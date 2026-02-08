<?php
declare(strict_types=1);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Проверка прав доступа: страница доступна только администраторам
global $USER;
if (!$USER->IsAdmin()) {
    echo "Доступ запрещен";
    die();
}

// Подключаем необходимые модули
CModule::IncludeModule("main");
CModule::IncludeModule("security");

use Bitrix\Security\Mfa\Otp;
use Bitrix\Main\Web\HttpClient;

/**
 * Class UserGenerator
 *
 * Обёртка для создания пользователей с использованием API Битрикса,
 * генерации пароля и установки двухфакторной авторизации (TOTP).
 */
class UserGenerator
{
    /** @var CUser */
    protected CUser $user;

    public function __construct()
    {
        $this->user = new CUser();
    }

    /**
     * Ищет ID группы по символьному коду.
     *
     * @param string $code
     * @return int|null
     */
    public function getGroupIdByCode(string $code): ?int
    {
        $groupId = null;
        $rsGroup = CGroup::GetList($by = "c_sort", $order = "asc", ["STRING_ID" => $code]);
        if ($arGroup = $rsGroup->Fetch()) {
            $groupId = (int)$arGroup["ID"];
        }
        return $groupId;
    }

    /**
     * Генерирует и активирует TOTP-секрет для пользователя.
     * Устанавливает тип TOTP, регенерирует секрет, форматирует его в группы по 4 символа через пробел и активирует OTP.
     *
     * @param int $userId
     * @return string
     */
    public function generateSecretForUser(int $userId): string
    {
        $otp = Otp::getByUser($userId);
        $otp->setType(Otp::TYPE_TOTP);
        $otp->regenerate();

        $displayedSecret = chunk_split($otp->getAppSecret(), 4, ' ');
        $otp->activate();
        return trim($displayedSecret);
    }

    /**
     * Создаёт одноразовую ссылку на личные доступы через сервис xpaste.pro.
     *
     * Для создания заметки отправляется JSON POST запрос на https://xpaste.pro/paste
     * с параметрами:
     *  - language: text
     *  - type: password (устанавливает TTL=7 дней по умолчанию)
     *  - body: текст с данными доступа
     *  - auto_destroy: true
     *  - ttl_days: 7
     *
     * @param string $login
     * @param string $password
     * @param string $otpSecret
     * @return string Ссылка на страницу с доступами или сообщение об ошибке.
     */
    protected function createAccessLink(string $login, string $password, string $otpSecret): string
    {
        $apiUrl = 'https://xpaste.pro/paste';
        $bodyText = "Логин: $login\nПароль: $password\nOTP-секрет: $otpSecret";
        $postData = [
            'language'      => 'text',
            'type'          => 'password',
            'body'          => $bodyText,
            'auto_destroy'  => 'true',
            'ttl_days'      => 7,
        ];

        $httpClient = new HttpClient();
        $response = $httpClient->post($apiUrl, $postData);

        if (!$response) {
            return "Ошибка создания ссылки: " . $httpClient->getError();
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['url'])) {
            return $responseData['url'];
        }

        if (filter_var(trim($response), FILTER_VALIDATE_URL)) {
            return trim($response);
        }
        return "Ошибка: некорректный ответ сервиса xpaste.pro";
    }

    /**
     * Создаёт пользователя с заданными данными и включает для него TOTP.
     *
     * @param array $data Массив с данными:
     *                    - login (обязательный, англ. символы/цифры/подчёркивания)
     *                    - email (опционально)
     *                    - name (опционально)
     *                    - last_name (опционально)
     *                    - is_xpage (опционально, 'Y' если сотрудник xpage)
     *
     * @return array Массив с результатом: userID, login, email, password, otp_secret, access_link или error.
     */
    public function createUser(array $data): array
    {
        $password = \Bitrix\Main\Security\Random::getStringByAlphabet(15, \Bitrix\Main\Security\Random::ALPHABET_ALL, true);

        $fields = [
            "LOGIN"            => trim($data['login']),
            "EMAIL"            => trim($data['email'] ?? ''),
            "NAME"             => trim($data['name'] ?? ''),
            "LAST_NAME"        => trim($data['last_name'] ?? ''),
            "PASSWORD"         => $password,
            "CONFIRM_PASSWORD" => $password,
            "ACTIVE"           => "Y",
        ];

        // Добавляем группу администраторов (ID=1)
        $groupIds = [1];

        // Если выбран флаг "сотрудник xpage", ищем группу с символьным кодом "xpage" и добавляем
        if (!empty($data['is_xpage']) && $data['is_xpage'] === 'Y') {
            $xpageGroupId = $this->getGroupIdByCode("xpage");
            if ($xpageGroupId === null) {
                // Если группа не найдена, создаем её
                $group = new CGroup;
                $groupFields = [
                    "ACTIVE"      => "Y",
                    "C_SORT"      => 500,
                    "NAME"        => "Сотрудники xpage",
                    "STRING_ID"   => "xpage",
                    "DESCRIPTION" => "Группа для сотрудников xpage"
                ];
                $xpageGroupId = $group->Add($groupFields);
                if (!$xpageGroupId) {
                    error_log("Не удалось создать группу 'xpage': " . $group->LAST_ERROR);
                }
            }

            if ($xpageGroupId !== null && intval($xpageGroupId) > 0) {
                $groupIds[] = $xpageGroupId;
            }
        }
        $fields["GROUP_ID"] = $groupIds;

        $userID = $this->user->Add($fields);
        if (intval($userID) <= 0) {
            return ["error" => $this->user->LAST_ERROR];
        }

        $otpSecret = $this->generateSecretForUser((int)$userID);

        // Создаем одноразовую ссылку с личными доступами через xpaste.pro
        $accessLink = $this->createAccessLink($fields["LOGIN"], $password, $otpSecret);

        return [
            "userID"      => $userID,
            "login"       => $fields["LOGIN"],
            "email"       => $fields["EMAIL"],
            "password"    => $password,
            "otp_secret"  => $otpSecret,
            "access_link" => $accessLink,
        ];
    }
}

/**
 * Class RequestHandler
 *
 * Делегирует обработку POST-запроса для создания пользователей.
 */
class RequestHandler
{
    /**
     * Обрабатывает POST-запрос и возвращает массив результатов.
     *
     * @return array
     */
    public static function handleRequest(): array
    {
        $results = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['users']) && is_array($_POST['users'])) {
            $generator = new UserGenerator();
            foreach ($_POST['users'] as $userData) {
                // Валидация логина: обязателен и должен содержать только английские буквы, цифры и знак подчёркивания.
                if (empty($userData['login'])) {
                    $results[] = ["error" => "Логин обязателен"];
                    continue;
                }
                if (!preg_match('/^[a-zA-Z0-9_@.]+$/', $userData['login'])) {
                    $results[] = ["error" => "Логин должен содержать только англ. буквы, цифры и знак подчёркивания"];
                    continue;
                }
                $results[] = $generator->createUser($userData);
            }
        }
        return $results;
    }
}

$results = RequestHandler::handleRequest();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Генерация пользователей с TOTP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .user-entry { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; }
        .user-entry input { margin-right: 10px; }
        .remove-entry { color: red; cursor: pointer; margin-left: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        .error { color: red; }
    </style>
</head>
<body>
<h1>Генерация пользователей с TOTP</h1>

<form method="post" id="userForm">
    <div id="userEntries">
        <!-- Первая запись для пользователя -->
        <div class="user-entry">
            <label>Логин (англ.): <input type="text" name="users[0][login]" required></label>
            <label>Email: <input type="email" name="users[0][email]"></label>
            <label>Имя: <input type="text" name="users[0][name]"></label>
            <label>Фамилия: <input type="text" name="users[0][last_name]"></label>
            <label>
                <input type="checkbox" name="users[0][is_xpage]" value="Y" checked>
                Сотрудник xpage
            </label>
            <span class="remove-entry" onclick="removeEntry(this)">Удалить</span>
        </div>
    </div>
    <button type="button" onclick="addEntry()">Добавить пользователя</button>
    <br><br>
    <input type="submit" value="Создать пользователей">
</form>

<?php if (!empty($results)) : ?>
    <h2>Результаты создания пользователей</h2>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Логин</th>
            <th>Email</th>
            <th>Пароль</th>
            <th>TOTP Секрет</th>
            <th>Личная ссылка доступа</th>
            <th>Сообщение об ошибке</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $index => $res): ?>
            <tr>
                <td><?= ($index + 1) ?></td>
                <td><?= isset($res['login']) ? htmlspecialchars($res['login']) : '' ?></td>
                <td><?= isset($res['email']) ? htmlspecialchars($res['email']) : '' ?></td>
                <td><?= isset($res['password']) ? htmlspecialchars($res['password']) : '' ?></td>
                <td><?= isset($res['otp_secret']) ? htmlspecialchars($res['otp_secret']) : '' ?></td>
                <td>
                    <?php if (isset($res['access_link']) && filter_var($res['access_link'], FILTER_VALIDATE_URL)): ?>
                        <a href="<?= htmlspecialchars($res['access_link']) ?>" target="_blank">Перейти</a>
                    <?php else: ?>
                        <?= htmlspecialchars($res['access_link'] ?? '') ?>
                    <?php endif; ?>
                </td>
                <td><?= isset($res['error']) ? '<span class="error">' . htmlspecialchars($res['error']) . '</span>' : '' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    let entryIndex = 1;

    const addEntry = () => {
        const container = document.getElementById('userEntries');
        const div = document.createElement('div');
        div.className = 'user-entry';
        div.innerHTML = `
            <label>Логин (англ.): <input type="text" name="users[${entryIndex}][login]" required></label>
            <label>Email: <input type="email" name="users[${entryIndex}][email]"></label>
            <label>Имя: <input type="text" name="users[${entryIndex}][name]"></label>
            <label>Фамилия: <input type="text" name="users[${entryIndex}][last_name]"></label>
            <label><input type="checkbox" name="users[${entryIndex}][is_xpage]" value="Y" checked> Сотрудник xpage</label>
            <span class="remove-entry" onclick="removeEntry(this)">Удалить</span>`;
        container.appendChild(div);
        entryIndex++;
    };

    const removeEntry = (element) => {
        const entry = element.parentNode;
        entry.parentNode.removeChild(entry);
    }
</script>
</body>
</html>
