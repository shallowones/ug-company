<?

namespace UW\Form;

class File extends Input
{
    const SESSION_KEY = 'FileInput';

    protected $attributes = [
        'browse_button' => '',
        'url' => ''
    ];

    protected $template = '';

    public function __construct($id, array $attrs = [], $template = '', $label = '')
    {
        $this->label = $label;
        if (!empty($template)) {
            $this->template = $template;
        }

        parent::__construct($id, $attrs);
    }

    /** @return string */
    public function render()
    {
        global $APPLICATION;

        ob_start();

        $APPLICATION->IncludeComponent('ugraweb:file.uploader', $this->template, [
            'INPUT_NAME' => $this->attributes['name'],
            'DEV_MODE' => 'N',
            'MULTIPLE' => array_key_exists('multiple', $this->attributes) ? 'Y' : 'N',
            "VALUE" => $this->attributes['value']
        ]);

        $input = ob_get_contents();
        ob_end_clean();

        return $input;
    }

    /**
     * Метод валидирует инпут.
     * Должен переобределяться в потомках, если нужна валидация инпута.
     *
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    public function save()
    {
        $_SESSION[self::SESSION_KEY][$this->id] = $this->value;
    }

    public function restore()
    {
        $value = $_SESSION[self::SESSION_KEY][$this->id];
        if(intval($value) > 0 || (is_array($value) && count($value) > 0)) {
            $this->value = $value;
        }
    }

    public function clear()
    {
        $this->value = '';
        $_SESSION[self::SESSION_KEY][$this->id] = '';
    }
}