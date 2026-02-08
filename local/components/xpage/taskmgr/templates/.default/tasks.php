<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
} ?>
<?php
	/** @var CMain $APPLICATION */
	$APPLICATION->SetTitle('Список задач - Планировщик заданий');
	$sortedArray = [];
	/** @var array $arResult */
	foreach ($arResult['TASKS'] as $taskData)
	{
		$sortedArray[$taskData['TASK_GROUP']][] = $taskData;
	}
?>
<table>
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Перейти к логу</th>
        <th>ID</th>
        <th>Название</th>
        <th>Команда</th>
        <th>Группа</th>
        <th>Приоритет</th>
        <th>Интервал</th>
        <th>Активность</th>
        <th>Отправка СМС</th>
        <th>Отправка Email</th>
        <th>Время начала</th>
        <th>Время конца</th>
        <th>Кол-во запусков за сутки</th>
        <th>Текущий статус активности</th>
    </tr>
    </thead>
	<?php foreach ($sortedArray as $groupId => $taskList): ?>

        <tbody>
		<?php foreach ($taskList as $arTask): ?>
			<?php
			$style = "";
			if ($arTask['ACTIVE'] != 'Y')
			{
				$style = "background-color: gray;";
			}

			?>
            <tr style="<?= $style ?>">
                <td><a href="<?= $arTask['EDIT_URL'] ?>" class="btn btn_blue">редактировать</a></td>
                <td><a href="<?= $APPLICATION->GetCurPageParam('action=RUN_TASK&TASK_ID=' . $arTask['ID'], [
						'TASK_ID',
						'action',
					]) ?>" class="btn btn_red run_task">запустить</a></td>
                <td>
                    <a href="?action=log&taskId=<?= $arTask['ID'] ?>"
                       class="btn btn_red run_task">log</a>
                </td>
                <td><?= $arTask['ID'] ?></td>
                <td><?= $arTask['NAME'] ?></td>
                <td><?= $arResult['COMMANDS'][$arTask['COMMAND']]['NAME'] ?></td>
                <td><?= $arTask['TASK_GROUP'] ?></td>
                <td><?= $arTask['PRIORITY'] ?></td>
                <td><?= $arTask['TASK_INTERVAL'] ?></td>
                <td><?= ($arTask['ACTIVE'] == 'Y' ? 'Да' : 'Нет') ?></td>
                <td><?= ($arTask['ENABLE_SMS'] == 'Y' ? 'Да' : 'Нет') ?></td>
                <td><?= ($arTask['ENABLE_MAIL'] == 'Y' ? 'Да' : 'Нет') ?></td>
                <td style="text-align: center;"><?= $arTask['TIME_START'] ?></td>
                <td style="text-align: center;"><?= $arTask['TIME_END'] ?></td>
                <td style="text-align: center"><?= (int)$arTask['count'] ?></td>
                <td style="text-align: center; background-color: <?= $arTask['color'] ?>"><?= $arTask['title'] ?></td>
            </tr>
		<?php endforeach; ?>
        <tr>
            <td style="background-color: #6c7a89"
                colspan="15"></td>
        </tr>
        </tbody>
	<?php endforeach; ?>
</table>
