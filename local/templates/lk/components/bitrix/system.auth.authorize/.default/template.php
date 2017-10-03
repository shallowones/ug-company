<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

    <h2 class="h2"><?ShowMessage($arParams["~AUTH_RESULT"]);?></h2>
    <h2 class="h2"><?ShowMessage($arResult['ERROR_MESSAGE']);?></h2>
<?if($arResult["AUTH_SERVICES"]):?>
    <h1 class="h1"><?echo GetMessage("AUTH_TITLE")?></h1>
<?endif?>

<h1 class="h1"><?=GetMessage("AUTH_PLEASE_AUTH")?></h1>

<form class="profile__form" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

    <input type="hidden" name="AUTH_FORM" value="Y" />
    <input type="hidden" name="TYPE" value="AUTH" />
    <?if (strlen($arResult["BACKURL"]) > 0):?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
    <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>

        <div class="profile">
                <h2 class="profile-left"><?=GetMessage("AUTH_LOGIN")?></h2>
                <div class="profile-right">
                    <input class="profile__text" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" required/>
                </div>
        </div>
        <div class="profile">
                <h2 class="profile-left"><?=GetMessage("AUTH_PASSWORD")?></h2>
                <div class="profile-right">
                    <input class="profile__text" type="password" id="password-1" name="USER_PASSWORD" maxlength="255" autocomplete="off" required/>
                        <?if($arResult["SECURE_AUTH"]):?>
                                <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                                    <div class="bx-auth-secure-icon"></div>
                                </span>
                                <noscript>
                                <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                                    <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                </span>
                                </noscript>
                                <script type="text/javascript">
                                document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                </script>
                        <?endif?>
                </div>
        </div>

                    <?if($arResult["CAPTCHA_CODE"]):?>
                        <div class="profile">
                            <h2><?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:</h2>
                            <div class="profile-left">
                                    <input class="profile__text" type="text" name="captcha_word" maxlength="50" value="" size="15" />
                            </div>
                            <div class="profile-right">
                                <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                            </div>
                        </div>
                    <?endif;?>
<!--                --><?//if ($arResult["STORE_PASSWORD"] == "Y"):?>
<!--                    <tr>-->
<!--                        <td></td>-->
<!--                        <td class="profile-check"><input class="profile-check__input" type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />-->
<!--                            <label class="profile-check__label" for="USER_REMEMBER">&nbsp;--><?//=GetMessage("AUTH_REMEMBER_ME")?><!--</label></td>-->
<!--                    </tr>-->
<!--                --><?//endif?>

        <table class="profile">
            <tr class="profile">
                     <td class="profile-left"><input class="button go" type="submit" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" /></td>
                <td class="profile-right">
                    <?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
                        <noindex>
                            <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a> /
                        </noindex>
                    <?endif?>

                    <?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
                        <noindex>
                            <a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a>
                        </noindex>
                    <?endif?>
                </td>
            </tr>

        </table>

</form>


<script type="text/javascript">
    $('#password-1').hidePassword(true);
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
	array(
		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
		"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
		"AUTH_URL" => $arResult["AUTH_URL"],
		"POST" => $arResult["POST"],
		"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
		"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
		"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
	),
	$component,
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>
