<?php
class Render{
	public static function html($file, $varList){
		foreach($varList as $key => $val){
			$$key = $val;	
		}
		include($file);
	}
	public static function json($varList){
		$json = json_encode($varList);
		echo $json;
	}
	public static function jsonDebug($varList){
		echo "<pre>";
		print_r($varList);
		echo "</pre>";	
	}
}