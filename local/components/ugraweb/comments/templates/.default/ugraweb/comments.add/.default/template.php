<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 * @global CMain $APPLICATION
 */
global $APPLICATION;
?>
<div class="messages_form">
    <form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">

        <?
        // Вывод ошибок, если есть
        if (count($arResult["ERROR"]) > 0):?>
            <span style="color:red;display: block;margin-bottom: 10px;"><?
            echo implode("<br />", $arResult["ERROR"]);?>
            </span><?
        endif;
        ?>

        <?if($arResult['SEND_MESSAGE_OK']) {?>

        <span style="color: green;display: block;margin-bottom: 10px;font-size: 14px;">
            Ваше сообщение успешно отправлено.
        </span>

        <?}?>
        <h3 class="h3_font2">Ваше сообщение</h3>
        <div class="contacts_form_item">
            <textarea placeholder="Ваше сообщение" rows="10" id="statementMessage" name="UF_MESSAGE"><?=$arResult['UF_MESSAGE']?></textarea>
        </div>
        
        <div class="contacts_form_item">
            <input class="green_btn green_btn-big send_request_message" type="submit" name="send_message" value="Отправить сообщение">
        </div>

        <? /*
        <div class="form-group">
            <label for="statementMessage">Ваше сообщение</label>
            <textarea placeholder="Ваше сообщение" rows="10" id="statementMessage" name="UF_MESSAGE"><?=$arResult['UF_MESSAGE']?></textarea>
        </div>

        <p><span class="text-muted">Прикрепить файлы:</span></p>

        <div class="appeal-files form-group">
            <?$APPLICATION->IncludeComponent("ugraweb:files.add", "", array('POST_FILES'=>$arResult['UF_FILES']), false);?>
        </div>

        <div class="form-group">
            <input class="btn btn-primary" type="submit" name="send_message" value="Отправить сообщение">
        </div>
        */ ?>
        <input type="hidden" name="msg_update" value="N">
        <input type="hidden" name="msg_id" value="N">

    </form>
</div>
