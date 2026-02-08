<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) ;

use Bitrix\Main\Loader;

class XpageMenuSectionComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return [
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"] ? $arParams["IBLOCK_TYPE"] : false,
            "CACHE_TIME"  => $arParams["CACHE_TIME"] ? $arParams["CACHE_TIME"] : 3600,
        ];
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

    private function getMenuTree()
    {
        $sections = [];
        $rsSections = CIBlockSection::GetList(
            ["SORT" => "ASC"],
            [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "GLOBAL_ACTIVE" => "Y",
            ],
            false,
            ["ID", "NAME", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID", "DEPTH_LEVEL"]
        );
        while ($section = $rsSections->GetNext()) {
            $section["SUBMENU"] = [];
            $sections[$section["ID"]] = $section;
        }
        $rsElements = CIBlockElement::GetList( //поменять на query
            ["SORT" => "ASC"],
            [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y"
            ],
            false,
            false,
            ["ID", "NAME", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"]
        );

        while ($el = $rsElements->GetNext()) {
            if ($el["IBLOCK_SECTION_ID"] && isset($sections[$el["IBLOCK_SECTION_ID"]])) {
                $sections[$el["IBLOCK_SECTION_ID"]]["SUBMENU"][] = [
                    "NAME" => $el["NAME"],
                    "URL" => $el["DETAIL_PAGE_URL"],
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
                $flatList[$node["IBLOCK_SECTION_ID"]]["SUBMENU"][] = &$node;
            }
        }
        return $tree;
    }
}
