<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 22.02.2017
 * Time: 10:15
 */

namespace UW\Modules\Notify;

/**
 * Шаблон данных для уведомления (immutable)
 *
 * Class Template
 * @package UW\Modules\Notify
 */
class Template
{
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $body;

    /**
     * Template constructor.
     * @param $title
     * @param $body
     */
    function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Получить тело
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Получить заголовок
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $body
     * @return Template
     */
    public function setBody($body)
    {
        return new self($this->title, $body);
    }

    /**
     * @param string $title
     * @return Template
     */
    public function setTitle($title)
    {
        return new self($title, $this->body);
    }
}