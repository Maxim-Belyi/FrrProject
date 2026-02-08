<?php
#здесь пополняются методы работы с почтой
namespace Xpage\Utils;

class Email
{
    //анонимизация почты test@ya.ru -> t**t@ya.ru
    function anonymize(string $fullEmail):?string
    {
        $correctEmail = filter_var(trim($fullEmail),FILTER_VALIDATE_EMAIL);
        if(!$correctEmail)
        {
            return null;
        }
        $userName = explode('@',$correctEmail)[0];
        //если надо чтобы была только первая и последняя буквы, то $clearLetters = 1
        //в данном случае зависит от длины
        $clearLetters =ceil(mb_strlen($userName)/5);
        $parts = [
            substr($userName,0,$clearLetters),
            str_repeat('*',mb_strlen($userName)-($clearLetters*2)),
            substr($userName,-$clearLetters,$clearLetters),
        ];
        return implode('',$parts). '@'.explode('@',$correctEmail)[1];

    }

}