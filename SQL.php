<?php
class SQL {
	private $db;
	private $perfixDB = "";
	
    public function __construct(&$pdo, $perfixDB="") {
		$this->db = $pdo;
		$this->perfixDB = $perfixDB;
    }

	public function sql($sql){
		$sth = $this->db->prepare($sql);
		$sth->execute([$sql]);
		return $sth;
		
	}

	public function sT($table, $fields='*', $cond='', $order='', $limit=''){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("select $fields from $table where 1=1 $cond $order $limit");  
		$sth->execute();
		return $sth->fetchAll();
		
	}
	
	public function iT($table, $fields, $values){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("insert into $table ($fields) values ($values)");  
		$sth->execute();
		$id = $this->db->lastInsertId();
		return $id;
		
	}
	
	public function uT($table, $fieldsValues, $cond=''){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("update $table set $fieldsValues where 1=1 $cond");  
		$sth->execute();
		return $sth;
		
	}
	
	public function dT($table, $cond=''){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("delete from $table where 1=1 $cond");  
		$sth->execute();
		return $sth;
		
	}
	
	public function cT($table, $prop){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("create table if not exists $table ($prop)");  
		$sth->execute();
		return $sth;
		
	}
	
	public function aT($table, $prop){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("alter table $table ($prop)");  
		$sth->execute();
		return $sth;
		
	}
	
	public function eT($table){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("drop table if exists $table");  
		$sth->execute();
		return $sth;
		
	}
	
	public function xT($table, $name, $field){
		$table = ($this->perfixDB).$table;
		$sth = $this->db->prepare("create index $name on $table ($field)");  
		$sth->execute();
		return $sth;
		
	}
	
	public function cV($view, $sql){
		$view = ($this->perfixDB).$view;
		$sth = $this->db->prepare("create view $view as $sql");  
		$sth->execute();
		return $sth;
		
	}
	
}

