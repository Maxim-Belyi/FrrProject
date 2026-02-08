<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

/**
 * @var CMain $APPLICATION
 */
$isIndexPage = ($APPLICATION->GetCurPage(false) === SITE_DIR ? 'Y' : 'N');

?>

<footer class="footer">
    <div class="footer__bg">
        <picture class="img footer__bg-image"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/footer-bg.svg" alt="alt" width="674" height="320" loading="lazy"></picture>
    </div>
    <div class="wrapper">
        <div class="footer__inner">
            <div class="footer__info"><a class="footer__logo logo" href="/">
                    <picture class="img logo__img"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/logo-footer.svg" alt="alt" width="190" height="48" loading="lazy"></picture></a>
                <address class="footer__contacts footer-contacts">
                    <div class="footer-contacts__info"><a class="footer-contacts__link link title title--h4" href="mailto:info@frr-rb.ru">info@frr-rb.ru</a><a class="footer-contacts__link link title title--h4" href="tel:+7 (3012) 21-11-50">+7 (3012) 21-11-50</a></div>
                    <div class="footer-contacts__address">ул. Борсоева 19Б, офис 510 <br> Улан-Удэ, Республика Бурятия, 670000</div>
                    <div class="footer-contacts__socials">
                        <div class="socials">
                            <div class="socials__list"><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/tg.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/whatsapp.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/vk.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a>
                            </div>
                        </div>
                    </div>
                </address>
            </div>
            <div class="footer__menu footer-menu">
                <div class="footer-menu__top">
                    <div class="footer-menu__group">
                        <div class="footer-menu__head">
                            <div class="footer-menu__title">Медиа</div>
                        </div>
                        <div class="footer-menu__list"><a class="footer-menu__item link link--color-gray" href="#">Новости</a><a class="footer-menu__item link link--color-gray" href="#">Фото</a><a class="footer-menu__item link link--color-gray" href="#">Видео</a>
                        </div>
                    </div>
                    <div class="footer-menu__group">
                        <div class="footer-menu__head">
                            <div class="footer-menu__title">Проекты</div>
                        </div>
                        <div class="footer-menu__list"><a class="footer-menu__item link link--color-gray" href="#">О мастер-плане</a><a class="footer-menu__item link link--color-gray" href="#">Об опорных населенных пунктах</a>
                        </div>
                    </div>
                    <div class="footer-menu__group">
                        <div class="footer-menu__head"><a class="footer-menu__title link" href="#">Методический кабинет</a><a class="footer-menu__title link" href="#">FAQ</a><a class="footer-menu__title link" href="#">Команда</a><a class="footer-menu__title link" href="#">Контакты</a>
                        </div>
                    </div>
                    <div class="footer-menu__group footer-menu__socials">
                        <div class="socials">
                            <div class="socials__list"><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/tg.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/whatsapp.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a><a class="socials__item btn btn--color-tertiary btn--inverse" href="#" target="_blank">
                                    <picture class="img socials__item-icon"><img class="img__image" src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/socials/vk.svg" alt="alt" width="20" height="20" loading="lazy"></picture></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-menu__bottom">
                    <div class="footer-menu__privacy"><a class="footer-menu__privacy-link link link--color-gray" href="#">Политика конфиденциальности</a><a class="footer-menu__privacy-link link link--color-gray" href="#">Пользовательское соглашение</a></div>
                    <div class="footer-menu__copyright">
                        <div class="footer-menu__copyright-title">© 2024 «Company Name»</div>
                        <div class="footer-menu__copyright-link"><a class="footer-menu__link link" href="https://www.xpage.ru/">Сделано в Xpage</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</root-component>
</suspense>
<div class="modals" id="modals-container">
    <!--include /shared/modals/modal-callback-->
</div>
</div>
</body>
</html>
