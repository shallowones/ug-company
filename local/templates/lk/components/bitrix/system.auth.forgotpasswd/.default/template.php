<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
?>

<form class="profile__form" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
    <h1 class="h1"><?=GetMessage("TITLE")?></h1>
    <?=$arParams["AUTH_RESULT"]['MESSAGE'];?>

<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>

    <div class="profile">
            <h2 class="profile-left">
                <?=GetMessage("AUTH_EMAIL")?>
            </h2>
            <div class="profile-right">
                    <input class="profile__text" name="USER_EMAIL" type="email" maxlength="255"
                           placeholder="demo@example.com"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                           required/>
            </div>
        <!--  <?if($arResult["USE_CAPTCHA"]):?>
          <tr>
                <td></td>
                <td>
                    <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                </td>
            </tr>
            <tr>
                <td><?echo GetMessage("system_auth_captcha")?></td>
                <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
            </tr>
        <?endif?>-->
    </div>
    <div class="buttons">
        <a class="button" href="<?=$arResult["AUTH_AUTH_URL"]?>">Назад</a>
        <button type="submit" class="button go"><?=GetMessage("AUTH_SEND")?></button>
    </div>

</form>
<script type="text/javascript">

document.bform.USER_EMAIL.focus();
</script>
