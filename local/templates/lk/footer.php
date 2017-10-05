        <?if ($USER->IsAuthorized()):?>
            <?if(!$state):?>
                </div>
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
                <?else:?>
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
            <?endif;?>
        <?endif;?>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="footer-flex wrapper">
        <div class="footer__item">
            <div class="footer__item-title">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/title.php"
                    )
                );?>
            </div>
            <div class="footer__item-desc">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/footer-desc.php"
                    )
                );?>
            </div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">Адрес</div>
            <div class="footer__item-desc">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/footer-address.php"
                    )
                );?>
            </div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">Телефон</div>
            <div class="footer__item-desc">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/footer-number.php"
                    )
                );?>
            </div>
        </div>
        <div class="footer__item">
            <div class="footer__item-title">E-mail</div>
            <div class="footer__item-desc">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/footer-email.php"
                    )
                );?>
            </div>
        </div>
    </div>
</footer>
</div>
</body>
</html>