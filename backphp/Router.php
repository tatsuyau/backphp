<?php
class Router{
	static $callbacks = [];
	public static function set($path, $callback){
		self::$callbacks[$path] = $callback;	
	}
	public static function run($path){
		if(!empty(self::$callbacks[$path])){
			self::$callbacks[$path]();	
		}
	}
}