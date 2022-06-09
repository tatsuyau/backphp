<?php
class Router{
	static $callbacks = [];
	public static function set($path, $callback){
		self::$callbacks[$path] = $callback;	
	}
	public static function run($path, $error_callback){
		if(!empty(self::$callbacks[$path])){
			self::$callbacks[$path]();	
		}else{
			$error_callback();	
		}
	}
}