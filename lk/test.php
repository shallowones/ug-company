<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"",
	Array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FORGOT_PASSWORD_URL" => "/",
		"PROFILE_URL" => "/lk/profile/",
		"REGISTER_URL" => "/",
		"SHOW_ERRORS" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>