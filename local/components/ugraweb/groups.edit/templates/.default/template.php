<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
    <? if (!$arResult['SELECTED_GROUP']): ?>
        <h2 class="h2">Пожалуйста, выберите необходимую группу:</h2>

        <form action="<? echo POST_FORM_ACTION_URI ?>" method="post">
            <div class="radio-group">
                <? foreach ($arResult['GROUPS_SELECT'] as $arGroup): ?>

                    <input type="radio"
                           name="group-select[]"
                           id="group-select__<? echo $arGroup['ID'] ?>"
                            <?if($arGroup['ID'] == "7"):?>
                                    checked
                            <?endif;?>
                           value="<? echo $arGroup['ID'] ?>">

                    <label for="group-select__<? echo $arGroup['ID'] ?>"><? echo $arGroup['NAME'] ?></label>

                <? endforeach; ?>
            </div>
            <br><br>
            <button class="button go" type="submit" name="select-submit" value="Y">Выбрать</button>
        </form>
    <? endif; ?>
