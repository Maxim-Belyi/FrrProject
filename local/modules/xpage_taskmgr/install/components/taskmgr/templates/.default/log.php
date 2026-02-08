<?php if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
/** @var CMain $APPLICATION */
/** @var array $arResult */
$APPLICATION->SetTitle('Лог - Планировщик заданий');
$arStatuses = array(
  'error' => 'Ошибка',
  'success' => 'Завершён',
  'process' => 'В процессе',
);
?>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Команда</th>
      <th>Статус</th>
      <th>Дата начала</th>
      <th>Дата завершения</th>
      <th>Длительность</th>
      <th>Лог</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($arResult['LOG'] as $arLog):?>
      <tr class=" <?=$arLog['STATUS']?> ">
        <td><?=$arLog['ID']?></td>
        <td><?=$arLog['TASK']['NAME']?></td>
        <td class="status"><?=$arStatuses[$arLog['STATUS']]?></td>
        <td><?=$arLog['DATETIME_START']?></td>
        <td><?=$arLog['DATETIME_END']?></td>
        <td><?=$arLog['INTERVAL']?></td>
        <td>
          <table>
            <?php foreach($arLog['MESSAGES'] as $arMessage):?>
              <tr>
                <td>
                  <?=$arMessage['DATETIME']?>
                </td>
                <td>
                  <pre>
                    <?=$arMessage['MESSAGE']?>
                  </pre>
                </td>
              </tr>
            <?php endforeach;?>
          </table>
        </td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>
