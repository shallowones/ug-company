<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var StatementComponent $component */
/** @var $arParams */

\Bitrix\Main\Loader::includeModule('highloadblock');

global $APPLICATION, $USER;

/* Логика по разграничению прав на детальной странице заявления */

$errorText = 'Заявление № ' . $arResult['VARIABLES']['ELEMENT_ID'] . ' не найдено.';

$hl = HL\Base::initByCode($arParams["HL_CODE"]);
$params = [
    'select' => [
        'USER_ID' => 'UF_USER_ID'
    ],
    'filter' => [
        'ID' => $arResult['VARIABLES']['ELEMENT_ID']
    ]
];

$element = $hl::getRow($params);

// если пользователь не модератор и ID юзера в заявлении не совпадает с ID текущего юзера
if ($arResult['IS_MODERATOR'] !== 'Y' && $element['USER_ID'] !== $USER->GetID()) {
    // то доступ закрыт
    ShowError('permission denied');
    return;
} else {
    // иначе пользователь является модератором, и нужно получить для него список полей для отображения
    $groups = $component->getGroupsCurrentUser($element['USER_ID']);

    $groupName = $component->checkAllowUser($groups);
    $component->getGroupControls($groupName);
}

//if (\UW\Acl\Storage::get()->isAllowed('statement', 'edit') === true):
//    $editURL = $arResult["FOLDER"] . str_replace(
//            '#ELEMENT_ID#',
//            $arResult['VARIABLES']['ELEMENT_ID'],
//            $arResult['URL_TEMPLATES']['edit']
//        );
//    ?>
<!--    <p class="h2"><a class="button" href="--><?//= $editURL ?><!--">Редактировать заявление</a></p>-->
<?// endif;
if (\UW\Acl\Storage::get()->isAllowed('statementStatus', 'edit') === true):
    $editStatusURL = $arResult["FOLDER"] . str_replace(
            '#ELEMENT_ID#',
            $arResult['VARIABLES']['ELEMENT_ID'],
            $arResult['URL_TEMPLATES']['status']
        );
    ?>
    <p><a href="<?= $editStatusURL ?>"><b>Изменить статус</b></a></p>
<? endif;

$APPLICATION->IncludeComponent(
    'ugraweb:hl.detail',
    '',
    [
        'FOLDER' => $arResult["FOLDER"],
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        "HL_CODE" => $arParams["HL_CODE"],
        "CONTROLS" => $arResult["HL_FIELDS"],
    ],
    $component
);

//$APPLICATION->IncludeComponent(
//    "ugraweb:comments",
//    ".default",
//    array(
//        "ID" => $arResult['VARIABLES']['ELEMENT_ID'],
//        "COMPONENT_TEMPLATE" => ".default",
//        "HL_CODE" => $arParams['HL_CODE_MESSAGES']
//    ),
//    false
//);

