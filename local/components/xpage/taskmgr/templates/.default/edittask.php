<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>

<?php
	$APPLICATION->SetTitle("{$arResult['TASK']['NAME']} - Редактировать задачу - Планировщик заданий");
?>
<form class="" action="" method="post">
    <input type="hidden" name="form_id" value="form_edittask">
    <input type="hidden" name="TASK_ID" value="<?= ($arResult['TASK']['ID']) ?>">
    <table style="width: 100%;">
        <tr>
            <td>
                <label for="task_active">Активность</label>
            </td>
            <td>
                <select id="task_active" name="active">
                    <option value="Y" <?php if ($arResult['TASK']['ACTIVE'] == 'Y'): ?> selected <?php endif; ?> >Да
                    </option>
                    <option value="N" <?php if ($arResult['TASK']['ACTIVE'] == 'N'): ?> selected <?php endif; ?> >Нет
                    </option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_active">Автозапуск</label>
            </td>
            <td>
                <select id="task_active" name="AUTORUN">
                    <option value="Y" <?php if ($arResult['TASK']['AUTORUN'] == 'Y'): ?> selected <?php endif; ?> >Да
                    </option>
                    <option value="N" <?php if ($arResult['TASK']['AUTORUN'] == 'N'): ?> selected <?php endif; ?> >Нет
                    </option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_name">Название задачи</label>
            </td>
            <td>
                <input id="task_name" type="text" name="name" value="<?= ($arResult['TASK']['NAME']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_priority">Приоритет</label>
            </td>
            <td>
                <input id="task_priority" type="text" name="priority" value="<?= ($arResult['TASK']['PRIORITY']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_interval">Интервал</label>
            </td>
            <td>
                <div class="default-input">
                    <div class="default-input__wrapper">
                        <div class="default-input__input">
                            <input id="task_interval" type="text" name="interval"
                                   value="<?= ($arResult['TASK']['TASK_INTERVAL']) ?>">
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label for="TASK_GROUP">Группа</label>
            </td>
            <td>
                <input id="TASK_GROUP" type="text" name="TASK_GROUP" value="<?= ($arResult['TASK']['TASK_GROUP']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="NOTIFICATIONS_COUNT">Количество сообщений подряд о неуспешном обмене</label>
            </td>
            <td>
                <input id="NOTIFICATIONS_COUNT" type="text" name="NOTIFICATIONS_COUNT"
                       value="<?= ($arResult['TASK']['NOTIFICATIONS_COUNT']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_time_start">Время запуска (ЧЧ:ММ)</label>
            </td>
            <td>
                <input id="task_time_start" type="text" name="time_start"
                       value="<?= ($arResult['TASK']['TIME_START']) ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_command">Команда</label>
            </td>
            <td>
                <select class="" name="command">
					<?php foreach ($arResult['COMMANDS'] as $arCommand): ?>
                        <option value="<?= $arCommand['COMMAND'] ?>" <?php if ($arResult['TASK']['COMMAND'] == $arCommand['COMMAND']): ?> selected <?php endif; ?> ><?= $arCommand['NAME'] ?></option>
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
                    <option value="Y" <?php if ($arResult['TASK']['ENABLE_SMS'] == 'Y'): ?> selected <?php endif; ?> >
                        Да
                    </option>
                    <option value="N" <?php if ($arResult['TASK']['ENABLE_SMS'] == 'N'): ?> selected <?php endif; ?> >
                        Нет
                    </option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_sms_phones">Номера для оповещения по СМС</label>
            </td>
            <td>
                <textarea id="task_sms_phones" name="sms_phones" rows="8"
                          cols="40"><?= ($arResult['TASK']['SMS_PHONES']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="SMS_MESSAGE_OK">Текст СМС (ОК)</label>
            </td>
            <td>
                <textarea id="SMS_MESSAGE_OK" name="SMS_MESSAGE_OK" rows="8"
                          cols="40"><?= ($arResult['TASK']['SMS_MESSAGE_OK']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="SMS_MESSAGE_ERROR">Текст СМС (Ошибка)</label>
            </td>
            <td>
                <textarea id="SMS_MESSAGE_ERROR" name="SMS_MESSAGE_ERROR" rows="8"
                          cols="40"><?= ($arResult['TASK']['SMS_MESSAGE_ERROR']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_enable_mail">Оповещать по Email</label>
            </td>
            <td>
                <select id="task_enable_mail" name="enable_mail">
                    <option value="Y" <?php if ($arResult['TASK']['ENABLE_MAIL'] == 'Y'): ?> selected <?php endif; ?> >
                        Да
                    </option>
                    <option value="N" <?php if ($arResult['TASK']['ENABLE_MAIL'] == 'N'): ?> selected <?php endif; ?> >
                        Нет
                    </option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="task_email_addresses">Адреса для оповещения по Email</label>
            </td>
            <td>
                <textarea id="task_email_addresses" name="email_addresses" rows="8"
                          cols="40"><?= ($arResult['TASK']['EMAIL_ADDESSES']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="EMAIL_MESSAGE_OK">Текст Email (ОК)</label>
            </td>
            <td>
                <textarea id="EMAIL_MESSAGE_OK" name="EMAIL_MESSAGE_OK" rows="8"
                          cols="40"><?= ($arResult['TASK']['EMAIL_MESSAGE_OK']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="EMAIL_MESSAGE_ERROR">Текст Email (Ошибка)</label>
            </td>
            <td>
                <textarea id="EMAIL_MESSAGE_ERROR" name="EMAIL_MESSAGE_ERROR" rows="8"
                          cols="40"><?= ($arResult['TASK']['EMAIL_MESSAGE_ERROR']) ?></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" name="submit" value="Сохранить" class="btn btn_red">
    <input type="submit" name="delete" value="Удалить" class="btn btn_red">
</form>
