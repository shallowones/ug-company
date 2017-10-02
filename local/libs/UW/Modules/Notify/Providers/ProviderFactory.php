<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 27.03.2017
 * Time: 12:10
 */

namespace UW\Modules\Notify\Providers;


class ProviderFactory
{
    /**
     * Построить всех провайдеров
     *
     * @return ProviderInterface[]
     */
    public function buildAll()
    {
        $ns = 'UW\Modules\Notify\Providers';
        $result = [];

        foreach (scandir(__DIR__) as $file){
            $fileInfo = pathinfo(__DIR__ . '/' . $file);


            if('php' == $fileInfo['extension']){
                $class = $ns . '\\' . $fileInfo['filename'];
                $reflection = new \ReflectionClass($class);

                if($reflection->isInstantiable() && $reflection->implementsInterface($ns . '\ProviderInterface')){
                    $result[] = new $class();
                }
            }
        }

        return $result;
    }

    /**
     * Построить провайдер по его коду
     *
     * @param $providerCode
     * @return ProviderInterface
     */
    public function buildByCode($providerCode)
    {
        $item = current(array_filter(
            $this->buildAll(),
            function (ProviderInterface $provider) use ($providerCode) {
                return ($provider->getCode() == $providerCode);
            }
        ));

        if(!$item){
            //todo error
        }

        return $item;
    }
}