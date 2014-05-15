<?php

class Constants {
static $Consts;
function __construct() {
	self::$Consts = array();
	$result = mysql_query('SELECT * From Constants');
 	while ($row = mysql_fetch_assoc($result)) {
        self::$Consts[$row["Name"]] = $row["Value"]; 
    }
}
function GetValue($ConstName) {
  if (isset(self::$Consts[$ConstName])) {
  	return 	self::$Consts[$ConstName];
  } else {
  	throw new Exception('Constant '.$ConstName.' does not exist.');
  }
}
function SetValue($ConstName, $ConstValue) {
 $q = sprintf("REPLACE into Constants SET  Name = '%s', Value = '%s' ",
      $ConstName, $ConstValue);
 	mysql_query($q);
 self::$Consts[$ConstName] = $ConstValue;	
}
}
?>