<?php


namespace Garnet\Model\Query;

use Garnet\Model\AbstractModel;

abstract class AbstractQuery
{

    /**
     * @var Garnet\Beans\Model\Column\AbstractColumn
     **/
    protected $column;



    /**
     * レコードの挿入
     *
     * @param \stdClass $params  パラメータ
     **/
    abstract public function insert (\stdClass $params);


    /**
     * レコードの更新
     *
     * @param AbstractModel $model  モデルクラス
     **/
    abstract public function update (AbstractModel $model);


    /**
     * レコードの削除
     *
     * @param AbstractModel $model  モデルクラス
     **/
    abstract public function delete (AbstractModel $model);


    /**
     * 指定idのレコードを取得する
     *
     * @param int $id  レコードのプライマリキー
     **/
    abstract public function fetchById ($id);



    /**
     * カラムの配列を取得する
     *
     * @return Array
     **/
    public final function getColumn ()
    {
        return $this->column->getColumn();
    }
}

