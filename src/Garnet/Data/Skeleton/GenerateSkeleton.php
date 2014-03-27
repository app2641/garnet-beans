<?php


use Garnet\Command\AbstractCommand,
    Garnet\Command\CommandInterface;

class Generate extends AbstractCommand implements CommandInterface
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
            // パラメータバリデート
            $this->_validateParameters($params);

            // スケルトンをコピーする
            $cmd = GARNET.DS.'Command';
            $params[0] = ucfirst($params[0]);
            $file_name = $params[0].'.php';
            $file_path = $cmd.DS.$file_name;

            // クエリ
            $data = file_get_contents(ROOT.DS.$cmd.DS.'Skeleton'.DS.'CommandSkeleton.php');
            $data = str_replace('${command}', $params[0], $data);
            file_put_contents($cmd.DS.$params[0].'.php', $data);

            $this->log($params[0].'コマンドを作成しました！', 'success');
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * パラメータのバリデート
     *
     * @param Array $params  パラメータ
     * @return void
     **/
    private function _validateParameters ($params)
    {
        // 引数の有無
        if (! isset($params[0])) {
            throw new \Exception('生成するコマンド名を指定してください');
        }

        // 既に存在するかどうか
        $params[0] = ucfirst($params[0]);
        if (file_exists(GARNET.DS.'Command'.DS.$params[0].'.php')) {
            throw new \Exception($params[0].' コマンドは既に存在しています');
        }
    }



    /**
     * ヘルプメッセージを返す
     *
     * @return String
     **/
    public static function help ()
    {
        return '引数に指定した新規コマンドを生成する';
    }
}
