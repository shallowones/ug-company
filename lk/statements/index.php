<?
define("NEED_AUTH", true); //доступ только авторизованным
define("STATEMENT", "Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заявления");
?>

    <section class="main-head">
        <div class="wrapper">Список заявлений</div>
    </section>
    <section class="tabs-block">
        <div class="tabs wrapper js-tabs">
            <button class="tabs__item active" type="button" data-tab="#all">Все</button>
            <button class="tabs__item" type="button" data-tab="#complited">Завершенные</button>
        </div>
    </section>
    <section class="content-block">
        <div class="content wrapper">

<?$APPLICATION->IncludeComponent(
	"ugraweb:statements",
	"",
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
		"COL_NAME" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_PAGE_URL" => "/statements/#ID#/",
		"DRAFTS_ADD_URL" => "/statements/add/?draft=#ID#&dsave=#ID#",
		"DRAFTS_PAGE_URL" => "/statements/drafts/",
		"GROUPS_FOR_SELECT_LIST" => array("INDIVIDUALS","ENTREPRENEUR","ORGANIZATION",""),
		"GROUP_MODERATORS" => "MODERATORS",
		"HL_CODE" => "Statements",
		"HL_CODE_DRAFTS" => "StatementsDrafts",
		"HL_CODE_MESSAGES" => "StatementsMessages",
		"HL_CODE_REQUISITES" => "OrganizationRequisites",
		"HL_FIELDS_STATUS" => ['UF_STATUS'],
		"ORGANIZATION_FIELD_CODE" => "UF_ORG_ID",
		"SEF_FOLDER" => "/lk/statements/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("add"=>"add/","detail"=>"#ELEMENT_ID#/","drafts"=>"drafts/","edit"=>"#ELEMENT_ID#/edit/","list"=>"","status"=>"#ELEMENT_ID#/status/"),
		"STEPS_STATUS" => ['step-1'=>['id'=>'step-1',
		"controls" => ['UF_STATUS']]],
		"label" => "Редактирование статуса"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
))?>

    <div class="content-right">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "right-menu",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "left",
                "USE_EXT" => "N",
                "COMPONENT_TEMPLATE" => "right-menu"
            ),
            false
        );?>
        <div class="side exit"><a class="side__item" href="?logout=yes">Выйти из аккаунта</a></div>
    </div>
    </div>
    </section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>