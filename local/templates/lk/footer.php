</div>
    <?if ($USER->IsAuthorized()):?>
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
    <?endif;?>

</div>
</div>
</main>
<footer class="footer">
    <div class="footer-flex wrapper">
        <div class="footer__item">
            <div class="footer__item-title">АО «ЮГРАЭНЕРГО»</div>
            <div class="footer__item-desc">
                © 2017 Акционерное общество "Югорская<br>энергетическая компаниядецентрализованной зоны".<br>
                Все права защищиены. oi
            </div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">Адрес</div>
            <div class="footer__item-desc">
                628011, Россия, Тюменская область,<br>
                Ханты-Мансийский автономный округ -<br>
                Югра, г. Ханты-Мансийск,<br>
                ул. Сосновый бор, 21
            </div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">Телефон</div>
            <div class="footer__item-desc">8 (3467) <span>37-93-30</span></div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">E-mail</div>
            <div class="footer__item-desc"><a href="#">ugk-2006@mail.ru</a></div>
        </div>
    </div>
</footer>
</div>
</body>
</html>