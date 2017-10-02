<?
namespace UW;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use UW\Acl\IConfiguration;

class Config extends \Configula\Config implements IConfiguration
{
    private $environment = [
        'dev' => 'development',
        'prod' => 'production'
    ];

    public function __construct($path = null, array $defaults = [])
    {
        $env = Configuration::getInstance()->get('environment');
        $folder = array_key_exists($env, $this->environment)
            ? $this->environment[$env]
            : $this->environment['prod'];
        $path = Application::getDocumentRoot() . "/local/php_interface/config/{$folder}/";

        parent::__construct($path, $defaults);
    }

    /**
     * Получить знчение
     * @param string $key Ключ
     * @param null $defaultValue Значение по у молчанию, если ключ не найден
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        return $this->getItem($key, $defaultValue);
    }

    /**
     * Установить значение
     * @param string $key Ключ
     * @param string $value Значение
     * @return mixed
     */
    public function set($key, $value)
    {
        return false;
    }
}