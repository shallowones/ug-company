<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

LocalRedirect("/lk/statements/");
$APPLICATION->SetTitle("Авторизация");

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>