<?
$no_sidebar = true;

include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("404 Not Found");?>


                        <div class="fourz">404</div>
                        <div class="error"> 
							Ошибка. Запрашиваемая страница не найдена!
                            <p>
								Такое иногда случается. Самые вероятные причины - устаревшие или удаленные страницы.<br> 
								Перейдите на <a href="/#">Главную страницу</a> и попробуйте еще раз начать оттуда либо введите запрос через поиск
							</p>
                        </div>
							<form action="/search/" method="get">
                                <input class="search_input col-xs-12 col-md-10" placeholder="Запрос для поиска" type="text" name="q" value="">

                                <input class="search_btn col-xs-4 offset-xs-4 col-md-2 offset-md-0" type="submit" value="Найти">
							</form>
                        <div class="clearfix"></div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>