<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var array $arResult
 * @var Step $step
 */
global $APPLICATION;

$step = $arResult['step'];
$nextStepCode = $arResult['nextStep'];
$prevStepCode = $arResult['prevStep'];

$controls = $step->controls();

$html = '';
foreach ($controls as $control) {
    /** @var Input $control */
    try {
        $required = $control->required ? ' <span>*</span>' : '';
    } catch (Exception $e) {
        $required = '';
    }

    switch (true) {
        case $control instanceof \UW\Form\Text:
        case $control instanceof \UW\Form\TextArea:
        case $control instanceof \UW\Form\Select:
        case $control instanceof \UW\Form\Date:
        case $control instanceof \UW\Form\File:
            $html .= "{$control->label()}{$required}<br> {$control->render()}<br><small style='color:red''>{$control->error()}</small>";
            break;
        case $control instanceof \UW\Form\CheckBox:
            $html .= "<label>{$control->render()} {$control->label()}{$required}</label><br><small style='color:red''>{$control->error()}</small>";
            break;
        case $control instanceof \UW\Form\Radio:
            $html .= "{$control->label()}:{$required}<br>{$control->render()}<br><small style='color:red''>{$control->error()}</small>";
            break;
    }
}

$prevText = 'Назад';
$nextText = empty($nextStepCode)
    ? 'Сохранить'
    : 'Далее';
$count = 0;
?>
<h2>Подать заявление</h2>
<p>
    <? foreach ($arResult['menuSteps'] as $menuItem): ?>
        <span <? if ($menuItem['current']) echo ' style="font-weight:bold;" '; ?>><?= $menuItem['label'] ?></span>
    <? endforeach; ?>
</p>
<form action="<?= POST_FORM_ACTION_URI ?>" method="POST">
    <fieldset>
        <legend><? echo $step->label ?></legend>
        <input type="hidden" name="currentStep" value="<? echo $step->id ?>">
        <? echo $html ?><br>
        <?
        if (!empty($prevStepCode)) {
            ?><a href="<?= $APPLICATION->GetCurPageParam('prevStep=' . $prevStepCode, ['prevStep']) ?>"><b><? echo $prevText ?></b></a><?
        }
        ?>
        <button type="submit" name="nextStep" value="<? echo $nextStepCode ?>"><? echo $nextText ?></button>
        <br><br>
        <button type="submit" name="saveDraft" value="saveDraft">Сохранить черновик</button>
    </fieldset>
</form>