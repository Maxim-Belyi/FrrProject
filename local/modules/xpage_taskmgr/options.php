<?php $module_id = "xpage_taskmgr";
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module_id . "/include.php");
IncludeModuleLangFile(__FILE__);

$arSettingsOptions = array(
  array('note' => 'Время на сервере: ' . FormatDate('j F Y H:i:s', time())),
  array("NAME", "Название", "Тест", array("text", 60)),
);

$aTabs = array(
  array(
    "DIV"   => "settings",
    "TAB"   => "Настройки",
    "ICON"  => "",
    "TITLE" => "Настройки"
  ),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);?>

<?php
if(($REQUEST_METHOD == "POST") && (strlen($Update . $Apply) > 0) && check_bitrix_sessid()) {
    foreach($arSettingsOptions as $option) {
        if(!is_array($option)) continue;
        $name = $option[0];
        $val = ${$name};
        COption::SetOptionString($module_id, $name, $val, $option[1]);
    }
    $Update = $Update . $Apply;
    ob_start();
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
    ob_end_clean();
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $tabControl->ActiveTabParam());
}
?>

<form method="POST" action="<?php  echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>">
    <?php  $tabControl->Begin(); ?>
        <?php $tabControl->BeginNextTab();?>
        <?php __AdmSettingsDrawList("xpage_taskmgr", $arSettingsOptions);?>

        <?php  $tabControl->Buttons(); ?>
        <input type="submit" name="Update" value="Сохранить">
        <input type="submit" name="Apply" value="Применить">
        <?= bitrix_sessid_post(); ?>
    <?php  $tabControl->End(); ?>
</form>
