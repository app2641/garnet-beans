<?php


namespace ${app}\Model\Query;

use ${app}\Container,
    ${app}\Factory\ModelFactory;
use Garnet\Model\Query\AbstractQuery,
    Garnet\Model\AbstractModel,
    Garnet\Utility\Database\Helper;

class ${query}Query extends AbstractQuery
{

    /**
     * @var Garnet\Utility\Database\Database
     **/
    private $db;


    /**
     * @var ${app}\Model\Query\${query}Column
     **/
    private $column;



    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        $container = new Container(new ModelFactory);
        $this->column = $container->get('${query}Column');
    }



    /**
     * レコードの挿入
     *
     * @param \stdClass $params
     * @return \stdClass
     **/
    public final function insert (\stdClass $params)
    {
        try {
            $sql = '';
            $bind = array();
            $this->db->state($sql, $bind);

            $result = $this->fetchById($this->db->lastInsertId());
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * レコードの更新
     *
     * @param Garnet\Model\AbstractModel
     * @return void
     **/
    public final function update (AbstractModel $model)
    {
        try {
            $record = $model->getRecord();
            foreach ($record as $key => $val) {
                if (! in_array($key, $this->getColumn())) {
                    throw new \Exception('invalid field!');
                }
            }

            $sql = '';
            $this->db->state($sql, $record);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * レコードの削除
     *
     * @param Garnet\Model\AbstractModel
     * @return void
     **/
    public final function delete (AbstractModel $model)
    {
        try {
            $sql = '';
            $this->db->state($sql, $model->get('id'));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 指定idのレコードを取得する
     *
     * @param int $id  プライマリキー
     * @return \stdClass
     **/
    public final function fetchById ($id)
    {
        try {
            $sql = '';
            $result = $this->db->state($sql, $id)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }
}
