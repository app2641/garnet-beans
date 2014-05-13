<?php


namespace Garnet\Model;

use Sapphire\Utility\Registry;

abstract class AbstractModel
{

    /**
     * @var Garnet\Model\Query\AbstrctQuery
     **/
    protected $query;



    /**
     * @var stdClass
     **/
    private $record;



    /**
     * 新規レコード挿入
     *
     * @param stdClass $params  レコードパラメータ
     * @return void
     **/
    public function insert (\stdClass $params)
    {
        $this->record = $this->query->insert($params);
    }



    /**
     * レコード更新
     *
     * @return void
     **/
    public function update ()
    {
        $this->record = $this->query->update($this);
    }



    /**
     * レコードの削除
     *
     * @return void
     **/
    public function delete ()
    {
        $this->query->delete($this);
        $this->record = null;
    }



    /**
     * 指定idのレコードを取得する
     *
     * @param int $id  プライマリキー
     * @return void
     **/
    public function fetchById ($id)
    {
        $record = $this->query->fetchById($id);

        if ($record instanceof \stdClass) {
            $this->setRecord($record);
        } else {
            $this->record = null;
        }
    }



    /**
     * カラムを取得する
     *
     * @return Array
     **/
    public final function getColumn ()
    {
        return $this->query->getColumn();
    }



    /**
     * レコードオブジェクトを返す
     *
     * @return stdClass
     **/
    public final function getRecord ()
    {
        return $this->record;
    }



    /**
     * レコードオブジェクトをセットする
     *
     * @param stdClass $params  パラメータ
     * @return void
     **/
    public final function setRecord ($params)
    {
        foreach ($params as $key => $param) {
            if (! in_array($key, $this->getColumn())) {
                unset($params->{$key});
            }
        }

        $this->record = $params;
    }



    /**
     * 指定カラムのパラメータを内包レコードから取得する
     *
     * @param String $key  カラム名
     * @return String
     **/
    public final function get ($key)
    {
        if (in_array($key, $this->getColumn())) {
            return $this->record->{$key};
        }

        return false;
    }



    /**
     * 内包レコードの指定カラムにパラメータをセットする
     *
     * @param String $key  カラム名
     * @param String $val  パラメータ
     * @return boolean
     **/
    public final function set ($key, $val)
    {
        if (in_array($key, $this->getColumn())) {
            $this->record->{$key} = $val;
            return true;
        }

        return false;
    }
}

