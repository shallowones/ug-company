<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) 
{
	die();
}
/** @var array $arResult */
?>

<?if(count($arResult['ITEMS']) > 0) {?>
    <a name="messages"></a>
    <div class="request_text">
        <span><h3>Сообщения:</h3></span>
    </div>


    <?foreach($arResult['ITEMS'] as $arItem) {?>

        <div class="request_who_files" id="">
            <div class="request_who_files_title">
                <div class="request_who_files_name"><?=$arItem['title_answer']?></div>
                <div class="request_who_files_date">
                    <span class="time_text">
                        <?
                        /** @var \Bitrix\Main\Type\DateTime $date */
                        $date = $arItem['UF_DATE_INSERT'];
                            echo $arItem['DATE_DISPLAY'];
                        ?>
                    </span>
                </div>
            </div>
            <div class="request_who_files_text" id="block_msg_<?=$arItem['ID']?>">
                <p><?=$arItem['UF_MESSAGE']?></p>
            </div>
            <? /*
            <div>
                <a class="download_link" href="#">Скачать одним архивом</a>
            </div>
            */ ?>
        </div>
        <? /*
        <div class="message__i">
            <div class="message__from">
                <?=$arItem['title_answer']?>
                <span class="glyphicon glyphicon-time" style="margin-left: 20px;"></span> <?=$arItem['DATE_DISPLAY']?>
                <?if($arResult['SHOW_ACTION']) {?>
                    <a href="#" style="margin-left: 20px;" class="edit_msg" data-id="<?=$arItem['ID']?>"><small>Править</small></a>
                    <a href="<?=$arResult['CurDir']?>?delete=Y&msg_id=<?=$arItem['ID']?>"><small>Удалить</small></a>
                <?}?>
            </div>
            <div class="well well-sm" id="block_msg_<?=$arItem['ID']?>">
                <p><?=$arItem['UF_MESSAGE']?></p>

                <? foreach($arItem['UF_FILES_DISPLAY'] as $arFile): ?>
                    <div class="detail-attachment__i">
                        <span class="glyphicon glyphicon-paperclip detail-attachment__icon"></span>
                        <a href="<?=$arFile['SRC']?>"><?=$arFile['NAME_DISPLAY']?></a>&nbsp;&nbsp;
                        <a class="glyphicon glyphicon-trash delete_file" href="?delete_file_msg=Y&file_id=<?=$arFile['ID']?>&msg_id=<?=$arItem['ID']?>"></a>
                        <br>
                        <small class="text-muted"><?=$arFile['EXT_DISPLAY']?>, <?=$arFile['SIZE_DISPLAY']?> Кб</small>
                    </div>
                <? endforeach; ?>

            </div>
        </div>
         */ ?>

    <?}?>

    <?=$arResult["NAV_STRING"]?>

<?}?>
