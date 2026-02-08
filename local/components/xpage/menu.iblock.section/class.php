<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Loader;

class MenuSectionComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return [
            "IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
            "CACHE_TIME" => $arParams["CACHE_TIME"] ?? 3600,
        ];
    }



    private function getMenuTree()
    {
        $iblockId = (int)$this->arParams["IBLOCK_ID"];
        $sections = [];

        $sectionEntity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($iblockId);
        $sectionQuery = new Query($sectionEntity);

        $sectionQuery
            ->setSelect([
                'ID',
                'NAME',
                'UF_LINK'
            ])
            ->setFilter([
                'ACTIVE' => 'Y'

            ])
            ->setOrder(['SORT' => 'ASC']);

        $rsSections = $sectionQuery->exec();

        while ($section = $rsSections->fetch()) {
            $section["SUBMENU"] = [];
            $section["URL"] = $section["UF_LINK"];
            $sections[$section["ID"]] = $section;
        }

        $elementQuery = new Query(ElementTable::getEntity());
        $elementQuery
            ->setSelect(['ID', 'NAME', 'IBLOCK_SECTION_ID'])
            ->setFilter([
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y'
            ])
            ->setOrder(['SORT' => 'ASC']);

        $rsElements = $elementQuery->exec();
        while ($el = $rsElements->fetch()) {
            if ($el["IBLOCK_SECTION_ID"] && isset($sections[$el["IBLOCK_SECTION_ID"]])) {
                $sections[$el["IBLOCK_SECTION_ID"]]["SUBMENU"][] = [
                    "NAME" => $el["NAME"],
                    // Низкий уровень: формируем ссылку на детальную страницу вручную
                    "URL" => $el["UF_LINK"],
                    "IS_ELEMENT" => "Y"
                ];
            }
        }

        return $this->buildTree($sections);
    }

    private function buildTree(array $flatList): array
    {
        $tree = [];
        foreach ($flatList as $id => &$node) {
            if (!$node["IBLOCK_SECTION_ID"]) {
                $tree[$id] = &$node;
            } else {
                if (isset($flatList[$node["IBLOCK_SECTION_ID"]])) {
                    $flatList[$node["IBLOCK_SECTION_ID"]]["SUBMENU"][] = &$node;
                }
            }
        }
        return $tree;
    }
    public function executeComponent()
    {
        if ($this->startResultCache()) {
            if (!Loader::includeModule("iblock")) {
                $this->abortResultCache();
                return;
            }

            $this->arResult["ITEMS"] = $this->getMenuTree();
            $this->includeComponentTemplate();
        }
    }
}