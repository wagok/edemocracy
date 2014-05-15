<?php
class Members extends SPRDB {
	private $Role;
	public $firstName, $lastName, $memberLocation, $userName, $Password,
	 $LocationDate, $voteWeight, $Email, $Blocked, $Language;
	

	function GetTableName() {
		return 'Members';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().'firstName, lastName, memberLocation, userName, Password,
	 LocationDate, voteWeight, Email, Blocked, Language';
	}
	function IsSuperAdmin(){
		return true;
	//	if ($this->Role=='SuperAdministrator') {
	//		return true;
	//	}
		return false;
	}
}
?>