<?php
declare(strict_types=1);

use Bitrix\Main\Loader;
use Xpage\Settings\Abstracts\CollectionAbstract;
use Xpage\Settings\Abstracts\ModelAbstract;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Collections\SettingCollection;
use Xpage\Settings\Collections\SettingFieldsCollection;
use Xpage\Settings\EntityTables\SettingFieldsTable;
use Xpage\Settings\EntityTables\SettingsTable;
use Xpage\Settings\Models\Fields\SettingBool;
use Xpage\Settings\Models\Fields\SettingFile;
use Xpage\Settings\Models\Fields\SettingFloat;
use Xpage\Settings\Models\Fields\SettingInt;
use Xpage\Settings\Models\Fields\SettingString;
use Xpage\Settings\Models\Fields\SettingTypeInterface;
use Xpage\Settings\Models\SettingFieldType;
use Xpage\Settings\SettingBuilder;
use Xpage\Settings\SettingTypeBuilder;

Loader::registerAutoLoadClasses('xpage.settings', [
	CollectionAbstract::class      => 'lib/Abstracts/CollectionAbstract.php',
	ModelAbstract::class           => 'lib/Abstracts/ModelAbstract.php',
	Setting::class                 => 'lib/Abstracts/Setting.php',
	SettingFieldsCollection::class => 'lib/Collections/SettingFieldsCollection.php',
	SettingCollection::class       => 'lib/Collections/SettingCollection.php',
	SettingFieldsTable::class      => 'lib/EntityTables/SettingFieldsTable.php',
	SettingsTable::class           => 'lib/EntityTables/SettingsTable.php',
	SettingFieldType::class        => 'lib/Models/SettingFieldType.php',
	SettingBool::class             => 'lib/Models/Fields/SettingBool.php',
	SettingString::class           => 'lib/Models/Fields/SettingString.php',
	SettingInt::class              => 'lib/Models/Fields/SettingInt.php',
	SettingFloat::class            => 'lib/Models/Fields/SettingFloat.php',
	SettingFile::class             => 'lib/Models/Fields/SettingFile.php',
	SettingTypeInterface::class    => 'lib/Models/Fields/SettingTypeInterface.php',
	SettingTypeBuilder::class      => 'lib/SettingTypeBuilder.php',
	SettingBuilder::class          => 'lib/SettingBuilder.php',
]);