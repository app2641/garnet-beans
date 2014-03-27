<?php


namespace Garnet\Command;

class Init extends AbstractCommand implements CommandInterface
{

    /**
     * 引数パラメータ
     *
     * @var Array
     **/
    private $params;



    /**
     * Application名
     *
     * @var String
     **/
    private $app;



    /**
     * 強制処理フラグ
     *
     * @var boolean
     **/
    private $force = false;



    /**
     * コマンドの実行
     *
     * @param Array $params  引数パラメータ
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            if (! isset($params[0])) {
                throw new \Exception('GarnetBeans展開先のディレクトリを指定してください');
            }

            if (isset($params[1]) && $params[1] === 'force') {
                $this->force = true;
            }

            defined('DS') || define('DS', DIRECTORY_SEPARATOR);
            $this->params = $params;
            $app = explode(DS, preg_replace('/\/$/', '', $params[0]));
            $this->app = ucfirst($app[count($app) - 1]);

            $path = $params[0];
            $path = preg_match('/\/\z/', $path) ? preg_replace('/\/\z/', '', $path): $path;

            // 必要ディレクトリの存在有無チェック
            $this->_checkExistsDirectories($path);

            // 必要ディレクトリの生成
            $this->_makeDirecotries($path);

            // 必要ファイルのコピー
            $this->_copyFiles($path);

            // garnetファイルのパス書き換え
            $this->_setGarnetPath($path);
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * 必要ディレクトリの存在有無をチェックする
     *
     * @param String $path  GarnetBeans展開先
     * @return void
     **/
    private function _checkExistsDirectories ($path)
    {
        // GarnetBeans
        $path = ROOT.DS.$path;
        if (! is_dir($path)) {
            throw new \Exception('指定パスのディレクトリは存在しません');
        }

        // Model
        $model = $path.DS.'Model';
        if (is_dir($model) && !$this->force) {
            throw new \Exception('Modelは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Model/Query
        if (is_dir($model.DS.'Query') && !$this->force) {
            throw new \Exception('Queryは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Model/Column
        if (is_dir($model.DS.'Column') && !$this->force) {
            throw new \Exception('Columnは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Command
        $cmd = $path.DS.'Command';
        if (is_dir($cmd) && !$this->force) {
            throw new \Exception('Commandは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Generateコマンド
        if (file_exists($cmd.DS.'Generate.php') && !$this->force) {
            throw new \Exception('Genrateコマンドは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // ModelGenerateコマンド
        if (file_exists($cmd.DS.'ModelGenerate.php') && !$this->force) {
            throw new \Exception('ModelGenrateコマンドは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Command/Skeleton
        $skeleton = $cmd.DS.'Skeleton';
        if (is_dir($skeleton) && !$this->force) {
            throw new \Exception('Sekeletonは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        }

        // Factory
        $factory = $path.DS.'Factory';
        if (is_dir($factory) && !$this->force) {
            throw new \Exception('Factoryは既に存在しています。第二引数にforceを指定して強制処理出来ます');
        } 
    }



    /**
     * モデルディレクトリを生成する
     *
     * @param String $path  GarnetBeans展開先
     * @return void
     **/
    private function _makeDirecotries ($path)
    {
        $path  = ROOT.DS.$path;
        $model = $path.DS.'Model';
        $cmd   = $path.DS.'Command';
        $skeleton = $cmd.DS.'Skeleton';
        $factory  = $path.DS.'Factory';

        // Model
        if (! is_dir($model)) {
            mkdir($model, 0755);
        }

        // Model/Query
        if (! is_dir($model.DS.'Query')) {
            mkdir($model.DS.'Query', 0755);
        }

        // Model/Column
        if (! is_dir($model.DS.'Column')) {
            mkdir($model.DS.'Column', 0755);
        }

        // Command
        if (! is_dir($cmd)) {
            mkdir($cmd, 0755);
        }

        // Command/Skeleton
        if (! is_dir($cmd.DS.'Skeleton')) {
            mkdir($cmd.DS.'Skeleton', 0755);
        }

        // Factory
        if (! is_dir($factory)) {
            mkdir($factory, 0755);
        }
    }



    /**
     * 必要ファイルをコピーする
     *
     * @return void
     **/
    private function _copyFiles ($path)
    {
        $path  = ROOT.DS.$path;
        $cmd   = $path.DS.'Command';
        $data  = dirname(__FILE__).'/../Data/Skeleton';
        $skeleton = $cmd.DS.'Skeleton';
        $factory  = $path.DS.'Factory';

        // Generateコマンド
        copy($data.DS.'GenerateSkeleton.php', $cmd.DS.'Generate.php');

        // ModelGenerateコマンド
        copy($data.DS.'ModelGenerateSkeleton.php', $cmd.DS.'ModelGenerate.php');

        // CommandSkeleton
        copy($data.DS.'CommandSkeleton.php', $skeleton.DS.'CommandSkeleton.php');

        // Container
        $file = file_get_contents($data.DS.'ContainerSkeleton.php');
        $file = str_replace('${app}', $this->app, $file);
        file_put_contents($path.DS.'Container.php', $file);

        // ModelFactory
        $file = file_get_contents($data.DS.'ModelFactorySkeleton.php');
        $file = str_replace('${app}', $this->app, $file);
        file_put_contents($factory.DS.'ModelFactory.php', $file);
    }



    /**
     * Garnetファイルにパスを書き込む
     *
     * @param String $path  Appへのパス
     * @return void
     **/
    private function _setGarnetPath ($path)
    {
        $garnet = file_get_contents(ROOT.DS.'garnet');
        $garnet = str_replace('${garnetbeans}', DS.$path.DS, $garnet);
        $garnet = str_replace('${app}', $this->app, $garnet);
        file_put_contents(ROOT.DS.'garnet', $garnet);
    }



    /**
     * ヘルプメッセージ
     *
     * @param String $msg  メッセージ
     * @return String
     **/
    public static function help ()
    {
        return 'GarnetBeansの初期化を行う'.PHP_EOL.
            '第一引数にAppへのパスを指定'.PHP_EOL.
            '第二引数にApp名を指定';
    }
}
