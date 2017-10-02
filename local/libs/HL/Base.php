<?
namespace HL;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\UserFieldTable;

class Base
{
    /**
     * Получить занчения множественного свойства HL-блкоа
     *
     * @param $arHL
     * @param $propCode
     * @return array
     */
    public static function getEnumPropValues($arHL, $propCode)
    {
        $userTypeEntity = new \CUserTypeEntity();

        $arUF = $userTypeEntity->GetList(
            [],
            ['ENTITY_ID' => 'HLBLOCK_'.$arHL['ID'], 'FIELD_NAME' => $propCode]
        )->Fetch();

        $arUserField = $userTypeEntity->GetByID($arUF['ID']);

        $arValues = array();
        $rsEnum = (new \CUserFieldEnum())->GetList(array(), array('USER_FIELD_ID' => $arUserField['ID']));
        while ($arEnum = $rsEnum->Fetch()) {
            $arValues[] = $arEnum;
        }

        return $arValues;
    }

    public static function initByCode($code)
    {
        $row = HighloadBlockTable::getRow([
            'filter' => [
                '=NAME' => $code
            ]
        ]);
        if ($row === null) {
            throw new \Exception("Хайлоада с кодом {$code} не существует");
        }

        return HighloadBlockTable::compileEntity($row)->getDataClass();
    }

    /**
     * Возвращает пользовательские поля хайлоуда с параметрами
     *
     * @deprecated
     * @param $NameHL
     * @return array|mixed
     */
    public static function getFieldsHL($NameHL)
    {
        return self::getFields($NameHL);
    }

    /**
     * Возвращает пользовательские поля хайлоада с параметрами
     *
     * @param string $name символьный код хайлоада
     * @param array $codes=[] массив символьных кодов пользовательских полей, если пустой возвращаются все поля
     * @return array
     */
    public static function getFields($name, array $codes = [])
    {
        $fields = [];
        $hl = HighloadBlockTable::getRow([
            'filter' => [
                '=NAME' => $name
            ]
        ]);

        $params = [
            'select' => [
                '*',
                'EDIT_FORM_LABEL' => 'LANG.EDIT_FORM_LABEL',
                'LIST_COLUMN_LABEL' => 'LANG.LIST_COLUMN_LABEL',
                'LIST_FILTER_LABEL' => 'LANG.LIST_FILTER_LABEL',
                'ERROR_MESSAGE' => 'LANG.ERROR_MESSAGE',
                'HELP_MESSAGE' => 'LANG.HELP_MESSAGE'
            ],
            'filter' => [
                'ENTITY_ID' => "HLBLOCK_{$hl['ID']}",
                'LANG.LANGUAGE_ID' => LANGUAGE_ID
            ],
            'runtime' => [
                new ReferenceField(
                    'LANG',
                    'UW\DB\FieldLangTable',
                    ['=this.ID' => 'ref.USER_FIELD_ID'],
                    ['join_type' => 'left']
                )
            ]
        ];

        if (!empty($codes)) {
            $params['filter'] = array_merge($params['filter'], [
                'FIELD_NAME' => $codes,
            ]);
        }

        $rows = UserFieldTable::getList($params);
        while ($row = $rows->fetch()) {
            $fields[$row['FIELD_NAME']] = array_merge($row, [
                'VALUE' => ''
            ]);
        }

        return $fields;
    }
}

