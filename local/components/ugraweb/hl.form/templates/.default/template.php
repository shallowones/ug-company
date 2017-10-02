<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var array $arResult
 * @var \UW\Form\Step $step
 */
$step = $arResult['step'];
$nextStepCode = $arResult['nextStep'];
$prevStepCode = $arResult['prevStep'];

$controls = $step->controls();

$html = '';
foreach ($controls as $control) {
    /** @var \UW\Form\Input $control */
    switch (true) {
        case $control instanceof \UW\Form\Text:
        case $control instanceof \UW\Form\TextArea:
        case $control instanceof \UW\Form\Select:
        case $control instanceof \UW\Form\Date:
            $html .= "{$control->label()}<br> {$control->render()}<br><small style='color:red''>{$control->error()}</small>";
            break;
        case $control instanceof \UW\Form\CheckBox:
            $html .= "<label>{$control->render()} {$control->label()}</label><br><small style='color:red''>{$control->error()}</small>";
            break;
        case $control instanceof \UW\Form\Radio:
            $html .= "{$control->label()}:<br>{$control->render()}<br><small style='color:red''>{$control->error()}</small>";
            break;
    }
}

$prevText = 'Назад';
$nextText = empty($nextStepCode)
    ? 'Сохранить'
    : 'Далее';
?>
<h2>Подать заявление</h2>
<form action="<?=POST_FORM_ACTION_URI?>" method="POST">
    <fieldset>
        <legend><? echo $step->label ?></legend>
        <input type="hidden" name="currentStep" value="<? echo $step->id ?>">
        <? echo $html ?><br>
        <?
        if (!empty($prevStepCode)) {
            ?><button type="submit" name="prevStep" value="<? echo $prevStepCode ?>"><? echo $prevText ?></button><?
        }
        ?>
        <button type="submit" name="nextStep" value="<? echo $nextStepCode ?>"><? echo $nextText ?></button>
    </fieldset>
</form>