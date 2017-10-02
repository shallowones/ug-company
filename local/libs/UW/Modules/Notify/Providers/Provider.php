<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 27.03.2017
 * Time: 16:46
 */

namespace UW\Modules\Notify\Providers;


abstract class Provider implements ProviderInterface
{
    /**
     * Код провайдера
     *
     * @var string
     */
    protected $code;
    /**
     * Имя провайдера
     *
     * @var string
     */
    protected $name;
    /**
     * Дата последнего обновления - дата последнего полученного от модуля сообщения.
     * Используется для фильтрации сообщений при каждом запросе к модулю
     *
     * @var \DateTime
     */
    protected $lastQueryDate;
    /**
     * @var ProviderHelper
     */
    protected $helper;
    /**
     * Время начала процесса получения сообщений
     *
     * @var \DateTime
     */
    protected $timeProcessMessaging;

    function __construct()
    {
        $this->helper = new ProviderHelper();
    }

    /**
     * Установить время начала процесса получения сообщений
     *
     * Указанное время запоминается перед началом получения списка сообщений.
     * Допускается веротяность, что этот процесс может затянуться на несколько секунд,
     * и за это время могут быть прощеные некоторые события.
     *
     * После успешного получения и обработки списка сообщений "запомненное" время
     * устанавливается, как время последнего обновления
     *
     * @param \DateTime $timeProcessMessaging
     * @return $this
     */
    public function setTimeProcessMessaging($timeProcessMessaging)
    {
        $this->timeProcessMessaging = $timeProcessMessaging;

        return $this;
    }

    /**
     * Получить имя провайдера
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Получить код провайдера
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Установить время последнего обновления
     *
     * @param \DateTime $lastQueryDate
     *
     * @return $this
     */
    public function setLastQueryDate($lastQueryDate)
    {
        $this->lastQueryDate = $lastQueryDate;

        $this->helper->saveProviderLastQueryDate($this);

        return $this;
    }

    /**
     * Получить время последнего запроса
     *
     * @return \DateTime
     */
    public function getLastQueryDate()
    {
        if(null == $this->lastQueryDate){
            $this->lastQueryDate = $this->helper->getProviderLastQueryDate($this);
        }

        return $this->lastQueryDate;
    }
}