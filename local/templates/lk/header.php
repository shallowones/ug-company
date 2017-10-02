<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $APPLICATION;

$arPage = array(
    'css' => [
        SITE_TEMPLATE_PATH.'/fonts/bundle.css',
        SITE_TEMPLATE_PATH.'/js/vendor/jquery-filestyle/jquery-filestyle.min.css',
        SITE_TEMPLATE_PATH.'/css/example.wink.css',
        SITE_TEMPLATE_PATH.'/css/main.css'
    ],
    'js' => [
        SITE_TEMPLATE_PATH.'/js/vendor/jquery/jquery-3.2.1.min.js',
        SITE_TEMPLATE_PATH.'/js/vendor/jquery-filestyle/jquery-filestyle.min.js',
        SITE_TEMPLATE_PATH.'/js/vendor/hideShowPassword/hideShowPassword.min.js',
        SITE_TEMPLATE_PATH.'/js/main.js'
    ]
);

$oAsset = \Bitrix\Main\Page\Asset::getInstance();
foreach ($arPage['css'] as $css) {
    $oAsset->addCss($css);
}
foreach ($arPage['js'] as $js) {
    $oAsset->addJs($js);
}
?>

<!DOCTYPE html>
<html lang="<? echo LANGUAGE_ID ?>">
<head>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowHead() ?>
</head>
<body>
<div class="page">
    <div id="bx-panel"><? $APPLICATION->ShowPanel() ?></div>
    <header class="header">
        <div class="header-flex wrapper">
            <div class="header-title">АО «ЮграЭнерго»</div>

            <div class="header-acc">
                <?if ($USER->IsAuthorized()):
                    echo 'Здравствуйте, ' . $USER->GetFullName() . '!';?>
                <a class="header-acc__link" href="?logout=yes">Выйти из аккаунта</a>
                <?endif;?>
            </div>

        </div>
    </header>
    <main class="main">
        <div class="wrapper">
            <div class="content">