<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 19.01.2017
 * Time: 19:02
 */

namespace UW;




class Logger extends Singleton
{
    /**
     * @var \Logger
     */
    private $logger;
    /**
     * @var string
     */
    private $traceKey;

    protected function __construct()
    {
        //TODO тестирование конфигурации
        \Logger::configure(__DIR__ . '/../../../bitrix/logger.xml');
        $this->logger = \Logger::getRootLogger();
    }

    /**
     * @return Logger
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return \Logger|\LoggerRoot
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Запускает трассировку сценария
     *
     * В сессию сеанса устанавливается уникальный ключ, который затем считывается
     * логгером и подставляется в логируемые строки.
     * Трассировка будет продолжаться до тех пора, пока не будет вызван метод Logger::endTrace();
     */
    public static function startTrace()
    {
        global $_SESSION;
        $_SESSION['traceKey'] = uniqid();
    }

    /**
     * Останавливает трассировку сценария
     *
     * @see Logger::endTrace();
     */
    public static function endTrace()
    {
        global $_SESSION;
        unset($_SESSION['traceKey']);
    }

    /**
     * Записать в лог (уровень логирования - TRACE)
     * @param $msg
     */
    public static function trace($msg)
    {
        Logger::getInstance()->getLogger()->trace($msg);
    }

    /**
     * Записать в лог (уровень логирования - DEBUG)
     * @param $msg
     */
    public static function debug($msg)
    {
        Logger::getInstance()->getLogger()->debug($msg);
    }

    /**
     * Записать в лог (уровень логирования - INFO)
     * @param $msg
     */
    public static function info($msg)
    {
        Logger::getInstance()->getLogger()->info($msg);
    }

    /**
     * Записать в лог (уровень логирования - WARN)
     * @param $msg
     * @param null $throwable
     */
    public static function warn($msg, $throwable = null)
    {
        Logger::getInstance()->getLogger()->warn($msg, $throwable);
    }

    /**
     * Записать в лог (уровень логирования - ERROR)
     * @param $msg
     * @param null $throwable
     */
    public static function error($msg, $throwable = null)
    {
        Logger::getInstance()->getLogger()->error($msg, $throwable);
    }

    /**
     * Записать в лог (уровень логирования - FATAL)
     * @param $msg
     * @param null $throwable
     */
    public static function fatal($msg, $throwable = null)
    {
        Logger::getInstance()->getLogger()->fatal($msg, $throwable);
    }
}