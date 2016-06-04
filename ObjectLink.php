<?php
class ObjectLink {
	private $db;
    public function __construct($pdo) {
		$this->db = $pdo;
    }

	public function cO($n, $pid){
		$sth = $this->db->prepare("insert into object (n) values (?)");  
		$sth->execute([$n]);
		
		$id = $this->db->lastInsertId();
		$pid = $pid ? $pid : 1;
		if ($pid) {
			$this->cL($id, $pid);
		}
		return $id;
		
	}

	public function cL($o1, $o2){
		$sth = $this->db->prepare("insert into link (o1, o2) values (?, ?)");  
		$sth->execute([$o1, $o2]);
		return $sth;
		
	}

	public function gO($n){
		$sth = $this->db->prepare("select id from object where n = ?");  
		$sth->execute([$n]);
		return $sth->fetch()[0];
		
	}
	
	public function gN($id){
		$sth = $this->db->prepare("select n from object where id = ?");  
		$sth->execute([$id]);
		return $sth->fetch()[0];
		
	}

	public function uO($id, $n){
		$sth = $this->db->prepare("update object set n = ? where id = ?");  
		$sth->execute([$n, $id]);
		return $sth;
		
	}
	
	public function eO($id){
		$sth = $this->db->prepare("delete from link where o1 = :id or o2 = :id; delete from object where id = :id");  
		$sth->execute(array("id" => $id));
		return $sth;
	}
	
	public function eL($o1, $o2){
		$sth = $this->db->prepare("delete from link where (o1 = :o1 and o2 = :o2) or (o1 = :o2 and o2 = :o1)");  
		$sth->execute(array("o1" => $o1, "o2" => $o2));
		return $sth;
	}
	
	
}

?>