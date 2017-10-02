<?

namespace UW\Form;

/**
 * Базовый класс для любого инпута
 *
 * Class Input
 *
 * @property string name
 * @property string value
 */
abstract class Input
{
    /**
     * атрибуты необходимые для инпута
     *
     * @type array
     */
    protected $attributes = [
        'name' => '',
        'value' => ''
    ];

    protected $id = '';

    protected $error = '';

    protected $label = '';

    public function __construct($id, array $attrs = [])
    {
        $this->id = $id;
        $this->attributes = array_merge($this->attributes, $attrs);
    }

    public function __set($attr, $value)
    {
        if (!array_key_exists($attr, $this->attributes)) {
            throw new \Exception("Свойства с именем {$attr} не существует");
        }

        $this->attributes[$attr] = $value;
    }

    public function __get($attr)
    {
        if (!array_key_exists($attr, $this->attributes)) {
            throw new \Exception("Свойства с именем {$attr} не существует");
        }

        return $this->attributes[$attr];
    }

    public function id()
    {
        return $this->id;
    }

    public function error()
    {
        return $this->error;
    }

    public function label()
    {
        return $this->label;
    }

    abstract public function render();

    /**
     * Метод валидирует инпут.
     * Должен переобределяться в потомках, если нужна валидация инпута.
     *
     * @return bool
     */
    abstract public function validate();

    abstract public function save();

    abstract public function restore();

    abstract public function clear();
}