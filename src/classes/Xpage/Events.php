<?php
/*Здесь вызовы всех событий*/

namespace Xpage;

use \Bitrix\Main\Application;
use \Bitrix\Main\Page\Asset;
use \Xpage\Redirects;

class Events
{
    public static function OnBeforeIblockElementAdd()
    {

    }

    public static function OnAfterIblockElementAdd()
    {

    }

    public static function OnBeforePrologHandler()
    {
        $GLOBALS[ 'OG_IMAGE' ] = '/local/client-app/dist/img/opengraph-image.jpg';

        try
        {

            //редиректы
            Redirects::redirectByUrl();

        }catch (\Throwable $e)
        {

        }
    }



    static function OnEpilogHandler(){



        if(Application::getInstance()->getContext()->getRequest()->isAdminSection()){
            return;
        }

        global $APPLICATION;
        $context = Application::getInstance()->getContext();
        $request = Application::getInstance()->getContext()->getRequest();

        if (!empty($GLOBALS['OG_IMAGE'])) {
            $ogImgUrl = 'https://' . $_SERVER['SERVER_NAME'] . $GLOBALS['OG_IMAGE'];
            Asset::getInstance()->addString('<meta property="og:image" content="' . $ogImgUrl . '"/>', true);
            Asset::getInstance()->addString('<meta property="vk:image" content="' . $ogImgUrl . '"/>', true);
            Asset::getInstance()->addString('<meta property="twitter:image" content="' . $ogImgUrl . '"/>', true);
        }
        $GLOBALS['OG_IMAGE_WIDTH'] = "1200";
        if (!empty($GLOBALS['OG_IMAGE_WIDTH'])) {
            Asset::getInstance()->addString('<meta property="og:image:width" content="' . $GLOBALS['OG_IMAGE_WIDTH'] . '"/>', true);
        }

        $GLOBALS['OG_IMAGE_HEIGHT'] = "660";
        if (!empty($GLOBALS['OG_IMAGE_HEIGHT'])) {
            Asset::getInstance()->addString('<meta property="og:image:height" content="' . $GLOBALS['OG_IMAGE_HEIGHT'] . '"/>', true);
        }

        Asset::getInstance()->addString('<meta property="og:site_name" content="' . $context->getSiteObject()->get("SITE_NAME") . '" />', true);
        Asset::getInstance()->addString('<meta property="og:title" content="' . strip_tags(htmlspecialchars_decode($APPLICATION->getTitle())) . '" />', true);
        Asset::getInstance()->addString('<meta property="og:description" content="' . strip_tags(htmlspecialchars_decode($APPLICATION->GetProperty('description'))) . '" />', true);


        Asset::getInstance()->addString('<meta property="og:url" content="https://' . $_SERVER['SERVER_NAME'] . $request->getRequestedPageDirectory() . '" />', true);

        Asset::getInstance()->addString('<meta property="og:type" content="website" />', true);
        Asset::getInstance()->addString('<meta property="og:locale" content="ru_RU" />', true);
        Asset::getInstance()->addString('<meta name="twitter:card" content="summary_large_image">', true);
        Asset::getInstance()->addString('<meta name="format-detection" content="telephone=no">', true);

    }


}