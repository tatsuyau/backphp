<?php
class Request{
	public static function get($key){
		if(!empty($_GET[$key])){
			return $_GET[$key];
		}
		return "";
	}
	public static function post($key){
		if(!empty($_POST[$key])){
			return $_POST[$key];
		}
		return "";
	}
}