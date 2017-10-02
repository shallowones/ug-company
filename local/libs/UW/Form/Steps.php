<?
namespace UW\Form;

use Bitrix\Main\Type\Dictionary;

/**
 * Class Steps
 *
 * @property Step[] $values
 * @method Step current возвращает текущий шаг формы
 */
class Steps extends Dictionary
{
    public function __construct(array $items = [], Controls $controls = null)
    {
        $steps = [];

        foreach ($items as $item) {
            $step = !($item instanceof Step)
                ? new Step($item['id'], $item['label'], $controls->filter($item['controls']))
                : $item;

            $steps[$step->id] = $step;
        }

        parent::__construct($steps);
    }

    public function prev()
    {
        return prev($this->values);
    }

    public function end()
    {
        return end($this->values);
    }

    /**
     * Метод возвращает ключ текущего шага.
     * Если в параметре $key передан существующий ключ, то указатель переместиться на это ключ
     *
     * @param string $key
     * @return mixed
     */
    public function key($key = null)
    {
        // если пришел ключ, и он есть в массиве
        if (array_key_exists($key, $this->values)) {
            // то нужно поставить на него указатель
            $this->rewind();
            while ($key !== $this->key()) {
                $this->next();
            }

            return $key;
        }

        return parent::key();
    }

    /**
     * Метод возвращает ключ следующего шага, или пустой результат указатель находится на последнем шаге
     *
     * @return string
     */
    public function nextStepCode()
    {
        $code = '';

        // установим указатель на следующий элемент массива
        // если следующий элемент массива вернул false
        if ($this->next() === false) {
            // значит это конец массива
            // если не вызвать end(), то next() переведет уазатель на следующий, не существующий, элемент
            $this->end();
        } else {
            // иначе получим его ключ
            $code = (string) $this->key();
            // и установим указатель обратно
            $this->prev();
        }

        return $code;
    }

    /**
     * Метод возвращает ключ предыдущего шага, или пустой результат если указатель находится на первом шаге
     *
     * @return string
     */
    public function prevStepCode()
    {
        $code = '';

        if ($this->prev() === false) {
            $this->rewind();
        } else {
            $code = (string) $this->key();
            $this->next();
        }

        return $code;
    }

    /**
     * Метод добавляет шаг в коллекцию шагов.
     *
     * @param Step $step
     * @return $this
     */
    public function add(Step $step)
    {
        $this->values[$step->id] = $step;

        return $this;
    }
}