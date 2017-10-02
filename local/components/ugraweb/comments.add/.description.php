<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$arComponentDescription = array(
	"NAME" => GetMessage("T_IBLOCK_DESC_CALENDAR"),
	"DESCRIPTION" => GetMessage("T_IBLOCK_DESC_CALENDAR_DESC"),
	"ICON" => "/images/iblock_calendar.gif",
	"SORT" => 40,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "ugraweb",
		"CHILD" => array(
			"ID" => "uqform",
			"NAME" => GetMessage("T_IBLOCK_DESC_NEWS"),
			"SORT" => 10,
		)
	),
);

?>
