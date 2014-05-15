<?php
class Locations extends SPRDB_TREE {
	public $Title, $Description;
	function GetTableName() {
		return 'Locations';
	}
	function GetFieldsList() {
		return parent::GetFieldsList(). ', Title, Description';
	}
	function InTree($Location){
	$sql = sprintf("SELECT  count(*) as cc
From Locations 
Where Locations.id = %s and  
Locations.left_key >= %s And Locations.right_key <= %s And Locations.Deleted<>true",
		self::qs($Location), self::qs($this->left_key),self::qs($this->right_key));
	$res = mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
	if (!$row = mysql_fetch_assoc($res)) {
   		throw new Exception("Locations class: Query return null.");
   	}
	return $row['cc']==1?true:false;
	}
}
?>