<?php
class Database{
	static $instaces = [];
	public static function getInstance($schema="default"){
		if(empty(self::$instaces[$schema])){
			self::$instaces[$schema] = new Database();	
		}
		return self::$instaces[$schema];
	}
	protected $pdo;
	public function connect($host, $db, $user, $password){
		$dsn = $this->makeDsn($host, $db);
		try{
			$this->pdo = new PDO($dsn, $user, $password);
		}catch(PDOException $e){
			exit($e->getMessage());
		}
		return true;
	}
   public function select($sql, $params=[]){
       $prepare = $this->prepare($sql, $params);
       $prepare->execute();
       return $prepare->fetchAll(PDO::FETCH_ASSOC); 
   }
   public function lastInsertId(){
       $lastid = $this->pdo->lastInsertId();
       return $lastid;
   }
   public function execute($sql, $params=[]){
       $prepare = $this->prepare($sql, $params);
       return $prepare->execute();
   }
    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }

    public function commit(){
        return $this->dbh->commit();
    }

    public function rollback(){
        return $this->dbh->rollback();
    }
    private function prepare($sql, $params){
       $prepare = $this->pdo->prepare($sql);
       foreach($params as $key => $value){
           $prepare->bindValue(":{$key}", $value);
       }
       return $prepare;
   }
   private function makeDsn($host, $db){
       return "mysql:dbname={$db};host={$host}"; 
   }
}