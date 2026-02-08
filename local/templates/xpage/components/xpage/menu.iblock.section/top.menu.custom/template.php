<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>

<nav class="header__menu header-menu">
    <?php foreach ($arResult["ITEMS"] as $arSection): ?>

        <?php if (!empty($arSection["SUBMENU"])): ?>
            <?php ?>
            <header-dropdown
                    class="header-menu__item"
                    aria-id="menu-<?= $arSection["ID"] ?>"
                    placement="bottom-start"
                    :triggers="['hover']"
                    :popper-triggers="['hover']"
            >
                <template #trigger>
                    <a class="header-menu__title link" href="<?= $arSection["SECTION_PAGE_URL"] ?>">
                        <?= $arSection["NAME"] ?>
                    </a>
                </template>

                <div class="header-menu__list">
                    <?php foreach ($arSection["SUBMENU"] as $subItem): ?>
                        <a class="header-menu__link link" href="<?= $subItem["URL"] ?? $subItem["DETAIL_PAGE_URL"] ?>">
                            <?= $subItem["NAME"] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </header-dropdown>

        <?php else: ?>
            <?php ?>
            <div class="header-menu__item">
                <a class="header-menu__title link" href="<?= $arSection["SECTION_PAGE_URL"] ?>">
                    <?= $arSection["NAME"] ?>
                </a>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
</nav>