<?php


use Garnet\Command\AbstractCommand,
    Garnet\Command\CommandInterface;

class ModelGenerate extends AbstractCommand implements CommandInterface
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
            $cmd   = GARNET.DS.'Command';
            $model = GARNET.DS.'Model';
            $file_name = ucfirst($params[0]).'.php';
            
            // モデル
            $data = file_get_contents($cmd.DS.'Sekeleton'.DS.'ModelSkeleton.php');
            $data = str_replace('${model}', $params[0]);
            $data = str_replace('${app}', APP);
            file_put_contents($model.DS.$params[0].'Model.php', $data);

            // クエリ
            $data = file_get_contents($cmd.DS.'Sekeleton'.DS.'QuerySkeleton.php');
            $data = str_replace('${query}', $params[0]);
            $data = str_replace('${app}', APP);
            file_put_contents($model.DS.'Query'.DS.$params[0].'Query.php', $data);

            // カラム
            $data = file_get_contents($cmd.DS.'Sekeleton'.DS.'ColumnSkeleton.php');
            $data = str_replace('${column}', $params[0]);
            $data = str_replace('${app}', APP);
            file_put_contents($model.DS.'Column'.DS.$params[0].'Column.php', $data);
        
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
            throw new \Exception('生成するモデル名を指定してください');
        }

        // 既に存在するかどうか
        $params[0] = ucfirst($params[0]);
        if (file_exists(GARNET.DS.'Model'.DS.$params[0].'Model.php')) {
            throw new \Exception($params[0].' モデルは既に存在しています');
        }
    }



    /**
     * ヘルプメッセージを返す
     *
     * @return String
     **/
    public static function help ()
    {
        return '引数に指定したモデルクラスを生成する';
    }
}
