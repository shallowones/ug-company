<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var array $arResult
 * @var UW\Form\Step $step
 */
$step = $arResult['step'];
$nextStepCode = $arResult['nextStep'];
$prevStepCode = $arResult['prevStep'];

$controls = $step->controls();

$html = '';
foreach ($controls as $control) {
    /** @var UW\Form\Input $control */
    switch (true) {
        case $control instanceof \UW\Form\Text:
        case $control instanceof \UW\Form\TextArea:
        case $control instanceof \UW\Form\Select:
            $html .= "{$control->label()}<br> {$control->render()}<br><small style='color:red''>{$control->error()}</small>";
            break;
    }
}

$prevText = 'Назад';
$nextText = empty($nextStepCode)
    ? 'Сохранить'
    : 'Далее';
?>
<? if (!empty($html)): ?>
    <h3>Редактирование реквизитов</h3>
    <? if ($arResult['REQUISITES_SAVE_SUCCESS']): ?>
        <div style="padding: 20px; border: 2px solid #2e8c2f; color: #2e8c2f; font-size: 18px; width: 400px;">
            Реквизиты сохранены
        </div>
    <? endif; ?>
    <form action="<?= POST_FORM_ACTION_URI ?>" method="POST">
        <input type="hidden" name="currentStep" value="<? echo $step->id ?>">
        <? echo $html ?><br>
        <?
        if (!empty($prevStepCode)) {
            ?>
            <button type="submit" name="prevStep" value="<? echo $prevStepCode ?>"><? echo $prevText ?></button><?
        }
        ?>
        <button type="submit" name="nextStep" value="<? echo $nextStepCode ?>"><? echo $nextText ?></button>
    </form>
<? endif; ?>