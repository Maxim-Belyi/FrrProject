<?php


namespace Xpage;

use Xpage\Api\RequestContext;
use RuntimeException;

//аккаунт xpagecaptcha@gmail.com -> новые коды заводить там
class Recaptcha
{
    public const PUBLIC_KEY = '6Ld6VecnA**********SuF_m2n2YmhAPFDPXDNSO';
    private const SERVER_KEY = '6Ld6Vecn**********8MoNi6REH0HHtSesgcSc';

    public static function sendNewCheckRecaptcha($token)
    {
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . self::SERVER_KEY . '&response=' . $token;
        $curl = curl_init($recaptcha_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, 1);
    }

    public static function checkRecaptcha($token = ""): bool
    {

        if(
            $GLOBALS['USER']->IsAuthorized()
            || $_SESSION['checkReCaptcha']['score'] > 0.3
            || LOCAL_DEPLOYMENT === true
        )
        {
            if(empty($_SESSION['checkReCaptcha'])){
                $result['score'] = 10;

                $result['local'] = LOCAL_DEPLOYMENT;
                $result['auth'] = $GLOBALS['USER']->IsAuthorized();

                $_SESSION['checkReCaptcha'] = $result;
            }
            return true;
        }

        if(empty($token)){
            return false;
        }


        $result = self::sendNewCheckRecaptcha($token);

        if($result && $result['score'] > 0.5)
        {
            $_SESSION['checkReCaptcha'] = $result;
            return true;
        }

        return false;

    }


    /** проверка на роботов через апи */
    public static function checkRecaptchaFromApi(RequestContext $context): bool
    {

        $arErrorsLang = [
            "ru" => [
                "notCaptcha" => "Не пройдена защита от роботов",
                "notKey" => 'Не передан ключ',
            ],
            "en" => [
                "notCaptcha" => "Robot protection failed",
                "notKey" => 'Key not sent',
            ],
        ];

        $lang = $context->get("language");
        if(empty($lang) || empty($arErrorsLang[$lang])){
            $lang = LANGUAGE_ID;
        }

        $tokenCaptcha = $context->get("tokenCaptcha");
        if(empty($tokenCaptcha))
        {
            throw new RuntimeException($arErrorsLang[$lang]['notKey']);
        }

        try
        {

            $checkCaptcha = self::checkRecaptcha($tokenCaptcha);

        }catch (\Throwable $e)
        {
            throw new RuntimeException($e->getMessage());
            return false;
        }


        if(!$checkCaptcha)
        {
            throw new RuntimeException($arErrorsLang[$lang]['notCaptcha']);
            return false;
        }

        return true;

    }

    public static function getSiteKey()
    {
        return self::PUBLIC_KEY;
    }

}