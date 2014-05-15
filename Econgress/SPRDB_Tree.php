<?php
class SPRDB_TREE extends SPRDB {
	public $left_key, $right_key, $level, $Parent, $Path;

	function GetFieldsList() {
		return parent::GetFieldsList().',left_key, right_key, level, Parent, Path';
	}	
	
	function SelectAllChilds() {
		if (! $this->Selected) {
			return false;
		}
		
		$sql = sprintf ( 'SELECT * from %s 
				WHERE left_key>=%s and right_key<=%s and level>%s and Deleted=false 
				ORDER BY left_key ', $this->GetTableName (), $this->left_key, $this->right_key, $this->level );
		
		return $this->QueryAndFill ( $sql );
	}
	function SelectChilds() {
		if (! $this->Selected) {
			return false;
		}
		
		$sql = sprintf ( 'SELECT * from %s 
				WHERE left_key>=%s and right_key<=%s and level=%s and Deleted=false 
				ORDER BY left_key ', $this->GetTableName (), $this->left_key, $this->right_key, $this->level + 1 );
		
		return $this->QueryAndFill ( $sql );
	}
	
	function SelectAllParents() {
		if (! $this->Selected) {
			return false;
		}
		
		$sql = sprintf ( 'SELECT * from %s 
				WHERE left_key<=%s and right_key>=%s and Deleted=false  
				ORDER BY left_key ', $this->GetTableName (), $this->left_key, $this->right_key );
		
		return $this->QueryAndFill ( $sql );
	}
	function GetParent() {
		if (! $this->Selected) {
			return false;
		}
		
		$sql = sprintf ( 'SELECT * from %s 
				WHERE id=%s and Deleted=false', $this->GetTableName (), $this->Parent );
		
		return $this->QueryAndFill ( $sql );
	}
	function CreateChild() {
		if (! $this->Selected) {
			return false;
		}
		$sql = sprintf ( 'UPDATE %s SET right_key=right_key+2,
		left_key=IF(left_key > %s, left_key+2, left_key) 
				WHERE right_key>=%s and Deleted=false', 

		$this->GetTableName (), $this->right_key, $this->right_key );
		mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$this->Create ();
		$this->left_key = $this->right_key;
		$this->right_key += 1;
		$this->level += 1;
		$this->Path = $this->Path.'/'.$this->id;
		$this->Parent = $this->id;
		$this->Save ();
		
		return true;
	}
	function DeleteNode() {
		if (! $this->Selected or $this->Deleted) {
			return false;
		}
		$sql = sprintf ( 'UPDATE %s SET Deleted=true where left_key>=%s and right_key<=%s and Deleted=false', $this->GetTableName (), $this->left_key, $this->right_key );
		mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$sql = sprintf ( 'UPDATE %1$s SET 
		left_key = if ( left_key > %2$s, left_key-(%3$s - %2$s +1) , left_key),
		right_key = right_key - (%3$s - %2$s +1) WHERE right_key > %3$s and Deleted=false', $this->GetTableName (), $this->left_key, $this->right_key );
		mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$this->Selected = false;
	}
  function CheckTreeTable(){
   	$tableName = $this->GetTableName();
  	// 1
   	$sql = "SELECT id FROM {$tableName} WHERE left_key>=right_key and Deleted<>true";
   	$res = mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );;
   	if (mysql_num_rows($res)>0) {
   		throw new Exception("Econgress class: Tree table {$tableName} has wrong rows, where left_key >= right_key. (1)");
   	}
      	// 2-3
   	$sql = "SELECT count(id) as Num, min(left_key) as ml, max(right_key) as mr FROM {$tableName} where Deleted<>true";
   	$res = mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );;
   	if (mysql_num_rows($res)<1) {
   		throw new Exception("Econgress class: Tree table {$tableName} has not any rows.");
   	}  	
   	$row = mysql_fetch_assoc($res);
   	if ($row['ml']!=1) {
   		throw new Exception("Econgress class: Tree table {$tableName} min left key is not 1. (2)");
   	}
    if ($row['mr']!=$row['Num']*2) {
   		throw new Exception("Econgress class: Tree table {$tableName} max right key is not 2*ElemCount. (3)");
   	}
     	// 4
   	$sql = "SELECT id FROM {$tableName} WHERE MOD((right_key - left_key),2)=0 and Deleted<>true";
   	$res = mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );;
   	if (mysql_num_rows($res)>0) {
   		throw new Exception("Econgress class: Tree table {$tableName} has wrong rows, where left_key - right_key = even. (4)");
   	}
       	// 5
   	$sql = "SELECT id FROM {$tableName} WHERE MOD((left_key - level +2),2)=0 and Deleted<>true";
   	$res = mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );;
   	if (mysql_num_rows($res)>0) {
   		throw new Exception("Econgress class: Tree table {$tableName} has wrong rows. (5)");
   	}
          	// 6
   	$sql = "SELECT t1.id, count(t1.id) as rep, max(t3.right_key) as max_right from {$tableName} as t1,
   	{$tableName} as t2, {$tableName} as t3 where t1.Deleted<>true and t2.Deleted<>true and t3.Deleted<>true 
   	and t1.left_key<>t2.left_key and
   	t1.left_key<>t2.right_key and t1.right_key<>t2.left_key and t1.right_key<>t2.right_key
   	group by t1.id having max_right<>sqrt(4*rep+1)+1";
   	
   	$res = mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );;
   	if (mysql_num_rows($res)>0) {
   		throw new Exception("Econgress class: Tree table {$tableName} has wrong rows. (6)");
   	}
   	
   }
}
?>