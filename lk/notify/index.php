<?
define("NEED_AUTH", true); //доступ только авторизованным
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

\Bitrix\Main\Loader::includeModule('highloadblock');

//$statementId = '32';
//
//$statement = \HL\Base::initByCode('Statements');
//$result = $statement::update($statementId, [
//    'UF_MESSAGE' => uniqid()
//]);
//
//if ($result->isSuccess() === false) {
//    var_dump($result->getErrorMessages());
//}

/** @global CMain $APPLICATION */
global $APPLICATION;
$APPLICATION->IncludeComponent('ugraweb:notify.settings', '');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
