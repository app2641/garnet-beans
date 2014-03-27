<?php


namespace Garnet\Utility\Database;

use Garnet\Data\Config\AbstractDatabaseConfig;
use Garnet\Utility\Registry;
use Garnet\Utility\Database\Database;

class Helper
{

    /**
     * データベース接続を行う
     *
     * @return Database
     **/
    public static function connection (AbstractDatabaseConfig $config)
    {
        // Registryに登録され、コネクションも同じ場合
        if (Registry::getInstance()->ifKeyExists('db')) {
            $db = Registry::get('db');

        } else {
            $params = $config->getParams();

            $dsn = sprintf('mysql:dbname=%s;host=%s', $params->database, $params->host);
            $db  = new Database($dsn, $params->user, $params->password);
            $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            Registry::set('db', $db);
        }

        return $db;
    }
}

