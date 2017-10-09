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
$id =[];
$type_name = CUserFieldEnum::GetList([], ["USER_FIELD_NAME" => 'UF_TYPE_GET_DOCUMENT', "XML_ID" => "post" ])->GetNext();
$id = $type_name['ID'];

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
        $html .= "<div class='profile'>                    
                                <div class='profile-left'>
                                    {$control->label()}
                                    {$required}
                                    </div>
                                     <div class='profile-right'>
                                    {$control->render()}
                                     </div>
                                    <br><small style='color:red''>
                                    {$control->error()}</small>
                                    </div>";
        break;

        case $control instanceof \UW\Form\Select:

                    $html .=  $control->render();
            break;


        case $control instanceof \UW\Form\File:
            $html .= "
                            {$control->label()}
                            {$required}
                          <div class='profile'>
                                {$control->render()}
                                <small style='color:red''>
                                {$control->error()}</small>
                            </div>";
            break;
        case $control instanceof \UW\Form\Date:
            $html .= "<div class='profile'>
                        <div class='profile-left'>
                            {$control->label()}
                            {$required}
                            </div>
                       
                            {$control->render()}

                            <br><small style='color:red''>
                            {$control->error()}</small>
                            </div>";
            break;
        case $control instanceof \UW\Form\CheckBox:
            $html .= "<div class='profile-check'>
                        {$control->render()}{$required}
                        <br>
                        <small style='color:red''>{$control->error()}</small>
                        </div>";
            break;
        case $control instanceof \UW\Form\Radio:
            $html .= " <br><div class='profile'>
                        <div class='profile-left'>
                          {$control->label()}:
                          {$required}
                          </div>
                         <div class='profile-right'>
                          {$control->render()}
                          </div>
                          <br><small style='color:red''>
                          {$control->error()}</small>
                      </div>";
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
<form class="profile__form" action="<?= POST_FORM_ACTION_URI ?>" method="POST">
    <fieldset>
        <legend><? echo $step->label ?></legend>
        <input type="hidden" name="currentStep" value="<? echo $step->id ?>">
        <? echo $html ?><br>
        <div class="buttons">

            <?
            if (!empty($prevStepCode)) {
                ?><a class="button" href="<?= $APPLICATION->GetCurPageParam('prevStep=' . $prevStepCode, ['prevStep']) ?>"><? echo $prevText ?></a><?
            }
            ?>

            <button class="button" type="submit" name="saveDraft" value="saveDraft">Сохранить черновик</button>
            <button class="button go" type="submit" name="nextStep" value="<? echo $nextStepCode ?>"><? echo $nextText ?></button>
        </div>
    </fieldset>
    <script>

      $('[name="type_get_document"]').off().on('change', function () {
        if (this.value === '<?echo $id?>') {
          $('#office').hide()
          return
        }
        $('#office').show()
      })
    </script>
</form>
