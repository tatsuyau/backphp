<?php
class Model{
	private $Database; 
	private $table_name;
	public function __construct($table_name, $Database=null){
		$this->table_name = $table_name;
		$this->Database = $Database;
		if(!$this->Database){
			$this->Database = Database::getInstance();	
		}
	}
	public function getList($p=[], $options=[]){
		$sql = "SELECT * FROM " . $this->table_name . " ";
		$sql .= $this->createConditions($p);
		$sql .= $this->createOptions($options);
		return $this->Database->select($sql, $p);
	}
	public function getOne($p=[], $options=[]){
		$list = $this->getList($p, $options);
		if($list){
			return $list[0];
		}
		return null;
	}
	public function update($c, $u){
		$sql = "UPDATE " . $this->table_name . " ";
		$u['modified'] = date("Y-m-d H:i:s");
		$sql .= $this->createUpdates($u);
		$sql .= $this->createConditions($p);
		$p = array_merge($c, $u);
		return $this->Database->execute($sql, $p);
	}
	public function insert($p){
		$now = date("Y-m-d H:i:s");
		$p["created"] = $now;
		$p["modified"] = $now;
		$sql = $this->createInsert($p);
		return $this->Database->execute($sql, $p);
	}
	public function delete($p){
        $sql = "DELETE FROM " . $this->_getTableName() . " ";
        $sql .= $this->createConditions($p);
        return $this->Database->execute($sql, $p);
	}
    private function createConditions($p){
        $sql = "";
        if (!$p) {
            return $sql;
        }
        $i = 0;
        foreach ($p as $key => $val) {
            if (!$i) {
                $sql .= " WHERE ";
            } else {
                $sql .= " AND ";
            }
            $sql .= $key . "=:" . $key;
            $i++;
        }
        return $sql;
    }
    private function createOptions($options){
    	$sql = " ";
    	if(!empty($options['order'])){
    		$sql .= "ORDER BY " . $options["order"] . " DESC ";
    	}
    	if(!empty($options['offset']) || !empty($options['limit'])){
    		$sql .= "LIMIT " ;
    	}
    	if(!empty($options['offset'])){
    		$sql .= " " . $options["offset"] . ", ";
    	}
    	if(!empty($options['limit'])){
    		$sql .= " " . $options["limit"] . " ";	
    	}
    	return $sql;
    }
    private function createUpdates($p){
        $sql = " SET ";
        $i = 0;
        foreach ($p as $key => $val) {
            if ($i) {
                $sql .= ", ";
            }
            $sql .= $key . "=:" . $key;
            $i++;
        }
        return $sql;
    }

    protected function createInsert($p)
    {
        $sql = "INSERT INTO " . $this->_getTableName() . " ";
        $sql .= "( ";
        $i = 0;
        foreach ($p as $key => $val) {
            if ($i) {
                $sql .= ", ";
            }
            $sql .= $key;
            $i++;
        }
        $sql .= ") VALUES( ";
        $i = 0;
        foreach ($p as $key => $val) {
            if ($i) {
                $sql .= ", ";
            }
            $sql .= ":" . $key;
            $i++;
        }
        $sql .= ") ";

        return $sql;
    }
}