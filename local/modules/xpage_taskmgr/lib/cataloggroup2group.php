<?php
namespace Xpage;

use \Bitrix\Main,
    \Bitrix\Main\Entity,
    \Bitrix\Main\Localization\Loc;


Class CatalogGroup2GroupTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_catalog_group2group';
    }

    public static function getMap()
    {
        return [
            'ID' => new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            'CATALOG_GROUP_ID' => new Entity\IntegerField('CATALOG_GROUP_ID'),
            'GROUP_ID' => new Entity\IntegerField('GROUP_ID'),
            'BUY' => [
                'data_type' => 'boolean',
                'values' => array('N','Y'),
            ]
        ];
    }
}
