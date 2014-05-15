<?php
class Voting extends SPRDB {
	public $Initiative, $startDate, $deadLine, $votingRating, $Pro, $Con, $voidVoting;
	function GetTableName() {
		return 'Voting';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Initiative, startDate, deadLine, votingRating, Pro, Con, voidVoting';
	}
}
?>