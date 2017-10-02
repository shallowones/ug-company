<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) 
	die();

/******
название узла (PH_NODE_NAME)
	- название папки (PH_SECTION_NAME)
		- название компонента (PH_COMPONENT_NAME)
*******/

$arComponentDescription = array(
	"NAME" => GetMessage("PH_COMPONENT_NAME"), // название компонента 
	"DESCRIPTION" => GetMessage("PH_COMPONENT_DESC"), // описание компонента
	"ICON" => "/images/news_all.gif", // иконка
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "phweb", // ID узла
		"NAME" => GetMessage("PH_NODE_NAME"), // название узла
		"SORT" => 10,
		"CHILD" => array(
			"ID" => "ph_component", // ID раздела в которой лежит компонент
			"NAME" => GetMessage("PH_SECTION_NAME"), // название раздела в которой лежит компонент
			"SORT" => 10
		)
	)
);
?>