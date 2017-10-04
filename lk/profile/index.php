<?
define("NEED_AUTH", true); //доступ только авторизованным
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION, $USER;
$APPLICATION->SetTitle("Профиль");
?><? $boolSelectedGroup = $APPLICATION->IncludeComponent(
            "ugraweb:groups.edit",
            ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "GROUPS_FOR_SELECT_LIST" => array(
                    0 => "",
                    1 => "INDIVIDUALS",
                    2 => "ENTREPRENEUR",
                    3 => "ORGANIZATION",
                    4 => "",
                ),
                "GROUP_AFTER_REGISTRATION" => "REGISTERED_USERS",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        );?>
	        <? if ($boolSelectedGroup): // если группа у пользователя уже установлена ?>
	            <?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"main-profile",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CHECK_RIGHTS" => "N",
		"COMPONENT_TEMPLATE" => "main-profile",
		"SEND_INFO" => "N",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(0=>"UF_PASSPORT_SERIES",1=>"UF_PASSPORT_NUMBER",2=>"UF_PASSPORT_CODE",3=>"UF_PASSPORT_ISSUED",4=>"UF_PASSPORT_DATE",5=>"UF_SNILS",),
		"USER_PROPERTY_NAME" => "",
		"USER_ROWS" => array(0=>"NAME",1=>"SECOND_NAME",2=>"LAST_NAME",3=>"PERSONAL_PHONE",)
	)
);?>
	            <? // Редактирование реквизитов ?>
	            <?$APPLICATION->IncludeComponent(
	"ugraweb:requisites",
	".default",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"GROUPS_FOR_SELECT_LIST" => array(0=>"",1=>"INDIVIDUALS",2=>"ENTREPRENEUR",3=>"ORGANIZATION",4=>"",),
		"GROUP_MODERATORS" => "MODERATORS",
		"HL_CODE" => "OrganizationRequisites",
		"ORGANIZATION_FIELD_CODE" => "UF_ORG_ID",
		"SEF_FOLDER" => "/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array("requisites"=>"",)
	)
);?>
            <?/* $APPLICATION->IncludeComponent(
		"ugraweb:hl.edit",
		".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"GROUPS_FOR_SELECT_LIST" => array(
				0 => "INDIVIDUALS",
				1 => "ENTREPRENEUR",
				2 => "ORGANIZATION",
				3 => "",
			),
			"HL_CODE" => "OrganizationRequisites",
			"ORGANIZATION_FIELD_CODE" => 'UF_ORG_ID',
			"GROUP_MODERATORS" => 'MODERATORS',
			'CONTROLS' => [
				'INDIVIDUALS' => [
				],
				'ENTREPRENEUR' => [
				],
				'ORGANIZATION' => [
					'UF_ORG_NAME',
					'UF_ORG_INN',
					'UF_ORG_OGRN',
					'UF_UR_ADDRESS',
					'UF_FACT_ADDRESS',
					'UF_BANK_NAME',
					'UF_ORG_RS',
					'UF_ORG_KS',
					'UF_ORG_BIK',
					'UF_ORG_KPP',
					'UF_ORG_PHONE',
				]
			],
			'STEPS' => [
				'INDIVIDUALS' => [
					'step-1' => [
						'id' => 'step-1',
						'label' => 'Step 1',
						'controls' => []
					]
				],
				'ENTREPRENEUR' => [
					'step-1' => [
						'id' => 'step-1',
						'label' => 'Step 1',
						'controls' => []
					]
				],
				'ORGANIZATION' => [
					'step-1' => [
						'id' => 'step-1',
						'label' => 'Step 1',
						'controls' => [
							'UF_ORG_NAME',
							'UF_ORG_INN',
							'UF_ORG_OGRN',
							'UF_UR_ADDRESS',
							'UF_FACT_ADDRESS',
							'UF_BANK_NAME',
							'UF_ORG_RS',
							'UF_ORG_KS',
							'UF_ORG_BIK',
							'UF_ORG_KPP',
							'UF_ORG_PHONE',
						]
					]
				]
			]
		),
		false
	); */?>
		<? endif; ?>