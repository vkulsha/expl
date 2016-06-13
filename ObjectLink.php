<?php
class ObjectLink {
	private $sql;
	
    public function __construct(&$pdo) {
		$this->sql = new SQL($pdo);
    }

	public function cO($n, $pid){
		$ret = $this->sql->iT("object", "n", "'".$n."'");  
		
		$pid = $pid ? $pid : 1;
		if ($pid) {
			$this->cL($id, $pid);
		}
		return $ret;
		
	}

	public function cL($o1, $o2){
		$ret = $this->sql->iT("link", "o1, o2", $o1.",".$o2);  
		return $ret;
		
	}

	public function gO($n){
		$ret = $this->sql->sT("object", "id", "and n = '".$n."'", "order by id", "limit 1");
		return $ret ? $ret[0][0] : null;
		
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