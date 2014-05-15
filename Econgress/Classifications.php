<?php
class Classifications extends SPRDB_TREE {
	public $Classification, $Title, $Description,  $Rating, $Information;
	function GetTableName() {
		return 'Classifications';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Classification, Title, Description, Rating, Information';
	}

}
?>