<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/** @var \CMain $APPLICATION */
$APPLICATION->SetTitle('Локалка ФРР РБ');
?>
    <main class="page__main">
        <section class="page__section section hero-section">
            <div class="hero-section__bg">
                <video class="hero-section__bg-video" autoplay muted loop>
                    <source src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/banner-video.webm" type="video/webm">
                </video>
                <div class="hero-section__bg-grid"></div>
            </div>
            <div class="wrapper">
                <div class="hero-section__wrapper">
                    <div class="hero-section__title title title--h1">Реализуя инвестиционные <br> проекты, мы делаем мир
                        <br> лучше и даем уверенность <br> в будущем!
                    </div>
                    <div class="hero-section__bottom">
                        <button class="hero-section__btn btn btn--color-tertiary btn--inverse"
                                @click="openModal('modal-video', { title: 'Заголовок видео в одну или несколько строк', poster: '<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/media/news-3.webp', source: [{ src: 'https://www.shutterstock.com/shutterstock/videos/1075356548/preview/stock-foobadgee-ice-hockey-rink-arena-professional-forward-player-breaks-defense-hitting-puck-with-stick-scores.mp4', type: 'video/mp4' },{ src: 'https://www.shutterstock.com/shutterstock/videos/1075356548/preview/stock-foobadgee-ice-hockey-rink-arena-professional-forward-player-breaks-defense-hitting-puck-with-stick-scores.webm', type: 'video/webm' }] })">
                            <span class="btn__text">Смотреть видео</span>
                            <svg class="btn__icon" aria-hidden="true">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-play"></use>
                            </svg>
                        </button>
                        <hero-slider class="hero-section__slider">
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="hero-slider__slide">
                                    <picture class="img hero-slider__slide-img"><img class="img__image"
                                                                                     src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-1.webp"
                                                                                     alt="alt" width="264" height="176"
                                                                                     loading="lazy"></picture>
                                    <div class="hero-slider__slide-info">
                                        <div class="hero-slider__slide-title title title--h5"
                                             title="Нацпроект &quot;Жилье и городская среда&quot;">Нацпроект "Жилье и
                                            городская среда"
                                        </div>
                                        <a class="hero-slider__slide-btn btn btn--color-secondary" href="#"><span
                                                    class="btn__text">Подробнее</span>
                                            <svg class="btn__icon" aria-hidden="true">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="hero-slider__slide-progress"></div>
                                </article>
                            </div>
                        </hero-slider>
                    </div>
                </div>
            </div>
        </section>
        <section class="page__section section main-about">
            <div class="wrapper">
                <div class="main-about__wrapper">
                    <div class="main-about__bg">
                        <picture class="img main-about__bg-image"><img class="img__image"
                                                                       src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/regional-map.webp"
                                                                       alt="alt" width="1158" height="760"
                                                                       loading="lazy"></picture>
                    </div>
                    <div class="main-about__top">
                        <div class="main-about__achievements achievements">
                            <div class="achievements__item achievements__item--full">
                                <div class="achievements__value title title--h1">10 000 000 000 ₽</div>
                                <div class="achievements__title">Объем инвестиций, привлеченных в проекты развития</div>
                            </div>
                            <div class="achievements__item">
                                <div class="achievements__value title title--h2">2</div>
                                <div class="achievements__title">Реализуемых мастер-плана и программы развития</div>
                            </div>
                            <div class="achievements__item">
                                <div class="achievements__value title title--h2">24</div>
                                <div class="achievements__title">Опорных населенных пункта в фокусе методологического
                                    центра
                                </div>
                            </div>
                        </div>
                        <div class="main-about__block">
                            <div class="main-about__block-title title title--h4">Опорные населенные пункты (ОНП) — это
                                ключевые населенные пункты, которые играют важную роль в социально-экономическом
                                развитии региона. Они могут служить центрами для предоставления различных услуг, таких
                                как образование, здравоохранение, торговля и транспорт. Опорные населенные пункты
                                выбираются на основе их стратегического положения, инфраструктуры и потенциала для
                                развития.
                            </div>
                            <a class="main-about__block-btn btn btn--color-secondary" href="#"><span class="btn__text">Подробнее</span>
                                <svg class="btn__icon" aria-hidden="true">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                </svg>
                            </a>
                        </div>
                        <div class="main-about__block">
                            <div class="main-about__block-title title title--h4">Мастер-планы представляют собой
                                стратегические документы, которые определяют долгосрочные цели и направления развития
                                городов и муниципальных образований. Они разрабатываются с целью улучшения качества
                                жизни населения, повышения экономической активности и устойчивого развития территорий.
                            </div>
                            <a class="main-about__block-btn btn btn--color-secondary" href="#"><span class="btn__text">Подробнее</span>
                                <svg class="btn__icon" aria-hidden="true">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="main-about__bottom">
                        <div class="main-about__banner">
                            <div class="main-about__banner-wrapper">
                                <div class="main-about__banner-title title title--h5">Изучите карту, чтобы узнать больше
                                    о развитии каждого города.
                                </div>
                                <div class="main-about__banner-btn btn btn--color-primary">Интерактивная карта</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="page__section section main-news" id="news-slider">
            <div class="wrapper">
                <div class="section__inner">
                    <div class="section__header">
                        <div class="section__title-block">
                            <div class="section__title title title--h2">Новости</div>
                            <a class="section__link link link--color-primary" href="#">Все новости
                                <svg class="link__icon" aria-hidden="true">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                </svg>
                            </a>
                        </div>
                        <div class="section__controls swiper-buttons">
                            <button class="swiper-button swiper-button-prev btn btn--color-secondary"></button>
                            <button class="swiper-button swiper-button-next btn btn--color-secondary"></button>
                        </div>
                    </div>
                    <div class="section__content">
                        <section-slider class="main-news__slider" id="news-slider" :space-between="0"
                                        :breakpoints="{ 1: { slidesPerView: 1.3 }, 481: { slidesPerView: 2.3 }, 1280: { slidesPerView: 3 } }">
                            <div class="swiper-slide">
                                <article class="news-card">
                                    <div class="news-card__preview">
                                        <picture class="img news-card__image"><img class="img__image"
                                                                                   src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-2.webp"
                                                                                   alt="alt" width="600" height="400"
                                                                                   loading="lazy"></picture>
                                    </div>
                                    <div class="news-card__bottom">
                                        <div class="news-card__actions">
                                            <div class="badges">
                                                <div class="badges__list">
                                                    <div class="badge">
                                                        <svg class="badge__icon" aria-hidden="true">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-calendar-mini"></use>
                                                        </svg>
                                                        <div class="badge__text">12 июля</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="news-card__btns"><a
                                                        class="news-card__btn btn btn--color-secondary" href="#">
                                                    <svg class="btn__icon" aria-hidden="true">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="news-card__info">
                                            <div class="news-card__title title title--h5"
                                                 title="Новая инициатива по улучшению инфраструктуры Бурятии Новая инициатива по улучшению инфраструктуры Бурятии">
                                                Новая инициатива по улучшению инфраструктуры Бурятии Новая инициатива по
                                                улучшению инфраструктуры Бурятии
                                            </div>
                                            <div class="news-card__desc"
                                                 title="Фонд регионального развития запускает проект по модернизации общественных пространств. Фонд регионального развития запускает проект по модернизации общественных пространств.">
                                                Фонд регионального развития запускает проект по модернизации
                                                общественных пространств. Фонд регионального развития запускает проект
                                                по модернизации общественных пространств.
                                            </div>
                                        </div>
                                    </div>
                                    <a class="news-card__link" href="#"></a>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="news-card">
                                    <div class="news-card__preview">
                                        <picture class="img news-card__image"><img class="img__image"
                                                                                   src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-2.webp"
                                                                                   alt="alt" width="600" height="400"
                                                                                   loading="lazy"></picture>
                                    </div>
                                    <div class="news-card__bottom">
                                        <div class="news-card__actions">
                                            <div class="badges">
                                                <div class="badges__list">
                                                    <div class="badge">
                                                        <svg class="badge__icon" aria-hidden="true">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-calendar-mini"></use>
                                                        </svg>
                                                        <div class="badge__text">12 июля</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="news-card__btns"><a
                                                        class="news-card__btn btn btn--color-secondary" href="#">
                                                    <svg class="btn__icon" aria-hidden="true">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="news-card__info">
                                            <div class="news-card__title title title--h5" title="Новая инициатива">Новая
                                                инициатива
                                            </div>
                                            <div class="news-card__desc" title="Фонд регионального развития запускает">
                                                Фонд регионального развития запускает
                                            </div>
                                        </div>
                                    </div>
                                    <a class="news-card__link" href="#"></a>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="news-card">
                                    <div class="news-card__preview">
                                        <picture class="img news-card__image"><img class="img__image"
                                                                                   src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-2.webp"
                                                                                   alt="alt" width="600" height="400"
                                                                                   loading="lazy"></picture>
                                    </div>
                                    <div class="news-card__bottom">
                                        <div class="news-card__actions">
                                            <div class="badges">
                                                <div class="badges__list">
                                                    <div class="badge">
                                                        <svg class="badge__icon" aria-hidden="true">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-calendar-mini"></use>
                                                        </svg>
                                                        <div class="badge__text">12 июля</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="news-card__btns"><a
                                                        class="news-card__btn btn btn--color-secondary" href="#">
                                                    <svg class="btn__icon" aria-hidden="true">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="news-card__info">
                                            <div class="news-card__title title title--h5"
                                                 title="Новая инициатива по улучшению инфраструктуры Бурятии Новая инициатива по улучшению инфраструктуры Бурятии">
                                                Новая инициатива по улучшению инфраструктуры Бурятии Новая инициатива по
                                                улучшению инфраструктуры Бурятии
                                            </div>
                                            <div class="news-card__desc"
                                                 title="Фонд регионального развития запускает проект по модернизации общественных пространств. Фонд регионального развития запускает проект по модернизации общественных пространств.">
                                                Фонд регионального развития запускает проект по модернизации
                                                общественных пространств. Фонд регионального развития запускает проект
                                                по модернизации общественных пространств.
                                            </div>
                                        </div>
                                    </div>
                                    <a class="news-card__link" href="#"></a>
                                </article>
                            </div>
                            <div class="swiper-slide">
                                <article class="news-card">
                                    <div class="news-card__preview">
                                        <picture class="img news-card__image"><img class="img__image"
                                                                                   src="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/content/main/news-2.webp"
                                                                                   alt="alt" width="600" height="400"
                                                                                   loading="lazy"></picture>
                                    </div>
                                    <div class="news-card__bottom">
                                        <div class="news-card__actions">
                                            <div class="badges">
                                                <div class="badges__list">
                                                    <div class="badge">
                                                        <svg class="badge__icon" aria-hidden="true">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-calendar-mini"></use>
                                                        </svg>
                                                        <div class="badge__text">12 июля</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="news-card__btns"><a
                                                        class="news-card__btn btn btn--color-secondary" href="#">
                                                    <svg class="btn__icon" aria-hidden="true">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH_LAYOUT ?>/img/icons.svg#icon-arrow-right-up"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="news-card__info">
                                            <div class="news-card__title title title--h5" title="Новая инициатива">Новая
                                                инициатива
                                            </div>
                                            <div class="news-card__desc" title="Фонд регионального развития запускает">
                                                Фонд регионального развития запускает
                                            </div>
                                        </div>
                                    </div>
                                    <a class="news-card__link" href="#"></a>
                                </article>
                            </div>
                        </section-slider>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>