<?

namespace UW\Form;

/**
 * Class Text
 *
 * Обычный текстовый инпут
 *
 * TODO нарушен SOLID принцип открытости-закрытости, шаблон инпута нельзя изменить не трогая класс
 * возможно следует сделать метод setTemplate
 */
class Text extends Input
{
    const SESSION_KEY = 'TextInput';

    public function __construct($id, array $attributes = [], $label = '')
    {
        $this->label = $label;
        parent::__construct($id, $attributes);
    }

    public function render()
    {
        $attributes = '';
        foreach ($this->attributes as $key => $value) {
            $attributes .= $value === true
                ? " {$key}"
                : " {$key}=\"{$value}\"";
        }
        $attributes = trim($attributes);

        return "<input id=\"{$this->id}\" {$attributes}/>";
    }

    public function validate()
    {
        $this->error = '';

        // Если поле обязательно для заполнения
        if (array_key_exists('required', $this->attributes)) {
            if (!strlen($this->value)) {
                $this->error = "Заполните поле {$this->label}";

                return false;
            }
        }

        if (array_key_exists('maxlength', $this->attributes)) {
            if (strlen(trim($this->value)) > $this->attributes['maxlength']) {
                $this->error =
                    "Количество символов в поле {$this->label} не должно превышать {$this->attributes['maxlength']}";

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