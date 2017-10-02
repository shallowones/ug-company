<?
//define("NEED_AUTH", true); //доступ только авторизованным
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION, $USER;


$APPLICATION->SetTitle("Личный кабинет");
?>
   <? if($USER->IsAuthorized()):
        LocalRedirect("/lk/statements/");?>
    <?else:?>
        <div id="reset">
                <h1 class="h1">Личный кабинет тех. присоединение</h1>
            <p><b>В личном кабинете Вы можете:</b></p>
                -подать заявку на технологическое присоединение к электрическим станциям АО "ЮграЭнерго";<br>
                -следить за текущим статусом Вашего заявления;<br>
                -получать актуальные уведомления о смене статуса и отслеживать прогресс заявления.<br>
            <div class="buttons">

                <a class="button lk" href="/lk/auth/index.php?login=yes">Авторизоваться</a>
                <p><small><i>или</i></small></p>
                <a class="button lk" href="/lk/auth/index.php?register=yes">Зарегистрироваться</a>
            </div>
        </div>
    <?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>