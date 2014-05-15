<?php
define ('SYSMESSAGE_TYPE_NORMAL',0);

class SysMessages extends SPRDB {
	
	public  $messTo, 
			$Message, 
			$Type;
	
	function GetTableName() {
		return 'SysMessages';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', messTo, Type, Message';
	}
	function NewMessage($From,$To,$Type, $Message) {
		$this->Author = $From;
		$this->messTo = $To;
		$this->Message = $Message;
		$this->Type = $Type;
		$this->Save();
	}
	function __construct($From=0,$To=0,$Type=0, $Message=0) {
		if ($From!=0 && $To!=0) {
			parent::Create();
			$this->NewMessage($From,$To,$Type, $Message);
		} else {
			parent::__construct();
		}
	}
}
?>