<?php


namespace Garnet\Utility\Database;

class Database extends \PDO
{

    // Database drivers that support SAVEPOINTs.
    protected static $savepointTransactions = array("pgsql", "mysql");

    // The current transaction level.
    protected $transLevel = 0;


    protected function nestable() {
        return in_array($this->getAttribute(\PDO::ATTR_DRIVER_NAME),
                        self::$savepointTransactions);
    }

    public function beginTransaction() {
        if(!$this->nestable() || $this->transLevel == 0) {
            parent::beginTransaction();
        } else {
            $this->exec("SAVEPOINT LEVEL{$this->transLevel}");
        }

        $this->transLevel++;
    }

    public function commit() {
        $this->transLevel--;

        if(!$this->nestable() || $this->transLevel == 0) {
            parent::commit();
        } else {
            $this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
        }
    }

    public function rollBack() {
        $this->transLevel--;

        if(!$this->nestable() || $this->transLevel == 0) {
            parent::rollBack();
        } else {
            $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
        }
    }



    /**
     * SQLの実行
     *
     * @param String $sql  SQLクエリ
     * @param Array $bind  プリペアドステートメントパラメータ
     * @return PDOStatement
     **/
    public function state ($sql, $bind = array())
    {
        // stdclassの場合はArrayにキャスト
        if ($bind instanceof \stdClass) {
            $bind = (array) $bind;
        }

        // String, int の場合はArrayにキャスト
        if (! is_array($bind)) {
            $bind = array($bind);
        }

        // mysql strict mode 対策
        // http://dev.mysql.com/doc/refman/5.1/ja/server-sql-mode.html
        // booleanをintに変更
        foreach($bind as $key => $value) {
            if (is_bool($value) === true) {
                $bind[$key] = (int)$value;
            }
        }

        $stmt = $this->prepare($sql);
        $stmt->execute($bind);

        return $stmt;
    }
}
