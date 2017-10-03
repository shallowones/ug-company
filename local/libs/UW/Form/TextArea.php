<?
namespace UW\Form;

/**
 * Class TextArea
 * @package UW\Form
 */
class TextArea extends Text
{
    const SESSION_KEY = 'TextareaInput';

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

        return "<textarea class='box' id='{$this->id}' {$data}>{$this->value}</textarea>";
    }
}