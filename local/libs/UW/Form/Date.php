<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 26.06.2017
 * Time: 15:32
 */

namespace UW\Form;


class Date extends Input
{
    const SESSION_KEY = 'DateInput';

    public function __construct($id, array $attributes = [], $label = '')
    {
        $this->label = $label;

        parent::__construct($id, $attributes);
    }

    public function render()
    {
        \CJSCore::Init(array('popup', 'date'));

        $data = '';

        foreach ($this->attributes as $key => $value) {
            if(is_bool($value)) {
                $data .= ' ' . $key;
            } else {
                $data .= ' ' . $key . '="' . $value . '"';
            }
        }

        $calendar = <<<CALENDAR
<span 
    class='add-date'
    onclick="BX.calendar({node:this, field:'{$this->id}', form: '', bTime: false, currentTime: '', bHideTime: true});"

></span>
CALENDAR;

        return "<div class='profile-right file'><input class='profile__text' type='text'  id='{$this->id}' {$data}/> {$calendar}</div>";
    }

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

        if (array_key_exists('pattern', $this->attributes)) {
            if (!preg_match('/' . $this->attributes['pattern'] . '/u', $this->value)) {
                $this->error = "Поле {$this->label} имеет неправильный формат";

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