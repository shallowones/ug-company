<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	"PARAMETERS" => array(
        "ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID комментируемого элемента",
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
        "HL_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код HL-блока",
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
	),
);
?>