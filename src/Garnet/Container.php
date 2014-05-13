<?php


namespace Garnet;

use Garnet\Factory\AbstractFactory;

class Container
{
    protected
        $factory;


    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct (AbstractFactory $factory)
    {
        $this->factory = $factory;
    }



    /**
     * ファクトリからクラスを呼び出す
     *
     * @param  string $class_name  呼び出したいクラス名
     * @return object
     **/
    public function get ($class_name)
    {
        return $this->factory->get($name);
    }
}
