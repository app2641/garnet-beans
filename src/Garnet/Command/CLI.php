<?php


namespace Garnet\Command;

class CLI extends AbstractCommand
{
    
    /**
     * CLIインスタンス
     *
     * @var CLI
     **/
    private static $instance;



    /**
     * CLIインスタンスを取得する
     *
     * @return CLI
     **/
    public static function getInstance ()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    /**
     * CLIの実行
     *
     * @param Array $argv  引数パラメータ
     * @return void
     **/
    public static function execute ($argv)
    {
        try {
            // 引数のある場合はコマンドの実行
            if (count($argv) > 1) {
                // 引数パラメータ整理
                $params = array();
                for ($i = 2; $i < count($argv); $i++) {
                    $params[] = $argv[$i];
                }

                // 初期化コマンドか判別
                if ('init' === strtolower($argv[1])) {
                    $init = new Init();
                    $init->execute($params);
                } else {
                    $command = ucfirst($argv[1]);
                    $path = GARNET.DS.'Command'.DS.$command.'.php';
                    if (! file_exists($path)) {
                        throw new \Exception($command.'コマンドは見つかりませんでした！');
                    }

                    require_once $path;
                    $class = new $command;
                    $class->execute($params);
                }


                // それ以外はコマンドリストを表示する
            } else {
                self::getInstance()->showCommandList();
            }

        } catch (\Exception $e) {
            self::getInstance()->errorLog($e->getMessage());
        }
    }



    /**
     * コマンドリストを表示する
     *
     * @return void
     **/
    public function showCommandList ()
    {
        if (preg_match('/\${garnetbeans}/', GARNET)) {
            return $this->errorLog('Init コマンドを実行して GarnetBeans を初期化してください');
        }

        $path = ROOT.DS.GARNET.DS.'Command';
        echo PHP_EOL.pack('c',0x1B).'[1m-- '.APP.' commands list --'.pack('c',0x1B).'[0m'.PHP_EOL;

        if ($dh = opendir($path)) {
            while ($command = readdir($dh)) {
                if (! is_dir($path.DS.$command) && preg_match('/\.php/', $command)) {
                    require_once $path.DS.$command;

                    $limit = 30;
                    $class = str_replace('.php', '', $command);
                    $class_str = mb_strlen($class);

                    $help = $class::help();
                    $help = explode(PHP_EOL, $help);
                    $text = '';

                    foreach ($help as $row => $help_message) {
                        $space = '';
                        $class_name = ' ';

                        if ($row == 0) {
                            // 一行目はクラス名を記載する
                            $space_limit = $limit - $class_str;
                            $class_name  = '  '.pack('c',0x1B).'[1;33m'.$class.':';
                        } else {
                            $space_limit = $limit;
                        }

                        for ($i = 0; $i < $space_limit; $i++) {
                            $space .= ' ';
                        }

                        echo $class_name.$space.pack('c',0x1B).'[0m'.$help_message.PHP_EOL;
                    }
                }
            }
            closedir($dh);
        }

        echo PHP_EOL;
    }
}
