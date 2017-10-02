<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 26.06.2017
 * Time: 14:08
 */

namespace UW\Form;

/**
 * Class CheckBox
 * @package UW\Form
 */
class CheckBox extends Input
{
    const SESSION_KEY = 'CheckBoxInput';

    public function __construct($id, array $attributes = [], $label = '')
    {
        $this->label = $label;
        parent::__construct($id, $attributes);
    }

    public function render()
    {
        $data = '';

        foreach ($this->attributes as $key => $value) {
            if($key == 'value') continue;
            if(is_bool($value)) {
                $data .= ' ' . $key;
            } else {
                $data .= ' ' . $key . '="' . $value . '"';
            }
        }
        if($this->value == 1) {
            $data .= ' checked ';
        }

        return "<input type='checkbox'  id='{$this->id}' {$data} value='1'/>";
    }

    /**
     * Метод валидирует инпут.
     * Должен переобределяться в потомках, если нужна валидация инпута.
     *
     * @return bool
     */
    public function validate()
    {
        $this->error = '';

        // Если поле обязательно для заполнения
        if(array_key_exists('required', $this->attributes)) {
            if (!strlen($this->value)) {
                $this->error = "Заполните поле {$this->label}";

                return false;
            }
        }

        return true;
    }

    public function save()
    {
        $_SESSION[self::SESSION_KEY][$this->id] = $this->value;
    }

    public function restore()
    {
        $value = (string) $_SESSION[self::SESSION_KEY][$this->id];
        if(strlen(trim($value)) > 0) {
            $this->value = $value;
        }
    }

    public function clear()
    {
        $this->value = '';
        $_SESSION[self::SESSION_KEY][$this->id] = '';
    }
}