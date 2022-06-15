<?php
class ArrayUtil{
	public static function sortArray($array,$sort_key,$sort_type=SORT_ASC){
		$sort_array	= array();
		foreach($array as $val){
			$sort_array[]	= $val[$sort_key];
		}
		array_multisort($sort_array,$sort_type,$array);
		return $array;
	}
	public static function uniqArray($array, $uniq_key){
		$resultList = [];
		foreach($array as $val){
			$uniq_value = $val[$uniq_key];
			foreach($resultList as $res){
				if($res[$uniq_key] == $uniq_value){
					continue 2;
				}
			}
			array_push($resultList, $val);
		}
		return $resultList;
	}

	public static function joinArray($base_array,$join_array,$join_key){
		foreach($base_array as $key => $val){
			if(!isset($val[$join_key])) continue;
			foreach($join_array as $join_val){
				if(!isset($join_val[$join_key])) continue;
				if($join_val[$join_key] == $val[$join_key]){
					foreach($join_val as $target_key => $target_val){
						$val[$target_key]	= $target_val;
					}
					break;
				}
			}
			$base_array[$key]	= $val;
		}
		return $base_array;
	}

	public static function limitArray($array,$from=0,$to=10){
		$limit_array	= array();
		$index		= 0;
		foreach($array as $val){
			if($index < $from)	continue;
			if($index > $to)	break;
			$limit_array[]	= $val;
			$index++;
		}
		return $limit_array;
	}

}