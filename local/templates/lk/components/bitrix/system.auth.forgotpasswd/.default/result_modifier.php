<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
?>
<?
    if($arParams['AUTH_RESULT']['TYPE'] == ('OK' or 'ERROR')){
        $arParams['AUTH_RESULT']['MESSAGE'] = 'На указанный Вами E-mail отправлена ссылка для смены пароля.<br>                                  
                            Не пришло письмо? Проверьте корректность введенного E-mail, либо отправить повторно.';
    }
