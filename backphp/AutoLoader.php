<?php
class AutoLoader{
	static $directories = [];
	public static function addDirectory($dir){
		array_push(self::$directories, $dir);
	}
	public static function load(){
		spl_autoload_register(function($class_name){
			foreach(self::$directories as $dir){
				$class_file = $dir . "/" . $class_name . ".php";
				if(file_exists($class_file)){
					require_once $class_file;
					break;
				}
			}
		});
	}
}