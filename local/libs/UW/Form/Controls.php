<?
namespace UW\Form;

use Bitrix\Main\Type\Dictionary;

/**
 * Class Controls
 *
 * @property Input[] $values
 */
class Controls extends Dictionary
{
    public function __construct(array $items = [])
    {
        $controls = [];

        // нужно проверить чтобы данные пришедшие в конструктор были нужного инстанса
        foreach ($items as $item) {
            $control = !($item instanceof Input)
                ? self::create($item)
                : $item;

            $controls[$control->id()] = $control;
        }

        parent::__construct($controls);
    }

    /**
     * Метод устанавливает новые значения, пришедшие из формы
     *
     * @param array $request
     * @return $this
     */
    public function fill(array $request)
    {
        foreach ($this->values as $control) {
            $control->value = $request[$control->name];
        }

        return $this;
    }

    public function validate()
    {
        $error = false;

        foreach ($this->values as $control) {
            if (!$control->validate()) {
                $error = true;
            }
        }

        return $error;
    }

    public function add(Input $control)
    {
        $this->values[$control->id()] = $control;
    }

    public function filter(array $codes = [])
    {
        $clone = new self();

        if (!empty($codes)) {
            foreach ($codes as $code) {
                if (array_key_exists($code, $this->values)) {
                    $clone->add($this->values[$code]);
                }
            }
        }

        return $clone;
    }

    public function saveValues()
    {
        foreach ($this->values as $input) {
            $input->save();
        }

        return $this;
    }

    public function restoreValues()
    {
        foreach ($this->values as $input) {
            $input->restore();
        }

        return $this;
    }

    public function clearValues()
    {
        foreach ($this->values as $input) {
            $input->clear();
        }
    }

    /**
     * Типа фабрика
     *
     * @param array $data
     * @return Input
     * @throws \Exception
     */
    public static function create(array $data)
    {
        switch ($data['type']) {
            case 'text':
                $skip = ['label', 'type', 'code'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new Text($data['code'], $attrs, $data['label']);
            case 'textarea':
                $skip = ['label', 'type', 'value', 'code'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new TextArea($data['code'], $attrs, $data['label']);

            case 'select':
                $skip = ['label', 'type', 'code', 'options'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new Select($data['code'], $attrs, $data['label'], $data['options']);

            case 'radio':
                $skip = ['label', 'type', 'code', 'options'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new Radio($data['code'], $attrs, $data['label'], $data['options']);

            case 'checkbox':
                $skip = ['label', 'type', 'code'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new CheckBox($data['code'], $attrs, $data['label']);

            case 'date':
                $skip = ['label', 'type', 'code'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }

                return new Date($data['code'], $attrs, $data['label']);

            case 'file':
                $skip = ['label', 'type', 'code'];
                $attrs = [];
                foreach ($data as $key => $value) {
                    if (in_array($key, $skip)) {
                        continue;
                    }

                    $attrs[$key] = $value;
                }
                return new File($data['code'], $attrs, '', $data['label']);
            default:
                throw new \Exception('Неизвестный тип инпута');
        }
    }

    /**
     * Возвращает параметры для конкертных полей из пользовательский свойств хайлоуда
     * в необходимом формате
     *
     * @param array $arControls
     * @param array $arFieldsHL
     * @return array
     */
    public static function prepareFields(array $arControls, array $arFieldsHL)
    {
        return array_map(function ($code) use ($arFieldsHL) {
            $field = $arFieldsHL[$code];

            $values = [];

            $type = 'text';
            $label = $field['EDIT_FORM_LABEL'];
            $name = \ToLower(str_replace('UF_', '', $field['FIELD_NAME']));
            $value = $field['VALUE'];
            $defaultValue = is_array($field['SETTINGS']['DEFAULT_VALUE'])
                ? $field['SETTINGS']['DEFAULT_VALUE']['VALUE']
                : $field['SETTINGS']['DEFAULT_VALUE'];

            switch ($field['USER_TYPE_ID']) {
                case 'string':
                    // Если rows > 1, значит textarea
                    if ($field['SETTINGS']['ROWS'] > 1) {
                        $type = 'textarea';
                    }
                    break;
                case 'date':
                    $type = 'date';
                    break;
                case 'integer':
                    $type = 'number';
                    break;
                case 'enumeration':
                    $type = 'select';

                    if ($field['SETTINGS']['DISPLAY'] === 'CHECKBOX') {
                        $type = 'radio';
                    }

                    $enums = (new \CUserFieldEnum())->GetList([], ['USER_FIELD_ID' => $field['ID']]);
                    while ($enum = $enums->GetNext()) {
                        // значение по умолчанию
                        if ($enum['DEF'] === 'Y') {
                            $defaultValue = $enum['ID'];
                        }

                        // список всех значений
                        $values[$enum['ID']] = [
                            'id' => $enum['ID'],
                            'value' => $enum['VALUE']
                        ];
                    }
                    break;
                case 'boolean':
                    $type = 'checkbox';
                    break;
                case 'file':
                    $type = 'file';
                    break;
            }

            $control = [
                'name' => $name,
                'label' => $label,
                'type' => $type,
                'value' => strlen($value) > 0
                    ? $value
                    : $defaultValue
            ];

            if ($type === 'file' && is_array($value) && count($value) > 0) {
                $control['value'] = $value;
            }

            if (count($values) > 0) {
                $control['options'] = $values;
            }

            // Регулярное выражение для проверки
            // FIXME тут есть вероятность ошибки, если внутри REGEXP будет один из заменяемых символов
            if (!empty($field['SETTINGS']['REGEXP'])) {
                $control['pattern'] = str_replace('#', '', $field['SETTINGS']['REGEXP']);
                $control['pattern'] = str_replace('/', '', $control['pattern']);
                $control['pattern'] = str_replace('u', '', $control['pattern']);
            }

            // Максимальная длина строки
            if (!empty($field['SETTINGS']['MAX_LENGTH'])) {
                $control['maxlength'] = $field['SETTINGS']['MAX_LENGTH'];
            }

            // Обязательное
            if ($field['MANDATORY'] === 'Y') {
                $control['required'] = true;
            }

            // Количество строчек поля ввода
            if ($field['SETTINGS']['ROWS'] > 1) {
                $control['rows'] = $field['SETTINGS']['ROWS'];
            }

            // Множественное или нет (пока только для файлов)
            if ($field['MULTIPLE'] === 'Y' && $type === 'file') {
                $control['multiple'] = true;
            }

            $control['code'] = $field['FIELD_NAME'];

            return $control;
        }, $arControls);
    }
}