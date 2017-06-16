<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('Главная');
?>
    <div class="container dd-shabow">
        <div class="hide-menu-desk hidden-lg-up">
            <div class="row">
                <div class="white-text topheader">Югорская генерирующая компания</div>
                <img alt="" src="/bitrix/templates/ugcompany/img/slider.jpg" style="width: 100%;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 push-md-3 hide-menu">
                <div class="row">
                    <div class="owl-carousel owl-theme" id="owl-header">
                        <div class="item">
                            <img alt="" src="/bitrix/templates/ugcompany/img/slider.jpg" style="width: 100%;height: 480px;">
                        </div>
                    </div>
                </div>
                <div class="orange slider-top">
                    <div class="white-text">Качество и надежность</div>
                    <p>В области топливно-энергетического
                        <br> комплекса Югры</p>
                </div>
            </div>
            <div class="col-md-3 pull-md-9 hide-menu">
                <div class="menu">
                    <div class="orange-text">Югорская генерирующая компания</div>
                    <?$APPLICATION->IncludeComponent("bitrix:menu", "main-left-menu", Array(
						 "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
							  "CHILD_MENU_TYPE" => "index",	// Тип меню для остальных уровней
							  "DELAY" => "N",	// Откладывать выполнение шаблона меню
							  "MAX_LEVEL" => "1",	// Уровень вложенности меню
							  "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
							  "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
							  "MENU_CACHE_TYPE" => "N",	// Тип кеширования
							  "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
							  "ROOT_MENU_TYPE" => "index",	// Тип меню для первого уровня
							  "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
							  "COMPONENT_TEMPLATE" => "main_menu"
						 ),
						 false
					);?>
					<form action="/search/" method="get">
						<input class="search_input" placeholder="Запрос для поиска" name="q" value="" style="margin: 10px 0px 20px 0px; width: 100%;" type="text">
					</form>
					<div class="clearfix"></div>
					<div class="info">
						<p class="phone">8 (3467) 37-93-30</p>
						<p class="email">ugk-2006@mail.ru</p>
					</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="row">
                    <div class="col-lg-12 no-border col-md-6 orange block nmrl investors">
                        <a href="/investors/" class="block-text_stroke">Акционерам и инвесторам</a>
                        <ul>
                            <li><a href="/investors/shareholding-structure/">Структура акционерного капитала</a></li>
                            <li><a href="/contacts/">Контакты для акционеров и инвесторов</a></li>
                        </ul>
                    </div>
                    <div class=" col-lg-12 no-border col-md-6 blue nmrl block clients">
                        <a href="/clients/" class="block-text_stroke">Клиентам</a>
                        <ul>
                            <li><a href="/clients/service-centers/">Центры обслуживания клиентов</a></li>
                            <li><a href="/clients/regulations/">Нормативные документы</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12  col-sm-12 ">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="blue block nmrl connect clearfix no-border">
                                <a href="/clients/technological-services/" class="block-text_stroke">Услуги по технологическому присоединению</a>
                                <ul>
                                    <li><a href="/clients/technological-services/individuals/">Физическим лицам</a></li>
                                    <li><a href="/clients/technological-services/corporate-banking/">Юридическим лицам</a></li>
                                    <li><a href="/clients/technological-services/special-cases/">Особые случаи</a></li>
                                </ul>
                                <div class="connect-form col-xs-12 ">
                                    <?$APPLICATION->IncludeComponent(
										"bitrix:form.result.new", 
										".default", 
										array(
											"CACHE_TIME" => "3600",
											"CACHE_TYPE" => "A",
											"CHAIN_ITEM_LINK" => "",
											"CHAIN_ITEM_TEXT" => "",
											"EDIT_URL" => "",
											"IGNORE_CUSTOM_TEMPLATE" => "N",
											"LIST_URL" => "",
											"SEF_MODE" => "N",
											"SUCCESS_URL" => "",
											"USE_EXTENDED_ERRORS" => "N",
											"WEB_FORM_ID" => "2",
											"COMPONENT_TEMPLATE" => ".default",
											"VARIABLE_ALIASES" => array(
												"WEB_FORM_ID" => "WEB_FORM_ID",
												"RESULT_ID" => "RESULT_ID",
											)
										),
										false
									);?>

									<script>
										$(document).ready(function() {
											$(".form-message:not(:empty)").show();
											setInterval('$(".form-message").fadeOut("1000")', "3000");
										});
									</script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="news">
                                <div class="blue-text">Наши новости</div>
                                <a href="/news/" class="more">Все новости</a>
                            </div>
                        </div>
                        <?$APPLICATION->IncludeComponent(
							"bitrix:news.list", 
							"newslist", 
							array(
								"ACTIVE_DATE_FORMAT" => "j F",
								"ADD_SECTIONS_CHAIN" => "Y",
								"AJAX_MODE" => "N",
								"AJAX_OPTION_ADDITIONAL" => "",
								"AJAX_OPTION_HISTORY" => "N",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"CACHE_FILTER" => "N",
								"CACHE_GROUPS" => "Y",
								"CACHE_TIME" => "36000000",
								"CACHE_TYPE" => "A",
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"DISPLAY_BOTTOM_PAGER" => "N",
								"DISPLAY_DATE" => "Y",
								"DISPLAY_NAME" => "Y",
								"DISPLAY_PICTURE" => "Y",
								"DISPLAY_PREVIEW_TEXT" => "Y",
								"DISPLAY_TOP_PAGER" => "N",
								"FIELD_CODE" => array(
									0 => "DATE_ACTIVE_FROM",
									1 => "",
								),
								"FILTER_NAME" => "",
								"HIDE_LINK_WHEN_NO_DETAIL" => "N",
								"IBLOCK_ID" => "1",
								"IBLOCK_TYPE" => "news",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
								"INCLUDE_SUBSECTIONS" => "Y",
								"MESSAGE_404" => "",
								"NEWS_COUNT" => "4",
								"PAGER_BASE_LINK_ENABLE" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_TEMPLATE" => ".default",
								"PAGER_TITLE" => "Новости",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"PREVIEW_TRUNCATE_LEN" => "",
								"PROPERTY_CODE" => array(
									0 => "",
									1 => "TAGS",
									2 => "",
								),
								"SET_BROWSER_TITLE" => "Y",
								"SET_LAST_MODIFIED" => "N",
								"SET_META_DESCRIPTION" => "Y",
								"SET_META_KEYWORDS" => "Y",
								"SET_STATUS_404" => "N",
								"SET_TITLE" => "Y",
								"SHOW_404" => "N",
								"SORT_BY1" => "ACTIVE_FROM",
								"SORT_BY2" => "SORT",
								"SORT_ORDER1" => "DESC",
								"SORT_ORDER2" => "ASC",
								"COMPONENT_TEMPLATE" => "newslist"
							),
							false
						);?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-62">
                <div id="owl-partners">
                    <div class="item">
                        <a href="http://www.admhmao.ru/"> <img src="/bitrix/templates/ugcompany/img/part/1.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.berezovo.ru/ "> <img src="/bitrix/templates/ugcompany/img/part/2.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.admbel.ru/"> <img src="/bitrix/templates/ugcompany/img/part/3.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.oktregion.ru/"> <img src="/bitrix/templates/ugcompany/img/part/4.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://hmrn.ru/"> <img src="/bitrix/templates/ugcompany/img/part/5.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.admsr.ru/?PAGEN_1=4"> <img src="/bitrix/templates/ugcompany/img/part/6.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.nvraion.ru/"> <img src="/bitrix/templates/ugcompany/img/part/7.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.admkonda.ru/"> <img src="/bitrix/templates/ugcompany/img/part/8.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://orael.ru/"> <img src="/bitrix/templates/ugcompany/img/part/13.jpg" style="width: 120px;margin-top: -15px;" align=""></a>
                    </div>
                    <div class="item">
                        <a href="http://hmao.fas.gov.ru/"> <img src="/bitrix/templates/ugcompany/img/part/9.jpg" align="middle"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.yutec-hm.ru/">
                            <img src="/bitrix/templates/ugcompany/img/part/10.jpg" align="middle" style="width: 150px;margin-top: 8px;"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.yuresk.ru/"> <img src="/bitrix/templates/ugcompany/img/part/11.jpg" align="middle" style="width: 150px;margin-top: 8px;"></a>
                    </div>
                    <div class="item">
                        <a href="http://www.elprof.info/"> <img src="/bitrix/templates/ugcompany/img/part/12.jpg" align="" style="width: 150px;margin-top: 8px;"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-37 hidden-md-down">
                <div class="mail nmrl blue index">
                    <a href="/pressroom/internet-reception/" class="block-text_stroke">Написать нам</a> Здесь вы можете задать нам интересующие Вас вопросы
                </div>
            </div>
        </div>
    </div>

    <div class="container dd-shabow" style="margin-top: 15px;">
        <div class="row">
            <div class="map-info clearfix">
                <div class="col-md-12 col-lg-3">
                    <div class="map">
                        <div class="blue-text">География расположения объектов</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 offset-md-0   col-xs-8 offset-xs-3">
                    <div class="map-ico">
                        <div class="big_number">7</div>
                        <div class="big_number-text">районов</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4  offset-md-0 col-xs-8  offset-xs-3 ">
                    <div class="build-ico">
                        <div class="big_number">25</div>
                        <div class="big_number-text">населенных<br> пунктов</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 offset-md-0  col-xs-8  offset-xs-3 ">
                    <div class="lp-ico">
                        <div class="big_number">80

                        </div>
                        <div class="big_number-text">работающих<br>обьектов</div>
                    </div>
                </div>
            </div>
        </div>
		<?
		// включаемая область - карта
		$APPLICATION->IncludeFile("/geography.php", Array(), Array(
			"MODE" => "php", // будет редактировать в веб-редакторе
			"NAME" => "География расположения объектов", // текст всплывающей подсказки на иконке
			));
		?>		

1
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
