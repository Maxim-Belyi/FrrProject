<?php
namespace Xpage\Utils;
use Xpage\Utils\Tools;
use Bitrix\Iblock\IblockTable;

/**
 * @var CMain $APPLICATION
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
};

if (!defined("SITE_TEMPLATE_PATH_LAYOUT")) {
    define("SITE_TEMPLATE_PATH_LAYOUT", \Xpage\Utils\ManifestVueLoader::FRONT_LAYOUT_PATH);
}

use Bitrix\Main\Page\Asset;

?>

<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">

    <link rel="icon" type="image/png" sizes="96x96" href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/favicons/favicon-96x96.png">
    <link rel="icon" type="image/svg+xml" href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/favicons/favicon.svg">
    <link rel="shortcut icon" href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/favicons/apple-touch-icon.png">
    <!--    <link rel="manifest" href="--><?php //= SITE_TEMPLATE_PATH_LAYOUT
    ?><!-- . '/favicons/site.webmanifest'">-->

    <meta property="og:title" content="Главная">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:description" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="">
    <meta property="og:image" content="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/opengraph-image.jpg">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="568">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/opengraph-image.jpg">

    <? $APPLICATION->ShowHead() ?>
    <title>
        <? $APPLICATION->ShowTitle() ?>
    </title>

    <div id="panel">
        <?php $APPLICATION->ShowPanel(); ?>
    </div>
    <? ManifestVueLoader::loadLayout() ?>
</head>

<body class="page">
<div class="layout" id="app">
    <suspense>
        <root-component csrf-token="" captcha-key=""
                        :docs="[{ code: 'user-agreement', link: '#' }, { code: 'privacy-policy', link: '#' }]">
            <header class="header header--transparent" v-toggle-header>
                <div class="wrapper">
                    <div class="header__inner"><a class="header__logo logo" href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/">
                            <picture class="img logo__img"><img class="img__image"
                                                                src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/logo.svg"
                                                                alt="alt" width="190" height="48" loading="lazy">
                            </picture>
                        </a>

                        <?php
                        $APPLICATION->IncludeComponent(
                                "xpage:menu.iblock.section",
                                "top.menu.custom",
                                [
                                        "IBLOCK_TYPE" => "News",
                                        "IBLOCK_ID" => Tools::getIblockId('headerMenu') ,
                                ],
                                false
                        );
                        ?>
                        <div class="header__map"><a class="btn btn--color-primary" href="#"><span class="btn__text">Интерактивная карта</span></a>
                        </div>
                        <svg class="header__burger-menu" aria-hidden="true" data-menu-open>
                            <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-burger-menu"></use>
                        </svg>
                    </div>
                </div>
                <div class="mobile-menu">
                    <div class="wrapper">
                        <div class="mobile-menu__header">
                            <div class="mobile-menu__logo logo">
                                <picture class="img logo__img"><img class="img__image"
                                                                    src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/logo-header.svg"
                                                                    alt="alt" width="220" height="27" loading="lazy">
                                </picture>
                            </div>
                            <svg class="mobile-menu__close" aria-hidden="true" data-menu-close>
                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-close"></use>
                            </svg>
                        </div>
                        <div class="mobile-menu__inner">
                            <div class="mobile-menu__list"><a class="mobile-menu__link link" href="#">Медиа</a><a
                                        class="mobile-menu__link link" href="#">Методический кабинет</a><a
                                        class="mobile-menu__link link" href="#">Проекты</a><a
                                        class="mobile-menu__link link" href="#">FAQ</a><a class="mobile-menu__link link"
                                                                                          href="#">Команда</a><a
                                        class="mobile-menu__link link" href="#">Контакты</a></div>
                            <div class="mobile-menu__btn btn btn--color-primary">Интерактивная карта</div>
                        </div>
                    </div>
                </div>
            </header>


