<?php


namespace ${app};

use Garnet\Factory\AbstractFactory;

class Container
{

    /**
     * @var Garnet\Factory\AbstractFactory
     **/
    private $factory;



    /**
     * コンストラクタ
     *
     * @param Garnet\Factory\AbstractFactory
     * @return void
     **/
    public function __construct (AbstractFactory $factory)
    {
        $this->factory = $factory;
    }



    /**
     * 指定したインスタンスを生成する
     *
     * @param String $class  クラス名
     * @return object
     **/
    public final function get ($class)
    {
        $class = ucwords(strtolower($class));
        return $this->factory->get($class);
    }
}
