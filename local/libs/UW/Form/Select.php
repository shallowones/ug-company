<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 22.06.2017
 * Time: 14:37
 */

namespace UW\Form;


class Select extends Input
{

    const SESSION_KEY = 'SelectInput';

    protected $valueOptions = '';

    public function __construct($id, array $attributes = [], $label = '', array $valueOptions)
    {
        $this->label = $label;
        $this->valueOptions = $valueOptions;

        parent::__construct($id, $attributes);
    }

    public function render()
    {
        $data = '';

        foreach ($this->attributes as $key => $value) {

            if(is_bool($value)) {
                $data .= ' ' . $key;
            } else {
                $data .= ' ' . $key . '="' . $value . '"';
            }
        }


        $options = '<option value="">(не выбрано)</option>';
        foreach ($this->valueOptions as $arOption) {
            $selected = '';
            if ($this->value == $arOption['id']) {
                $selected = ' selected ';
            }
            $options .= '<option value="' . $arOption['id'] . '" ' . $selected . '>' . $arOption['value'] . '</option>';
        }
        if ($this->id == 'UF_LIST_OFFICE')
            $id = "id='office'";

        $select = "<div class='profile' {$id}>
                           <div class='profile-left'>
                                {$this->label}
                           </div>
                           <div class='profile-right'>                          
                ";
        return "$select <select class='profile__select' id='{$this->id}' {$data}>{$options}</select></div> <small style='color:red''>{$this->error()}</small></div>";
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