<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
} ?>

<?php
	$APPLICATION->SetTitle('Добавить задачу - Планировщик заданий');
?>
<form class="" action="" method="post">
    <input type="hidden" name="form_id" value="form_addtask">
    <table style="width: 100%;">
        <tr>
            <td>
                <label for="task_active">Активность</label>
            </td>
            <td>
                <select id="task_active" name="active">
                    <option value="Y">Да</option>
                    <option value="N">Нет</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_active">Автозапуск</label>
            </td>
            <td>
                <select id="task_active" name="AUTORUN">
                    <option value="Y">Да</option>
                    <option value="N">Нет</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_name">Название задачи</label>
            </td>
            <td>
                <input id="task_name" type="text" name="name" value="<?= htmlspecialcharsEx($_POST['name']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_priority">Приоритет</label>
            </td>
            <td>
                <input id="task_priority" type="text" name="priority" value="500">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_priority">Интервал</label>
            </td>
            <td>
                <input id="task_interval" type="text" name="interval" value="">
            </td>
        </tr>
        <tr>
            <td>
                <label for="NOTIFICATIONS_COUNT">Количество сообщений подряд о неуспешном обмене</label>
            </td>
            <td>
                <input id="NOTIFICATIONS_COUNT" type="text" name="NOTIFICATIONS_COUNT" value="1">
            </td>
        </tr>
        <tr>
            <td>
                <label for="TASK_GROUP">Группа</label>
            </td>
            <td>
                <input id="TASK_GROUP" type="text" name="TASK_GROUP" value="1">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_time_start">Время запуска (ЧЧ:ММ)</label>
            </td>
            <td>
                <input id="task_time_start" type="text" name="time_start"
                       value="<?= htmlspecialcharsEx($_POST['time_start']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_command">Команда</label>
            </td>
            <td>
                <select class="" name="command">
					<?php foreach ($arResult['COMMANDS'] as $arCommand): ?>
                        <option value="<?= $arCommand['COMMAND'] ?>"><?= $arCommand['NAME'] ?></option>
					<?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_enable_sms">Оповещать по СМС</label>
            </td>
            <td>
                <select id="task_enable_sms" name="enable_sms">
                    <option value="Y">Да</option>
                    <option value="N">Нет</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_sms_phones">Номера для оповещения по СМС</label>
            </td>
            <td>
                <textarea id="task_sms_phones" name="sms_phones" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['sms_phones']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="SMS_MESSAGE_OK">Текст СМС (OK)</label>
            </td>
            <td>
                <textarea id="SMS_MESSAGE_OK" name="SMS_MESSAGE_OK" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['SMS_MESSAGE_OK']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="SMS_MESSAGE_ERROR">Текст СМС (Ошибка)</label>
            </td>
            <td>
                <textarea id="SMS_MESSAGE_ERROR" name="SMS_MESSAGE_ERROR" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['SMS_MESSAGE_ERROR']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_enable_mail">Оповещать по Email</label>
            </td>
            <td>
                <select id="task_enable_mail" name="enable_mail">
                    <option value="Y">Да</option>
                    <option value="N">Нет</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_email_addresses">Адреса для оповещения по Email</label>
            </td>
            <td>
                <textarea id="task_email_addresses" name="email_addresses" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['email_addresses']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="EMAIL_MESSAGE_OK">Текст Email (ОК)</label>
            </td>
            <td>
                <textarea id="EMAIL_MESSAGE_OK" name="EMAIL_MESSAGE_OK" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['EMAIL_MESSAGE_OK']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="EMAIL_MESSAGE_ERROR">Текст Email (Ошибка)</label>
            </td>
            <td>
                <textarea id="EMAIL_MESSAGE_ERROR" name="EMAIL_MESSAGE_ERROR" rows="8"
                          cols="40"><?= htmlspecialcharsEx($_POST['EMAIL_MESSAGE_ERROR']) ?></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" name="submit" value="Добавить" class="btn btn_red">
</form>
