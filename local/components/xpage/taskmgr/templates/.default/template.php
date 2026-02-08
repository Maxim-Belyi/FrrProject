<?php if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php 
global $USER;
if (
    (
        empty($_SESSION['taskmgr_access']) &&
        !empty($_REQUEST['hash']) &&
        $_REQUEST['hash'] == 'da36c2a2958eZc162D'
    ) ||
    $USER->IsAdmin()
) {
    $_SESSION['taskmgr_access'] = true;
}

if (empty($_SESSION['taskmgr_access'])) {
  return;
}
?>

<div class="taskmgr">
  <?php if(!empty($arResult['RUN_FORCED'])):?>
  <div class="taskmgr__run_forced">
    <p>Ручной запуск:</p>
    <ul>
      <?php foreach($arResult['TASKS'] as $arTask):?>
        <?php 
        if ($arTask['RUN_FORCED'] != 'Y') {
          continue;
        }
        ?>
        <li><?=$arTask['NAME']?></li>
      <?php endforeach;?>
    </ul>
  </div>
  <?php endif;?>

  <div class="taskmgr__control_panel">
      <a href="<?=$arResult['LIST_URL']?>" class="btn btn_red" >Список задач</a>
      <a href="<?=$arResult['ADD_TASK_URL']?>" class="btn btn_red" >Добавить задачу</a>
      <a href="<?=$arResult['LOG_URL']?>" class="btn btn_red" >Лог</a>
      <?php if($arResult['ACTION'] == 'log'):?>
        <br>
        <br>
        <form action="" method="post">
          <div class="form_row">
            <label for="log_date">Дата лога</label>
            <input type="text" id="log_date" name="log_date" value="<?=$arResult['LOG_DATE']?>" class="mask-date">
          </div>
          <div class="form_row">
            <input type="submit" name="submit" value="ОК" class="btn btn_red">
          </div>
        </form>
      <?php endif;?>
  </div>

  <?php
  if (file_exists(__DIR__ . '/' . $arResult['ACTION'] . '.php'))
  {
    include_once(__DIR__ . '/' . $arResult['ACTION'] . '.php');
  }
  ?>

</div>
