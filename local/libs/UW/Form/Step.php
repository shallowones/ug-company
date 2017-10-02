<?
namespace UW\Form;

/**
 * Class Step
 *
 * @property string $id
 * @property string $label
 */
class Step
{
    protected $attributes = [
        'id' => '',
        'label' => ''
    ];

    /** @type Controls */
    protected $controls;

    public function __construct($id, $label = '', Controls $controls = null)
    {
        if (empty($id)) {
            throw new \Exception('Не передали "id" шага');
        }

        $this->attributes = array_merge($this->attributes, [
            'id' => $id,
            'label' => $label
        ]);

        $this->controls = $controls === null
            ? new Controls()
            : $controls;
    }

    public function __set($attr, $value)
    {
        if (!array_key_exists($attr, $this->attributes)) {
            throw new \Exception("Свойства с именем {$attr} не существует");
        }

        if ($attr === 'id') {
            throw new \Exception('Свойство "id" доступно только для чтения');
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

    public function addControl(Input $control)
    {
        $this->controls->add($control);

        return $this;
    }

    public function controls()
    {
        return $this->controls;
    }
}
