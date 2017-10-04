<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 22.06.2017
 * Time: 14:37
 */

namespace UW\Form;


class Radio extends Input
{

    const SESSION_KEY = 'RadioInput';

    protected $valueOptions = '';

    public function __construct($id, array $attributes = [], $label = '', array $valueOptions)
    {
        $this->label = $label;
        $this->valueOptions = $valueOptions;

        parent::__construct($id, $attributes);
    }

    public function render()
    {
        $radio = '';
        foreach ($this->valueOptions as $arOption) {
            $checked = '';
            if($this->value == $arOption['id']) {
                $checked = ' checked ';
            }
            $radio .= <<<RADIO

<input
    class="magic-radio"
    type="radio"
    name="{$this->attributes['name']}"
    id="{$this->id}_{$arOption['id']}"
    value="{$arOption['id']}"
    {$checked}
><label for="{$this->id}_{$arOption['id']}">{$arOption['value']}
</label><br>
RADIO;
        }

        return $radio;
    }

    public function validate()
    {
        $this->error = '';

        // Если поле обязательно для заполнения
        if(array_key_exists('required', $this->attributes)) {
            if (intval($this->value) < 1) {
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

    public function valueOptions()
    {
        return $this->valueOptions;
    }
}