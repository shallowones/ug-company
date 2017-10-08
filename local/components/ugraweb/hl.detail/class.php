<?
use Bitrix\Main\Loader;
use \HL\Base;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

class HighloadDetailComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['HL_CODE'] = trim($arParams['HL_CODE']);

        return $arParams;
    }

    private function getDetail()
    {
        $hl = HL\Base::initByCode($this->arParams['HL_CODE']);

        $arFilter = [
            'ID' => $this->arParams['ELEMENT_ID'],
        ];

        $rsHlElement = $hl::getList([
            'filter' => $arFilter,
        ]);

        if ($arHlElement = $rsHlElement->fetch()) {
            $arFieldsHL = Base::getFields($this->arParams['HL_CODE'], $this->arParams['CONTROLS']);

            // Заголовок заявления
            /** @var Bitrix\Main\Type\DateTime $date */
            $date = $arHlElement['UF_DATE_INSERT'];
            $this->arResult['DATE_INSERT'] = $date->format('d.m.Y H:i');
            $this->arResult['ID'] = $arHlElement['ID'];
            $arFields = [];
            $obUserFieldEnum = new \CUserFieldEnum;
            foreach ($this->arParams['CONTROLS'] as $code) {

                $value = $arHlElement[$code];
                if (array_key_exists($code, $arFieldsHL)) {
                    $displayValue = $value;
                    $fieldType = $arFieldsHL[$code]['USER_TYPE_ID'];

                    if ($code === 'UF_USER_ID') {
                        $arUser = \CUser::GetByID($value)->Fetch();
                        $displayValue = "{$arUser['LAST_NAME']} {$arUser['NAME']} {$arUser['SECOND_NAME']}";
                    }
                    if ($fieldType === 'enumeration') {
                        $rsFieldEnum = $obUserFieldEnum->GetList([], [
                            'USER_FIELD_ID' => $arFieldsHL[$code]['ID'],
                            'ID' => $value
                        ]);
                        if ($arFieldEnum = $rsFieldEnum->GetNext()) {
                            $displayValue = $arFieldEnum['VALUE'];
                        }
                    }
                    if ($fieldType === 'datetime' && $value instanceof \Bitrix\Main\Type\DateTime) {
                        $date = $value;
                        $displayValue = $date->format('d.m.Y H:i');
                    }
                    if ($fieldType === 'date' && $value instanceof \Bitrix\Main\Type\Date) {
                        $date = $value;
                        $displayValue = $date->format('d.m.Y');
                    }
                    if ($fieldType === 'boolean') {
                        $displayValue = ($value === 1) ? 'Да' : 'Нет';
                    }
                    if ($fieldType === 'file') {
                        if (intval($value) > 0) {
                            $arFile = CFile::GetFileArray($value);
                            $displayValue = [
                                'src' => $arFile['SRC'],
                                'name' => $arFile['ORIGINAL_NAME']
                            ];
                        }
                        if (is_array($value) && count($value) > 0) {
                            $displayValue = [];
                            foreach ($value as $fileID) {
                                $arFile = CFile::GetFileArray($fileID);
                                $displayValue[] = [
                                    'src' => $arFile['SRC'],
                                    'name' => $arFile['ORIGINAL_NAME']
                                ];
                            }
                        }
                    }
                    if ($displayValue === '0') {
                        $displayValue = '';
                    }

                    $arFields[] = [
                        'value' => $displayValue,
                        'title' => $arFieldsHL[$code]['EDIT_FORM_LABEL'],
                        'type' => $fieldType,
                        'multiple' => $arFieldsHL[$code]['MULTIPLE']
                    ];
                }
            }

            $this->arResult['FIELDS'] = $arFields;
        }
    }

    public function executeComponent()
    {
        if (empty($this->arParams['HL_CODE'])) {
            ShowError('Не задан параметр HL_CODE');
            return;
        }

        Loader::includeModule('highloadblock');

        if (intval($this->arParams['ELEMENT_ID']) < 1) {
            ShowError('Не задан параметр ELEMENT_ID');
            return;
        }

        $this->getDetail();

        $this->includeComponentTemplate();
    }
}