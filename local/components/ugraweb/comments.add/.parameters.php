<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if(!CModule::IncludeModule("iblock"))
	return;

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS"  =>  array(
		"EMAIL_TO" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("UGRAWEB_EMAIL_TO"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),		
		"OK_TEXT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("UGRAWEB_OK_TEXT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"USE_CAPTCHA" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("UGRAWEB_USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => false,
		),
	),
);
?>