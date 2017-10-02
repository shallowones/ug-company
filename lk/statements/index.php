<?
define("NEED_AUTH", true); //доступ только авторизованным
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заявления");
?>

<?$APPLICATION->IncludeComponent(
	"ugraweb:statements",
	".default",
	array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COL_NAME" => "",
		"SEF_FOLDER" => "/lk/statements/",
		"SEF_MODE" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"GROUPS_FOR_SELECT_LIST" => array(
			0 => "INDIVIDUALS",
			1 => "ENTREPRENEUR",
			2 => "ORGANIZATION",
			3 => "",
		),
		"GROUP_MODERATORS" => "MODERATORS",
		"HL_CODE" => "Statements",
		"HL_CODE_REQUISITES" => "OrganizationRequisites",
        "HL_CODE_MESSAGES" => "StatementsMessages",
        "HL_CODE_DRAFTS" => "StatementsDrafts",
		"ORGANIZATION_FIELD_CODE" => "UF_ORG_ID",
		"DETAIL_PAGE_URL" => "/statements/#ID#/",
        "DRAFTS_PAGE_URL" => "/statements/drafts/",
        "DRAFTS_ADD_URL" => "/statements/add/?draft=#ID#&dsave=#ID#",
		"SEF_URL_TEMPLATES" => array(
			"list" => "",
			"detail" => "#ELEMENT_ID#/",
			"add" => "add/",
			"edit" => "#ELEMENT_ID#/edit/",
			"status" => "#ELEMENT_ID#/status/",
            "drafts" => "drafts/",
		),
        'HL_FIELDS_STATUS' => ['UF_STATUS'],
        'STEPS_STATUS' => [
            'step-1' => [
                'id' => 'step-1',
                'label' => 'Редактирование статуса',
                'controls' => ['UF_STATUS']
            ]
		]
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>