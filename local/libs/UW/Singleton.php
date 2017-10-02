<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 20.01.2017
 * Time: 12:12
 */

namespace UW;


/**
 * Class Singleton
 * @package UW
 */
class Singleton
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    protected static $instances;

    /**
     * Returns the *Singleton* instance of this class.
     */
    public static function getInstance()
    {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

}