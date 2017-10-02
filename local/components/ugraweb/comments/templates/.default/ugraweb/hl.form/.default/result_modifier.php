<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * Возвращает данные из профиля и реквизитов организации
 * @param $orgID
 * @param $hlCodeRequisites
 * @return array
 */
function uwGetDataDefaultValues($orgID, $hlCodeRequisites)
{
    $profile = (array)\CUser::GetList(
        $by1 = 'id',
        $order1 = 'asc',
        ['ID' => (new \CUser())->GetID()],
        ['SELECT' => ['UF_*']]
    )->Fetch();

    $orgID = intval($orgID);

    $requisites = [];
    if ($orgID > 0) {
        $hlRequisites = HL\Base::initByCode($hlCodeRequisites);
        $rsRequisites = $hlRequisites::getList([
            'filter' => ['ID' => $orgID]
        ]);
        if ($arRequisites = $rsRequisites->fetch()) {
            foreach ($arRequisites as $code => $value) {
                $requisites[$code . '__REQUISITES'] = $value;
            }
        }
    }

    return array_merge($profile, $requisites);
}

/**
 * Замена по карте на реальные значения
 * @param $value
 * @param $dataDefaultValues
 * @return mixed
 */
function uwReplaceMapValue($value, $dataDefaultValues)
{
    if (strpos($value, '#') !== false) {
        $anchorsFields = explode('#', $value);
        foreach ($anchorsFields as $key => $fieldCode) {
            if (strlen(trim($fieldCode)) > 0) {
                if (array_key_exists($fieldCode, $dataDefaultValues)) {
                    $value = str_replace(
                        '#' . $fieldCode . '#',
                        $dataDefaultValues[$fieldCode],
                        $value
                    );
                }
            }
        }
    }

    return $value;
}

$dataDefaultValues = uwGetDataDefaultValues($arParams['ORGANIZATION_ID'], $arParams['HL_CODE_REQUISITES']);
/** @var UW\Form\Step $step */
$step = $arResult['step'];
/** @var \UW\Form\Input */
$controls = $step->controls();
foreach ($controls as $control) {
    $control->value = uwReplaceMapValue($control->value, $dataDefaultValues);
}