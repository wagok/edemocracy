<?php
class SysLog extends SPRDB {
	public $Error;
	function GetTableName() {
		return 'SysLog';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Error';
	}
	function NewMessage($Error) {
		$this->Author = isset($_SESSION['uid'])?$_SESSION['uid']:1;
		$this->Error = $Error;
		$this->Save();
	}
	function __construct($Error='') {
		if ($Error!='') {
			parent::Create();
			$this->NewMessage($Error);
		} else {
			parent::__construct();
		}
	}
}
?>