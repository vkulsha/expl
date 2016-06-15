<?php
class ObjectLink {
	private $sql;
	
    public function __construct(SQL &$sql){
		$this->sql = $sql;
    }

	public function cD(){//create database
		try {
			$this->sql->cT(["object", "id bigint not null auto_increment, n char(255), d timestamp, c bigint, primary key(id), index(n), index(d), index(c)"]);  
			$this->sql->cT(["link", "id bigint not null auto_increment, o1 bigint, o2 bigint, c bigint, d timestamp, primary key(id), index(o1), index(o2), index(c), index(d)"]);
			$ret = $this->cO(["Класс"]);
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}
	
	public function cO($params){//create object and link
		try {
			$n = $params[0];
			$pid = isset($params[1]) ? $params[1] : 1;
			
			$id = $this->sql->iT(["object", "n", "'$n'"]);  

			$pid = isset($pid) ? $pid : 1;
			if ($pid) {
				$this->cL([$id, $pid]);
			}
			$ret = $id;
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function cL($params){//link objects
		try {
			$o1 = $params[0];
			$o2 = $params[1];
			
			$ret = $this->sql->iT(["link", "o1, o2", "$o1,$o2"]);  
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function gO($params){//get object id by name
		try {
			$n = $params[0];
			$isClass = isset($params[1]) ? "and id in (select o1 from link where o2 = 1) " : "";
			
			$ret = $this->sql->sT(["object", "id", "and n = '$n' $isClass", "order by id", "limit 1"]);
			return $ret ? $ret[0][0] : null;
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}
	
	public function gN($params){//get object name by id
		try {
			$id = $params[0];
			
			$ret = $this->sql->sT(["object", "n", "and id = '$id'", "", "limit 1"]);
			return $ret ? $ret[0][0] : null;
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function uO($params){//update object name by id
		try {
			$id = $params[0];
			$n = $params[1];
			
			$ret = $this->sql->uT(["object", "n='$n'", "and id=$id"]);  
			return $ret;
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}
	
	public function eO($params){//erase object from database
		try {
			$id = $params[0];
			
			$ret = $this->sql->dT(["link", "and (o1=$id or o2=$id)"]);  
			$ret = $this->sql->dT(["object", "and id=$id"]);  
			return $ret;
			
		} catch (Exception $e) {
			print($e);
			$ret = null;
		}
		return $ret;
	}
	
	public function eL($params){//erase link from database
		try {
			$o1 = $params[0];
			$o2 = $params[1];
			
			$ret = $this->sql->dT(["link", "and ((o1=$o1 and o2=$o2) or (o2=$o1 and o1=$o2))"]);  
			return $ret;
			
		} catch (Exception $e){
			print($e);
			$ret = null;
		}
		return $ret;
	}
	
	public function gC($params){//get class object fields
		try {
			$id = $params[0];
			$fields = isset($params[1]) ? $params[1] : "*";
			
			$ret = $this->sql->sT(["object",$fields,"and id in (select o2 from link where o2 in (select o1 from link where o2 = (select id from object where n='Класс' limit 1)) and o1 = $id)"]);
			return $ret;
			
		} catch (Exception $e){
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function gCn($params){//get class object names
		try {
			$id = $params[0];
			
			$ret = $this->gC([$id, "n"]);  
			return $ret;
			
		} catch (Exception $e){
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function gCid($params){//get class object ids
		try {
			$id = $params[0];
			
			$ret = $this->gC([$id, "id"]);  
			return $ret;
			
		} catch (Exception $e){
			print($e);
			$ret = null;
		}
		return $ret;
	}

	public function getTableQuery($params){//[{id:1331, n:"ик", parentCol:0, linkParent:false, inClass:false}]
		try {
			$paramsArr = $params[0];
			$groupbyind = isset($params[1]) ? $params[1] : "0";
			
			$result = [];
			$head = [];
			$body = [];
			$foot = [];
			$i = -1;
			foreach ($paramsArr as $cc){
				$i++;
				if (isset($cc["n"])) {
					//i++;
					$id = isset($cc["id"]) ? $cc["id"] : null;
					$col = isset($cc["n"]) ? $cc["n"] : null;
					$plink = isset($cc["linkParent"]) ? $cc["linkParent"] : null;
					$pcol = isset($cc["parentCol"]) ? $cc["parentCol"] : null;
					$inClass = isset($cc["inClass"]) ? $cc["inClass"] : null;
					if ($i==0){
						$h = "select o".$i.".id `id ".$col."`, o".$i.".n `".$col."` \n";
						$l = $id ? $id : "(select id from object where n='".$col."' limit 1)";
						$b = 
							"from (\n".
							"	select id, n from object where id in ( \n".
							"		select o1 from link where o2 = ".$l." \n".
							($inClass ? "" : "and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1)) \n").
							"	) \n".
							"	group by id \n".
							")o".$i." \n";
						$head[] = $h;
						$body[] = $b;
					} else {
						$h = "";
						if ($groupbyind !== false) {
							$h = ",case when count(distinct o".$i.".id) <= 1 then group_concat(distinct o".$i.".id) else concat(o".$i.".id,'..') end `id ".$col."` ".
								",case when count(distinct o".$i.".id) <= 1 then group_concat(distinct o".$i.".n)  else concat(o".$i.".n,'..')  end `".$col."` ".
								",count(distinct o".$i.".id) `кол-во ".$col."` \n";
						} else {
							$h = ",o".$i.".id `id ".$col."` ".
								",o".$i.".n `".$col."` ";
						}
						$l = $id ? $id : "(select id from object where n='".$col."' limit 1)";
						$selecto1o2 = $plink ? "select o1 o2, o2 o1 from link where o2 in (" : "select o1, o2 from link where o1 in (";
						$parentCol = $pcol ? $pcol : 0;
						$b = 
							"left join ( \n".
							"	".$selecto1o2." \n".
							"		select o1 from link where o2 = ".$l." \n".
							($inClass ? "" : "and o1 not in (select o1 from link where o2 = (select id from object where n='класс' limit 1)) \n").
							"	) \n".
							"	group by o1, o2 \n".
							")l".$i." on l".$i.".o2 = o".$parentCol.".id left join object o".$i." on o".$i.".id = l".$i.".o1 \n";
						
						$head[] = $h;
						$body[] = $b;
					}
				}
			}
			
			if ($groupbyind !== false) {
				$foot[] = "group by o".$groupbyind.".id having 1=1 \n\n";
			}
			$result = join("",$head).join("",$body).join("foot",$foot);
			return $result;
			
		} catch (Exception $e){
			print($e);
			return null;
		}
	}
	
	public function gTq($params){//["a","b","c"], [[1,0],[2,0],[3,1]], [1,2], [1], false
		try {
			$nArr = isset($params[0]) ? $params[0] : [];
			$parentColArr = isset($params[1]) ? $params[1] : [];
			$linkParentArr = isset($params[2]) ? $params[2] : [];
			$inClassArr = isset($params[3]) ? $params[3] : [];
			$groupByInd = isset($params[4]) ? $params[4] : false;

			$opts = [];
			for ($i=0; $i < count($nArr); $i++){
				$opts[] = array("n"=>$nArr[$i], "parentCol"=>0, "linkParent"=>false);
			}
			for ($i=0; $i < count($parentColArr); $i++){
				$opts[$parentColArr[$i][0]]["parentCol"] = $parentColArr[$i][1];
			}
			for ($i=0; $i < count($linkParentArr); $i++){
				$opts[$linkParentArr[$i]]["linkParent"] = true;
			}
			for ($i=0; $i < count($inClassArr); $i++){
				$opts[$inClassArr[$i]]["inClass"] = true;
			}
			return $this->getTableQuery([$opts, $groupByInd]);
			
		} catch (Exception $e){
			print($e);
			return null;
		}
	}	
	
	public function gT($params){//["a","b","c"], [[1,0],[2,0],[3,1]], [1,2], [1], false, "*", "and a = 115"
		try {
			$fields = isset($params[5]) ? $params[5] : "*";
			$cond = isset($params[6]) ? $params[6] : "";
			
			$sel = $this->gTq($params);
			return $this->sql->sT(["(".$sel.")x", $fields, $cond]);
			
		} catch (Exception $e){
			print($e);
			return null;
		}
	}	

	public function gAnd($params){
		try {
			$objects = join(",",$params[0]);
			$count = count($params[0]);
			$fields = isset($params[1]) ? $params[1] : "*";
			$notIsClass = isset($params[2]) ? "not in (select o1 from link where o2 = 1)" : "";
			$notIsClass1 = $notIsClass ? "and o1 $notIsClass" : "";
			$notIsClass2 = $notIsClass ? "and o2 $notIsClass" : "";
			$cond = isset($params[3]) ? $params[3] : "";
			$parent = isset($params[4]) ? "and not parent" : "";
			
			$sel = "and id in ( ".
					"select o1 from ( ".
					"select o1, o2, false parent from link where o2 in ($objects) $notIsClass1 ".
					"union all ".
					"select o2, o1, true  parent from link where o1 in ($objects) $notIsClass2 ".
					")x where 1=1 ".
					"$parent".
					"group by o1 ".
					"having count(o1) = $count ".
				") $cond";
			return $this->sql->sT(["object", $fields, $sel]);
		} catch (Exception $e){
			print($e);
			return null;
		}
	}
	
}

?>