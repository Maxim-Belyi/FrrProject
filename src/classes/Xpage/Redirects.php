<?php

namespace Xpage;

use Xpage\Tools;
use \Bitrix\Main\Application;
use function LocalRedirect;

class Redirects
{



    public static function getRedirectList()
    {
        //редиректы
        return Tools::getHLB('RedirectList')::getList([
            'filter' => [],
            'select' => ['*'],
            'cache' => ['ttl' => 86400]
        ])->fetchAll();
    }

    public static function getUrl()
    {
        //убираем с url всякую шнягу
        $url = Application::getInstance()->getContext()->getRequest()->getRequestUri();
        $arUrl = explode("?", $url);
        $arUrl = explode("#", $arUrl[0]);
        return $arUrl[0];
    }


    public static function getDirectoriesMask()
    {
        return Application::getInstance()->getContext()->getRequest()->getRequestedPageDirectory();
    }

    public static function redirectByUrl()
    {
        $arRedirects = self::getRedirectList();
        $url = self::getUrl();
        $urlDirectory = self::getDirectoriesMask();

        if(Application::getInstance()->getContext()->getRequest()->isAdminSection()){
            return;
        }

        if(is_array($arRedirects) && count($arRedirects))
        {
            $found_key = array_search($url, array_column($arRedirects, 'UF_FROM'));
            if($found_key !== false && !empty($arRedirects[$found_key]['UF_TO'])){
                if((int)$arRedirects[$found_key]['UF_VID'] == 1){
                    LocalRedirect($arRedirects[$found_key]['UF_TO'], false, "301 Moved permanently");
                }else{
                    LocalRedirect($arRedirects[$found_key]['UF_TO']);
                }
            }

            if(!empty($urlDirectory))
            {
                $found_key = array_search($urlDirectory, array_column($arRedirects, 'UF_FROM'));
                if($found_key !== false && !empty($arRedirects[$found_key]['UF_TO']))
                {
                    if((int)$arRedirects[$found_key]['UF_VID'] == 1){
                        LocalRedirect($arRedirects[$found_key]['UF_TO'], false, "301 Moved permanently");
                    }else{
                        LocalRedirect($arRedirects[$found_key]['UF_TO']);
                    }
                }
            }

        }

    }

}