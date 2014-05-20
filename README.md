GarnetBeans
=============
GarnetBeans は俺々モデルクラスだ。

### Requires
GarnetBeans では、内部で EmeraldBeans と SapphireBeans が動作している。  
[EmeraldBeans](https://github.com/app2641/emerald-beans)  
[SapphireBeans](https://github.com/app2641/sapphire-beans)



### 定数の設定と準備
GarnetBeans を動かすには LIB と APP という定数が必要になる。  
LIB はディレクトリのパスを、 APP にはアプリケーション名を指定する。


```
<?php
define('LIB', '/Users/hoge/Desktop/app/library');
define('APP', 'App');
```

LIB に指定したディレクトリの直下には APP で指定したアプリケーション名の空ディレクトリを生成する。

```
$ mkdir ~/Desktop/app/library/App
```



### モデルクラスの生成
モデルクラスは EmeraldBeans のコマンドクラスを使用して生成する。

```
$ touch run
$ chmod +x run
```

```
<?php
use Emerald\CLI;

$cli = CLI::getInstance();
$cli->execute($argv);
```

GenerateModel コマンドを実行すると引数に与えたモデルクラスが生成される。

```
$ ./run GenerateModel User
generated UserModel!
```

### モデルクラスの使い方
モデルファクトリクラスを介してモデルクラスは取得する。
下記のように使う。

```
<?php
use Garnet\Container,
	App\Factory\ModelFactory;
	
$container  = new Container(new ModelFactory);
$user_model = $container->get('UserModel');

$user_model->fetchById(1);
echo $user_model->get('name');
```