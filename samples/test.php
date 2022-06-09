<?php
// オートローダー
$backphp_dir = "./../backphp";
require_once $backphp_dir . "/AutoLoader.php";
AutoLoader::addDirectory($backphp_dir);
AutoLoader::load();

// 環境変数
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

// データベースなど。環境に応じたやつ。
if(ENV == "develop"){
	ini_set('display_errors', 1);
	Database::getInstance()->connect("localhost", "dev_tackphp3", "root", "root");
}
if(ENV == "stage"){
	ini_set('display_errors', 1);
	Database::getInstance()->connect("unkohost", "stage_user", "root", "unchi");
}
if(ENV == "product"){
	Database::getInstance()->connect("unkohost", "product_user", "root", "unchi");
}

// APIルーティング
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

// ルーティング実行
Router::run(Request::get("api"));

// データを作る本体
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