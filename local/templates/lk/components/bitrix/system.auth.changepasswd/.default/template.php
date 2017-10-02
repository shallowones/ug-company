<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form class="profile__form" method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">

				<h1 class="h1"><?=GetMessage("AUTH_CHANGE_PASSWORD")?></h1>

				<div class="profile">
                    <h2 class="profile-left">
                        <span class="starrequired">*</span>
                        <?=GetMessage("AUTH_LOGIN")?>
                    </h2>
                    <div class="profile-right">
				        <input class="profile__text" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" required />
                    </div>
                </div>

                <div class="profile">
                    <h2 class="profile-left">
                        <span class="starrequired">*</span>
                        <?=GetMessage("AUTH_CHECKWORD")?>
                    </h2>
                    <div class="profile-right">
                        <input class="profile__text" type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" required/>
                    </div>
                </div>

                <div class="profile">
                    <h2 class="profile-left">
                        <span class="starrequired">*</span>
                        <?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>
                    </h2>
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
                    <h2 class="profile-left">
                        <span class="starrequired">*</span>
                        <?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?>
                    </h2>
                    <div class="profile-right">
                        <input class="profile__text" id="password2" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off"
                               required
                               pattern="[0-9a-zA-Z]{6,}"
                               placeholder="Пароль должен быть не менее 6 символов длиной."/>
                    </div>
                </div>

            <?if($arResult["USE_CAPTCHA"]):?>
                        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                    <td><span class="starrequired">*</span><?echo GetMessage("system_auth_captcha")?></td>
                    <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
            <?endif?>
            <div class="buttons">
                <a class="button" href="<?=$arResult["AUTH_AUTH_URL"]?>"">Авторизация</a>
                <input class="button go" type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" />
            </div>
</form>
<script type="text/javascript">
    window.onload = function () {
        document.getElementById("password1").onchange = validatePassword;
        document.getElementById("password2").onchange = validatePassword;
    }
    function validatePassword(){
        var pass2=document.getElementById("password2").value;
        var pass1=document.getElementById("password1").value;
        if(pass1!=pass2)
            document.getElementById("password2").setCustomValidity("Пароли не совпадают");
        else
            document.getElementById("password2").setCustomValidity('');
//empty string means no validation error
    }
</script>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
