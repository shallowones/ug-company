<?

namespace UW\DB;

use Bitrix\Main\Entity;

/**
 * Class FieldLangTable
 *
 * Fields:
 * <ul>
 * <li> USER_FIELD_ID int mandatory
 * <li> LANGUAGE_ID string(2) mandatory
 * <li> EDIT_FORM_LABEL string(255) optional
 * <li> LIST_COLUMN_LABEL string(255) optional
 * <li> LIST_FILTER_LABEL string(255) optional
 * <li> ERROR_MESSAGE string(255) optional
 * <li> HELP_MESSAGE string(255) optional
 * </ul>
 *
 * @package Bitrix\User
 **/
class FieldLangTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_user_field_lang';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('USER_FIELD_ID', [
                'primary' => true
            ]),
            new Entity\StringField('LANGUAGE_ID', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateLanguageId']
            ]),
            new Entity\StringField('EDIT_FORM_LABEL', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateEditFormLabel']
            ]),
            new Entity\StringField('LIST_COLUMN_LABEL', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateListColumnLabel']
            ]),
            new Entity\StringField('LIST_FILTER_LABEL', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateListFilterLabel']
            ]),
            new Entity\StringField('ERROR_MESSAGE', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateErrorMessage']
            ]),
            new Entity\StringField('HELP_MESSAGE', [
                'primary' => true,
                'validation' => [__CLASS__, 'validateHelpMessage']
            ])
        );
    }

    /**
     * Returns validators for LANGUAGE_ID field.
     *
     * @return array
     */
    public static function validateLanguageId()
    {
        return [
            new Entity\Validator\Length(null, 2)
        ];
    }

    /**
     * Returns validators for EDIT_FORM_LABEL field.
     *
     * @return array
     */
    public static function validateEditFormLabel()
    {
        return [
            new Entity\Validator\Length(null, 255)
        ];
    }

    /**
     * Returns validators for LIST_COLUMN_LABEL field.
     *
     * @return array
     */
    public static function validateListColumnLabel()
    {
        return [
            new Entity\Validator\Length(null, 255)
        ];
    }

    /**
     * Returns validators for LIST_FILTER_LABEL field.
     *
     * @return array
     */
    public static function validateListFilterLabel()
    {
        return [
            new Entity\Validator\Length(null, 255)
        ];
    }

    /**
     * Returns validators for ERROR_MESSAGE field.
     *
     * @return array
     */
    public static function validateErrorMessage()
    {
        return [
            new Entity\Validator\Length(null, 255)
        ];
    }

    /**
     * Returns validators for HELP_MESSAGE field.
     *
     * @return array
     */
    public static function validateHelpMessage()
    {
        return [
            new Entity\Validator\Length(null, 255)
        ];
    }
}