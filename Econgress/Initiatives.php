<?php
class Initiative extends SPRDB {
	
	public  $Title, 
			$Description, 
			$authorRating, 
			$initRating, 
			$deadLine, 
			$Voting, 
			$Location,
			$Decision,
			$Closed,
			$Information;
	
	function GetTableName() {
		return 'Initiatives';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Title, Description, authorRating, initRating,
		deadLine, Voting, Location, Information, Decision, Closed';
	}
	function GetActiveUsingClassification(Classifications $classElem){
		$sql = sprintf("Select Initiatives.*
		From ClassificatedInitiatives Inner Join
  		Initiatives On ClassificatedInitiatives.Initiative = Initiatives.id Inner Join
  		classifications On ClassificatedInitiatives.Classification =
   		classifications.id
		Where Initiatives.Closed <> True And classifications.left_key >= %s And
  		classifications.right_key <= %s And ClassificatedInitiatives.Deleted
  		<> true And Initiatives.Deleted <> true And classifications.Deleted <> true",
		self::qs($classElem->left_key), self::qs($classElem->right_key));
		$this->QueryAndFill( $sql);
	}
	// Создать временную таблицу со всеми парент элементами классификаций для
	// элементов классификаций по которым данная инициатива классифицирована
	function CreateTempTableCCI($TempTableName) {
		$sql = "CREATE TEMPORARY TABLE {$TempTableName} (id int NOT NULL ,   
		PRIMARY KEY (id)) TYPE = HEAP ROW_FORMAT =DEFAULT";
		mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		$sql = sprintf("insert into {$TempTableName} Select Distinct cl2.id
From classifications As cl1, classifications As cl2, ClassificatedInitiatives
Where ClassificatedInitiatives.Initiative = %s And
  ClassificatedInitiatives.Classification = cl1.id And cl2.left_key <=
  cl1.left_key And cl2.right_key >= cl1.right_key and cl2.deleted<>true and cl1.deleted<>true",
   self::qs($this->id));
		mysql_query($sql, self::$DB) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
  		return true;
	}
}
?>