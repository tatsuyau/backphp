# backphpとは？

バックエンドに特化したマイクロフレームワーク。

最大の特徴は、設定ファイルが1つなこと。



# 必要なもの

backphpフォルダのみ。



# 作った経緯

いちいち開発環境なんて作ってられない。

いちいちデプロイ環境なんて作ってられない。

本番環境にFTPでアップして開発とテストとデプロイを済ませたい。

大したことやらないんだから1ファイルでええやろ。

という怠惰で熱い思いから。



#  やらないといけないこと



### 環境に合わせたphpファイルを1つ用意する

以下はdevelop.phpとして用意したものです。

開発が完了したら、develop.phpと全く同じファイルを名前を変えて、stage.php、product.phpとして環境ごとにファイルを用意し、APIのエンドポイントとして指定すれば良いという考え方です。



```develop.php
<?php

// オートローダー。
// backphpフォルダまでのpathを指定します。
// 他にもオートロードしたいフォルダがあるならaddDirectoryしてください。
$backphp_dir = "./../backphp";
require_once $backphp_dir . "/AutoLoader.php";
AutoLoader::addDirectory($backphp_dir);
AutoLoader::load();

// 環境変数。
// ここではphpファイル名で環境を変えています。
// 本番環境でも気軽に開発しちゃいましょう。（セキュリティにはご用心）
switch(basename(__FILE__)){
	case "develop.php";
		define("ENV", "develop");
		break;
	case "stage.php";
		define("ENV", "stage");
		break;
	case "product.php":
		define("ENV", "product");
		break;
}

// データベースは使いますよね？
// 環境によってデータベースコネクターを管理しましょう。
if(ENV == "develop"){
	ini_set('display_errors', 1);
	Database::getInstance()->connect("localhost", "dev_backphp", "user", "passwd");
}
if(ENV == "stage"){
	ini_set('display_errors', 1);
	Database::getInstance()->connect("localhost", "stage_backphp", "user", "passwd");
}
if(ENV == "product"){
	Database::getInstance()->connect("localhost", "product_backphp", "user", "passwd");
}

// APIルーティングをします。
// URLごとに処理を記述しましょう。
Router::set("", function(){
	$response = [
		'message' => "not found",
	];
	Render::json($response);
});
Router::set("userlist", function(){
	$service = new StarShootService();
	$user_list = $service->getUserList();
	$response = [
		'user_list' => $user_list,
	];
	Render::json($response);
});
Router::set("user", function(){
	$service = new StarShootService();
	$user_id = Request::get("user_id");
	$user_data = $service->getUserData($user_id);
	$response = [
		'user_data' => $user_data,
	];
	Render::json($response);
});

// ルーティングを実行します。ルーティング文字列はGETパラメーターで手軽に。
Router::run(Request::get("api"));

// 各ルーティングで重複するような処理は適当なクラスでまとめておきましょう。
class Service{
	private $TestModel;
	public function __construct(){
		$this->TestModel = new Model("tests", Database::getInstance());
	}
	public function getUserList(){
		$list = $this->TestModel->getList();
		return $list;
	}
	public function getUserData($user_id){
		$p = ["id" => $user_id];
		$data = $this->TestModel->getOne($p);
		return $data;
	}
	public function createUserData($user_name){
		$p = ["name" => $user_name];
		return $this->TestModel->insert($p);
	}
}
```

