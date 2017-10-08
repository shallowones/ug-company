<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<noindex>
<form class="profile__form" method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />

			<h1 class="h1"><?=GetMessage("AUTH_REGISTER")?></h1>

            <div class="profile">
                <div class="profile-left">
                    <?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?><?=GetMessage("AUTH_EMAIL")?>
                </div>
                <div class="profile-right">
                    <input class="profile__text" type="text" id="reg_email" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" required />
                </div>
            </div>


            <div class="profile">
                <div class="profile-left">
                    <span class="starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?>
                </div>
                <div class="profile-right">
                    <input class="profile__text" id="password1" type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off"
                           required
                           pattern="[0-9a-zA-Z]{6,}"
                           placeholder="Пароль должен быть не менее 6 символов длиной."/>
                </div>
            </div>


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

            <div class="profile">
                <div class="profile-left">
                    <span class="starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?>
                </div>
                <div class="profile-right">
                    <input class="profile__text" id="password2" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off"
                           required
                           pattern="[0-9a-zA-Z]{6,}"
                           placeholder="Пароль должен быть не менее 6 символов длиной."/>
                </div>
            </div>

            <div class="profile">
                <div class="profile-left">
                    <?=GetMessage("AUTH_LAST_NAME")?>
                </div>
                <div class="profile-right">
                    <input class="profile__text" type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" />
                </div>
            </div>


            <div class="profile">
                <div class="profile-left">
                    <?=GetMessage("AUTH_NAME")?>
                </div>
                <div class="profile-right">
                    <input class="profile__text" type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" />
                </div>
            </div>


        <?// ********************* User properties ***************************************************?>
        <?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
            <tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
            <?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
            <tr><td><?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;
                ?><?=$arUserField["EDIT_FORM_LABEL"]?>:</td><td>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:system.field.edit",
                        $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                        array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
            <?endforeach;?>
        <?endif;?>
        <?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{?>
		<div class="profile">
			<?=GetMessage("CAPTCHA_REGF_TITLE")?>
		</div>
        <div class="profile">
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
        </div>
        <div class="profile">
            <div class="profile-left">
                <span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:
            </div>
            <div class="profile-right">
                <input class="profile__text" type="text" name="captcha_word" maxlength="50" value="" />
            </div>
        </div>

		<?
	}
	/* CAPTCHA */
	?>
        <div class="buttons">
            <a class="button" href="<?=$arResult["AUTH_AUTH_URL"]?>"">Авторизация</a>
            <input class="button go" type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
        </div>

</form>
</noindex>
<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>
