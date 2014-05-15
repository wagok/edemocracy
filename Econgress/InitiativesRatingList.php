<?php
class InitiativesRatingList extends SPRDB {
	public $Member, $Initiative, $declineByDelegant, $delegateBackFrom, $Classification;
	function GetTableName() {
		return 'InitiativesRatingList';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Member, Initiative, declineByDelegant, delegateBackFrom, Classification';
	}
}
?>