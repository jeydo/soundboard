<?php
function cleanStrRewrite($str)
{
    $str = strip_tags($str);
    $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    $str = removeAccents($str);
    $str = str_replace('œ', 'oe', $str);
    $str = preg_replace('#[^0-9\pL]#u', '-', $str);

    return trim($str);
}
function removeAccents($txt)
{
    $accents = explode(",","á,é,í,ó,ú,ý,Á,É,Í,Ó,Ú,Ý,à,è,ì,ò,ù,À,È,Ì,Ò,Ù,ä,ë,ï,ö,ü,ÿ,Ä,Ë,Ï,Ö,Ü,â,ê,î,ô,û,Â,Ê,Î,Ô,Û,å,Å,ø,Ø,ß,ç,Ç,ã,ñ,õ,Ã,Ñ,Õ");
    $sans    = explode(",","a,e,i,o,u,y,A,E,I,O,U,Y,a,e,i,o,u,A,E,I,O,U,a,e,i,o,u,y,A,E,I,O,U,a,e,i,o,u,A,E,I,O,U,a,A,o,O,ss,c,C,a,n,o,A,N,O");

    return str_replace($accents, $sans, $txt);
}