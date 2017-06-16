<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?>

					<h1>Поиск по сайту</h1>

<?$APPLICATION->IncludeComponent("bitrix:search.page", "seach", Array(
	"RESTART" => "N",	// Искать без учета морфологии (при отсутствии результата поиска)
		"CHECK_DATES" => "N",	// Искать только в активных по дате документах
		"USE_TITLE_RANK" => "N",	// При ранжировании результата учитывать заголовки
		"DEFAULT_SORT" => "rank",	// Сортировка по умолчанию
		"FILTER_NAME" => "",	// Дополнительный фильтр
		"arrFILTER" => array(	// Ограничение области поиска
			0 => "main",
			1 => "iblock_news",
			2 => "iblock_documents",
		),
		"arrFILTER_main" => "",	// Путь к файлу начинается с любого из перечисленных
		"arrFILTER_iblock_news" => array(	// Искать в информационных блоках типа "iblock_news"
			0 => "all",
		),
		"arrFILTER_iblock_documents" => array(	// Искать в информационных блоках типа "iblock_documents"
			0 => "all",
		),
		"SHOW_WHERE" => "Y",	// Показывать выпадающий список "Где искать"
		"arrWHERE" => array(	// Значения для выпадающего списка "Где искать"
			0 => "iblock_photos",
			1 => "iblock_news",
			2 => "iblock_job",
			3 => "blog",
		),
		"SHOW_WHEN" => "N",	// Показывать фильтр по датам
		"PAGE_RESULT_COUNT" => "10",	// Количество результатов на странице
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над результатами
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под результатами
		"PAGER_TITLE" => "Результаты поиска",	// Название результатов поиска
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "",	// Название шаблона
		"USE_SUGGEST" => "N",	// Показывать подсказку с поисковыми фразами
		"SHOW_ITEM_TAGS" => "Y",	// Показывать теги документа
		"TAGS_INHERIT" => "Y",	// Сужать область поиска
		"SHOW_ITEM_DATE_CHANGE" => "Y",	// Показывать дату изменения документа
		"SHOW_ORDER_BY" => "Y",	// Показывать сортировку
		"SHOW_TAGS_CLOUD" => "N",	// Показывать облако тегов
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"SHOW_RATING" => "Y",
		"PATH_TO_USER_PROFILE" => "/forum/user/#USER_ID#/"
	),
	false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
