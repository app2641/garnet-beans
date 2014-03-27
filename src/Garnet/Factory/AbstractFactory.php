<?php


namespace Garnet\Factory;

abstract class AbstractFactory
{

    /**
     * 指定クラス名のインスタンスを返す
     *
     * @param String $class name
     * return object
     */
    public final function get ($class)
    {
        return $this->{'build'.$class}();
    }
}
