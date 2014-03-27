<?php


namespace Garnet\Model\Column;

abstract class AbstractColumn
{

    /**
     * カラム名の配列
     *
     * @var Array
     **/
    protected $column;



    /**
     * カラム配列を取得する
     *
     * @return Array
     **/
    public function getColumn ()
    {
        return $this->column;
    }
}
