<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var array $arResult
 * @var Step $step
 */
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
        case $control instanceof \UW\Form\TextArea:
            $html .= "<div class='profile'>{$control->label()}{$required}</div> {$control->render()}<small style='color:red''>{$control->error()}</small>";
            break;
    }
}

$prevText = 'Назад';
$nextText = empty($nextStepCode)
    ? 'Сохранить'
    : 'Далее';
$count = 0;
?>

<form class="profile__form" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
    <h3 class="h3_font2"><? echo $step->label ?></h3>
    <input type="hidden" name="currentStep" value="<? echo $step->id ?>">
    <? echo $html ?><br>
    <?
    if (!empty($prevStepCode)) {
        ?><a href="?prevStep=<? echo $prevStepCode ?>"><b><? echo $prevText ?></b></a><?
    }
    ?>
    <button class="button go" type="submit" name="nextStep" value="<? echo $nextStepCode ?>"><? echo $nextText ?></button>
</form>
