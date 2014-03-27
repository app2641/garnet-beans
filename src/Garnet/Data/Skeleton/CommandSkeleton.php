<?php


use Garnet\Command\AbstractCommand,
    Garnet\Command\CommandInterface;

class ${command} extends AbstractCommand implements CommandInterface
{

    /**
     * コマンドの実行
     *
     * @param Array $params  引数パラメータ
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * ヘルプメッセージを返す
     *
     * @return String
     **/
    public static function help ()
    {
        return '';
    }
}
