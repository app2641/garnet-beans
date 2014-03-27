<?php


namespace Garnet\Data\Config;

abstract class AbstractDatabaseConfig
{

    /**
     * database.ini へのパス
     *
     * @var String
     **/
    protected $database_ini_path;

    

    /**
     * データベース設定のパラメータを取得する
     *
     * @return stdClass
     **/
    public function getParams ()
    {
        $ini = parse_ini_file($this->database_ini_path, true)['default'];
        $config = new \stdClass;
        $config->user     = $ini['user'];
        $config->password = $ini['password'];
        $config->database = $ini['database'];
        $config->host     = $ini['host'];

        return $config;
    }
}

