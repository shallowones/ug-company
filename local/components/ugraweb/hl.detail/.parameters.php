<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
    return;

if($arCurrentValues["IBLOCK_ID"] > 0)
{
    $arIBlock = CIBlock::GetArrayByID($arCurrentValues["IBLOCK_ID"]);

    $bWorkflowIncluded = ($arIBlock["WORKFLOW"] == "Y") && CModule::IncludeModule("workflow");
    $bBizproc = ($arIBlock["BIZPROC"] == "Y") && CModule::IncludeModule("bizproc");
}

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
    $arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("IBLOCK_IBLOCK"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
        ),
        "HL_CODE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => 'Код HL-блока',
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
    ),
);