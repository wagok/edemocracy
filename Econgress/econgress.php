<?php
require_once 'SPRDB.php';
require_once 'SPRDB_Tree.php';
require_once 'Members.php';
require_once 'Initiatives.php';
require_once 'Classifications.php';
require_once 'Locations.php';
require_once 'InitiativesRatingList.php';
require_once 'Voting.php';
require_once 'Votes.php';
require_once 'Decisions.php';
require_once 'Delegations.php';
require_once 'ClassificatedInitiatives.php';
require_once 'delegationsToClassify.php';
require_once 'Constants.php';
require_once 'SysMessages.php';
require_once 'SysLog.php';

class Econgress {
	public static $DB = false;
	static $hostName = "localhost";
	static $DBuserName = "root";
	static $DBpassword = "";
	static $DbaseName = "SPR";
	static $Member = false;
	static $Constants;
	
	static function qs($value) {
		if (get_magic_quotes_gpc ()) {
			$value = stripslashes ( $value );
		}
		if (! is_numeric ( $value )) {
			$value = "'" . mysql_real_escape_string ( $value ) . "'";
		}
		return $value;
	}

	// Функция:
	// 1. Устанавливает соединение SQL
	// 2. Начинает сессию
	// 3. Проверяет совершен ли вход пользователем и если да то запоминает его данные в объекте
	// 4. Подключает языковой файл в соответствии с установкой языка пользователя
	// 5. Загружает константы в память
	static function Init($connect = 0) {
		if ($connect == 0) {
			$connect = mysql_connect ( self::$hostName, self::$DBuserName, self::$DBpassword );
			if (! $connect) {
				throw new Exception ( "Class E-congress: Unable to connect to MySQL" );
			}
			if (! mysql_select_db ( self::$DbaseName, $connect )) {
				throw new Exception ( "Class E-congress: Could not select the database" );
			}
		}
		
		self::$DB = $connect;
		SPRDB::Init ( self::$DB );
		mysql_query ( "SET NAMES 'utf8'", self::$DB );
		session_start ();
		header ( 'Content-type: text/html; charset=utf-8' );
		if (isset ( $_SESSION ['uid'] )) {
			self::$Member = new Members ( );
			self::$Member->GetByID ( $_SESSION ['uid'] );
			if (! self::$Member->Selected) {
				new SysLog ( 'Class Econgress: Session user id do not correspond to DB record.' );
				throw new Exception ( 'Class Econgress: Session user id do not correspond to DB record.' );
			}
			if (file_exists ( 'Econgress/Language/' . self::$Member->Language . '.php' )) {
				require_once 'Econgress/Language/' . self::$Member->Language . '.php';
			} else {
				require_once 'Econgress/Language/english.php';
			}
		
		}
		self::$Constants = new Constants ( );
	}
	static function LogIn($userName, $password) {
		self::$Member = new Members ( );
		self::$Member->userName = $userName;
		self::$Member->Password = $password;
		self::$Member->Find ();
		if (self::$Member->Selected) {
			if (self::$Member->Blocked) {
				return false;
			}
			$_SESSION ['uid'] = self::$Member->id;
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, 'Login' );
			return true;
		} else {
			return false;
		}
	}
	static function LogOut() {
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, 'Logout' );
		unset ( $_SESSION ['uid'] );
		self::$Member = false;
		return true;
	}
    static function ChangeMemberPassword($newPass) {
		if (self::$Member == false) {
			new SysLog ( 'Class Econgress: User not loged in.' );
			throw new Exception ( 'Class Econgress: User not loged in.' );
		}
		self::$Member->Password = $newPass;
		self::$Member->Save ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_PasswordWasChanged );
	}
	static function ChangeMemberPasswordWithCheck($oldPass,$newPass,$newPass2) {
		if (self::$Member->Password!=$oldPass) {
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_WrongOldPasswordForPasswordChangeOperation );
			throw new Exception ( LANG_WrongOldPasswordForPasswordChangeOperation );
		}
		if ($newPass!=$newPass2) {
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_NewPasswordsNotMatch );
			throw new Exception ( LANG_NewPasswordsNotMatch );
		}
		self::ChangeMemberPassword($newPass);
		return true;
	}
	static function ChangeMemberLocation($MemberId, $ToLocation, $NoPeriodCheck){
		$Member = new Members();
		$Member->GetByID($MemberId);
		if (!$Member->Selected) {
			new SysLog("Member {$MemberId} not found to change member Location");
			throw new Exception ( "Member {$MemberId} not found to change member Location" );
		}
		$Location = new Locations();
		$Location->GetByID($ToLocation);
		if (!$Location->Selected) {
			new SysLog("Location {$ToLocation} not found to change member Location");
			throw new Exception ( "Location {$ToLocation} not found to change member Location" );
		}
		$Location->SelectChilds();
		if ($Location->Selected) {
			new SysLog("Try to change member Location to Locations group #{$ToLocation}");
			throw new Exception ("Try to change member Location to Locations group #{$ToLocation}");
		}
		if (!$NoPeriodCheck && 
		   strtotime($Member->LocationDate)+
		   			timer(self::$Constants->GetValue('TimeBetweenReLocations'))>time()) {
			new SysMessages(1,$Member->id,0,LANG_MessageTimeToReLocation.date('r',strtotime($Member->LocationDate)+
		   			timer(self::$Constants->GetValue('TimeBetweenReLocations'))));
		   	return false;			
		}
		$Member->memberLocation = $ToLocation;
		if (!$NoPeriodCheck) {
			$Member->LocationDate = date('c',time()); 
		}
		$Member->Save();
		return true;
	}
	static function GroupRelocation($fromLocation, $toLocation) {

		if (!self::$Member->IsSuperAdmin()) {
			new SysLog ( 'Econgress class: User have not permissions to execute group relocation' );
			throw new Exception (  'Econgress class: User have not permissions to execute group relocation' );
		}
		
		$sql = sprintf("
						UPDATE Members SET memberLocation=%s
						Where Members.Deleted <> true And 
							  Members.memberLocation =%s ",
		self::qs($toLocation), self::qs($fromLocation));
		if (!  mysql_query ( $sql )) {
			new SysLog ( 'Econgress class: Error whith query to group relocate.' );
			throw new Exception ( 'Econgress class: Error whith query to group relocate.' );
		}
		return true;
	}

	static function AddNewClassification($Title, $Description, $Information) {
		if (! isset ( $_SESSION ['uid'] )) {
			throw new Exception ( 'Econgress class: Members only may create new classifications' );
		}
		$NewClass = new Classifications ( );
		$NewClass->GetByID ( 1 );
		$NewClass->CreateChild ();
		$NewClass->Title = $Title;
		$NewClass->Classification = $NewClass->id;
		$NewClass->Description = $Description;
		$NewClass->Information = $Information;
		$NewClass->Author = self::$Member->id;
		$NewClass->Save ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_NewClassificationCreated . $NewClass->Title );
	}
	static function AddNewClassificationElement($Classification, $Title, $Description, $Information) {
		$NewClass = new Classifications ( );
		$NewClass->GetByID ( $Classification );
		if (! $NewClass->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($NewClass->Author != self::$Member->id) {
			new SysLog ( 'Econgress class: User have not permissions to modify classification element #' . $Classification );
			throw new Exception ( 'Econgress class: User have not permissions to modify classification element #' . $Classification );
		}
		$rootClassification = $NewClass->Classification;
		$NewClass->CreateChild ();
		$NewClass->Title = $Title;
		$NewClass->Description = $Description;
		$NewClass->Information = $Information;
		$NewClass->Author = self::$Member->id;
		$NewClass->Classification = $rootClassification;
		$NewClass->Save ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_NewElementOfClassificationCreated . $NewClass->Title );
	}
	static function AddNewLocationElement($Location, $Title, $Description) {
		$NewLoc = new Locations();
		$NewLoc->GetByID ( $Location );
		if (! $NewLoc->Selected) {
			new SysLog ( 'Econgress class: Location element id#' . $Location . ' not found.' );
			throw new Exception ( 'Econgress class: Location element id#' . $Location . ' not found.' );
		}
		if (!self::$Member->IsSuperAdmin()) {
			new SysLog ( 'Econgress class: User have not permissions to modify Location element #' . $Location );
			throw new Exception ( 'Econgress class: User have not permissions to modify Location element #' . $Location );
		}
		$NewLoc->CreateChild ();
		$NewLoc->Title = $Title;
		$NewLoc->Description = $Description;
		$NewLoc->Author = self::$Member->id;
		$NewLoc->Save ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_NewElementOfLocationCreated.': ' . $NewLoc->Title );
	}
	static function ChangeLocationElement($Location, $Title, $Description) {
		$NewLoc = new Locations();
		$NewLoc->GetByID ( $Location );
		if (! $NewLoc->Selected) {
			new SysLog ( 'Econgress class: Location element id#' . $Location . ' not found.' );
			throw new Exception ( 'Econgress class: Location element id#' . $Location . ' not found.' );
		}
		if (!self::$Member->IsSuperAdmin()) {
			new SysLog ( 'Econgress class: User have not permissions to modify Location element #' . $Location );
			throw new Exception ( 'Econgress class: User have not permissions to modify Location element #' . $Location );
		}
		$OldTitle = $NewLoc->Title;
		$NewLoc->Title = $Title;
		$NewLoc->Description = $Description;
		$NewLoc->Author = self::$Member->id;
		$NewLoc->Save ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, sprintf(LANG_LocationElementChanged, $OldTitle, $NewLoc->Title ));
		
	}
	static function DeleteLocation($Location) {
		$NewLoc = new Locations();
		$NewLoc->GetByID($Location);
		if (! $NewLoc->Selected) {
			new SysLog ( 'Econgress class: Location element id#' . $Location . ' not found.' );
			throw new Exception ( 'Econgress class: Location element id#' . $Location . ' not found.' );
		}
		if (!self::$Member->IsSuperAdmin()) {
			new SysLog ( 'Econgress class: User have not permissions to delete Location element #' . $Location );
			throw new Exception ( 'Econgress class: User have not permissions to delete Location element #' . $Location );
		}
		// Проверить не используется ли локация где либо в неудаленных записях.
		$sql = sprintf("Select Distinct Count(*) As Num
From Initiatives, Locations, Members
Where Locations.left_key >= %s And Locations.right_key <= %s And
  (Initiatives.Location = Locations.id Or Members.memberLocation = Locations.id)
  And Initiatives.Deleted <> true And Locations.Deleted <> true And
  Members.Deleted <> true", $NewLoc->left_key, $NewLoc->right_key);
	if (! ($res = mysql_query ( $sql, self::$DB ))) {
			new SysLog ( 'Econgress class: Error whith query checking location using.' );
			throw new Exception ( 'Econgress class: Error whith query checking location using.' );
		}
	$row = mysql_fetch_assoc($res);
	if ($row['Num']>0) {
			new SysMessages(1,self::$Member->id,SYSMESSAGE_TYPE_NORMAL,"Location is used and can not be removed.");
			throw new Exception ( 'Location is used and can not be removed.' );
	}
	// Проверка пройдена можно пометить ветвь на удаление
	$sql = sprintf("update  Locations
SET Deleted = true
Where Locations.left_key >= %s And Locations.right_key <= %s And
  Locations.Deleted <> true", $NewLoc->left_key, $NewLoc->right_key);
	if (! ($res = mysql_query ( $sql, self::$DB ))) {
			new SysLog ( 'Econgress class: Error whith query removing location.' );
			throw new Exception ( 'Econgress class: Error whith query removing location.' );
		}	
			new SysMessages(1,self::$Member->id,SYSMESSAGE_TYPE_NORMAL,"Location deleted succesfuly.");
		return true;
	}
	static function DeleteClassification($Classification) {
		$NewClass = new Classifications ( );
		$NewClass->GetByID ( $Classification );
		if (! $NewClass->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($NewClass->Author != self::$Member->id) {
			new SysLog ( 'Econgress class: User have not permissions to delete classification ' . $NewClass->Title );
			throw new Exception ( 'Econgress class: User have not permissions to delete classification ' . $NewClass->Title );
		}
		// Выбрать все дочерние и проверить по ним незакрытие инициативы
		$activInits = new Initiative ( );
		$activInits->GetActiveUsingClassification ( $NewClass );
		if ($activInits->Selected) {
			// Существуют активные инициативы использующие элементы поддерева
			// удалять нельзя
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_ActiveInitiativesExistForThisClassificationElemOrItChilds );
			return false;
		}
		// Выбрать делегирования которые будут помечены на удаление и сообщить пользователям.
		$sql = sprintf ( "insert into SysMessages (Author, addDate, Deleted, Type, messTo, Message) 
	Select %s, %s, false, 0, Delegations.delegateFrom, concat(%s, Delegations.Classification, %s,
  Delegations.delegateTo, ',', Members.firstName,',', Members.lastName, ',', Members.userName)
From Delegations Inner Join
  classifications On Delegations.Classification = classifications.id Inner Join
  Members On Delegations.delegateTo = Members.id
Where Delegations.Deleted <> true And classifications.Deleted <> true And
  classifications.left_key >= %s And classifications.right_key <= %s", self::qs ( self::$Member->id ), self::qs ( Date ( 'c' ) ), self::qs ( LANG_MessageDelegationDeletedWithClassification1 ), self::qs ( LANG_MessageDelegationDeletedWithClassification2 ), self::qs ( $NewClass->left_key ), self::qs ( $NewClass->right_key ) );
		
		// Выбрать делегирования на классифицирование
		$sql = sprintf ( "Select classifications.Classification As Classification, Members.id As uid,
  classifications.Title As Title
From classifications Inner Join
  delegationsToClassify On delegationsToClassify.Classification =
    classifications.id Inner Join
  Members On delegationsToClassify.delegateTo = Members.id
Where classifications.Deleted <> true And classifications.left_key >= %s And
  classifications.right_key <= %s And delegationsToClassify.Deleted <> true", self::qs ( $NewClass->left_key ), self::qs ( $NewClass->right_key ) );
		if (! ($res = mysql_query ( $sql, self::$DB ))) {
			new SysLog ( 'Econgress class: Error whith query delegations to classify for messaging.' );
			throw new Exception ( 'Econgress class: Error whith query delegations to classify.' );
		}
		while ( $row = mysql_fetch_assoc ( $res ) ) {
			$mess = sprintf ( LANG_MessageDelegationToClassify_ClassificationWasDeleted, $row ['Classification'], $row ['Title'] );
			new SysMessages ( self::$Member->id, $row ['uid'], SYSMESSAGE_TYPE_NORMAL, $mess );
		}
		
		// Выбрать все дочерние и пометить на удаление все записи делегирования по ним.
		// Работаем и с делегированием голосов и делегирование классифицировать одновременно
		$sql = sprintf ( "update Delegations Inner Join
  classifications On Delegations.Classification = classifications.id Inner Join
  delegationsToClassify On delegationsToClassify.Classification =
    classifications.id
  SET Delegations.Deleted=true, delegationsToClassify.Deleted=true
Where Delegations.Deleted <> true And classifications.Deleted <> true And
  classifications.left_key >= %s And classifications.right_key <=
  %s And delegationsToClassify.Deleted <> true", self::qs ( $NewClass->left_key ), self::qs ( $NewClass->right_key ) );
		if (! mysql_query ( $sql, self::$DB )) {
			new SysLog ( 'Econgress class: Error encountered with updating delegatins elements during classification deleting ' . $NewClass->Title );
			throw new Exception ( 'Econgress class: Error encountered with updating delegatins elements during classification deleting ' . $NewClass->Title );
		}
		$NewClass->DeleteNode ();
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_ClassificationWasDeleted . $NewClass->Title );
		return true;
	}
	static function DelegateToClassify($Classification, $Member) {
		$NewClass = new Classifications ( );
		$NewClass->GetByID ( $Classification );
		if (! $NewClass->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($NewClass->Author != self::$Member->id) {
			new SysLog ( 'Econgress class: User have not permissions to delete classification ' . $NewClass->Title );
			throw new Exception ( 'Econgress class: User have not permissions to delete classification ' . $NewClass->Title );
		}
		if ($NewClass->id != $NewClass->Classification) {
			new SysLog ( 'Econgress class: You must delegate to root of Classification ' . $NewClass->Title );
			throw new Exception ( 'Econgress class: You must delegate to root of Classification ' . $NewClass->Title );
		}
		$mem = new Members ( );
		$mem->GetByID ( $Member );
		if (! $mem->Selected) {
			new SysLog ( 'Econgress class: Member id#' . $Member . ' not found.' );
			throw new Exception ( 'Econgress class: Member id#' . $Member . ' not found.' );
		}
		
		$delegation = new delegationsToClassify ( );
		$delegation->delegateTo = $Member;
		$delegation->Classification = $Classification;
		$delegation->Find ();
		if ($delegation->Selected) {
			$mess = sprintf ( LANG_MessageDelegationToClassifyRepeatedTry, $NewClass->id, $NewClass->Title, $mem->id, $mem->firstName . ' ' . $mem->lastName );
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			return false;
		}
		$delegation->Create ();
		$delegation->Classification = $Classification;
		$delegation->delegateTo = $Member;
		$delegation->Author = self::$Member->id;
		$delegation->Save ();
		$mess = sprintf ( LANG_MessageDelegationToClassifyCreated, $NewClass->id, $NewClass->Title, $mem->id, $mem->firstName . ' ' . $mem->lastName );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		new SysMessages ( self::$Member->id, $mem->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function DeleteClassifyRight($DelegationRecordId) {
		$DelRecord = new delegationsToClassify ( );
		$DelRecord->GetByID ( $DelegationRecordId );
		if (! $DelRecord->Selected) {
			new SysLog ( 'Econgress class: Delegation to classify element id#' . $DelegationRecordId . ' not found.' );
			throw new Exception ( 'Econgress class: Delegation to classify element id#' . $DelegationRecordId . ' not found.' );
		}
		if ($DelRecord->Author != self::$Member->id) {
			new SysLog ( 'Econgress class: User have not permissions to delete record of delegation to classify #' . $DelegationRecordId );
			throw new Exception ( 'Econgress class: User have not permissions to delete record of delegation to classify #' . $DelegationRecordId );
		}
		$mem = new Members ( );
		$mem->GetByID ( $DelRecord->delegateTo );
		if (! $mem->Selected) {
			new SysLog ( 'Econgress class: Member id#' . $Member . ' not found.' );
			throw new Exception ( 'Econgress class: Member id#' . $Member . ' not found.' );
		}
		
		$mess = sprintf ( LANG_MessageDelegationToClassifyDelete, $NewClass->id, $NewClass->Title, $mem->id, $mem->firstName . ' ' . $mem->lastName );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		new SysMessages ( self::$Member->id, $mem->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		$DelRecord->Delete ();
	}
	static function ClassifyInitiative($Initiative, $Classification) {
		$Class = new Classifications ( );
		$Class->GetByID ( $Classification );
		if (! $Class->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($Class->Deleted) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
		
		}
		if ($Class->Author != self::$Member->id) {
			// Если текущий пользователь не автор классификации, то поищем право классифицировать
			$delegation = new delegationsToClassify ( );
			$delegation->Classification = $Class->Classification;
			$delegation->delegateTo = self::$Member->id;
			$delegation->Find ();
			if (! $delegation->Selected) {
				new SysLog ( 'Econgress class: User have not permissions to to classify by classification #' . $delegation );
				throw new Exception ( 'Econgress class: User have not permissions to to classify by classification #' . $delegation );
			}
		}
		// Проверим инициативу
		$init = new Initiative ( );
		$init->GetByID ( $Initiative );
		if (! $init->Selected) {
			new SysLog ( 'Econgress class: Inittiative #' . $Initiative . ' not found.' );
			throw new Exception ( 'Econgress class: Inittiative #' . $Initiative . ' not found.' );
		}
		if ($init->Deleted) {
			new SysLog ( 'Econgress class: Try to classiffy deleted Inittiative #' . $Initiative );
			throw new Exception ( 'Econgress class: Try to classiffy deleted Inittiative #' . $Initiative );
		}
		if ($init->Closed) {
			new SysLog ( 'Econgress class: Try to classiffy closed Inittiative #' . $Initiative );
			throw new Exception ( 'Econgress class: Try to classiffy closed Inittiative #' . $Initiative );
		}
		// Проверим не классифицирована ли инициатива уже по данной классификации
		$sql = sprintf ( "Select ClassificatedInitiatives.id
From ClassificatedInitiatives Inner Join
  classifications On ClassificatedInitiatives.Classification =
    classifications.id
Where ClassificatedInitiatives.Initiative = %s And
  classifications.Classification = %s", self::qs ( $Initiative ), self::qs ( $Class->Classification ) );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		if (mysql_num_rows ( $res ) > 0) {
			$mess = sprintf ( LANG_MessageInitiativeClassifiedByThisClassificationAlready, $init->id, $init->Title, $Class->id, $Class->Title );
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			return false;
		}
		// Добавим запись классификации инициативы
		$ClassifInits = new ClassificatedInitiatives ( );
		$ClassifInits->Create ();
		$ClassifInits->Classification = $Classification;
		$ClassifInits->Initiative = $Initiative;
		$ClassifInits->Author = self::$Member->id;
		$ClassifInits->Save();
		// Сообщим пользователю об успешности
		$mess = sprintf ( LANG_MessageInitiativeClassified, $init->id, $init->Title, $Class->id, $Class->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function ReClassifyInitiative($Initiative, $Classification) {
		$Class = new Classifications ( );
		$Class->GetByID ( $Classification );
		if (! $Class->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($Class->Deleted) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
		
		}
		if ($Class->Author != self::$Member->id) {
			// Если текущий пользователь не автор классификации, то поищем право классифицировать
			$delegation = new delegationsToClassify ( );
			$delegation->Classification = $Class->Classification;
			$delegation->delegateTo = self::$Member->id;
			$delegation->Find ();
			if (! $delegation->Selected) {
				new SysLog ( 'Econgress class: User have not permissions to to classify by classification #' . $delegation );
				throw new Exception ( 'Econgress class: User have not permissions to to classify by classification #' . $delegation );
			}
		}
		// Проверим инициативу
		$init = new Initiative ( );
		$init->GetByID ( $Initiative );
		if (! $init->Selected) {
			new SysLog ( 'Econgress class: Inittiative #' . $Initiative . ' not found.' );
			throw new Exception ( 'Econgress class: Inittiative #' . $Initiative . ' not found.' );
		}
		if ($init->Deleted) {
			new SysLog ( 'Econgress class: Try to classiffy deleted Inittiative #' . $Initiative );
			throw new Exception ( 'Econgress class: Try to classiffy deleted Inittiative #' . $Initiative );
		}
		if ($init->Closed) {
			new SysLog ( 'Econgress class: Try to classiffy closed Inittiative #' . $Initiative );
			throw new Exception ( 'Econgress class: Try to classiffy closed Inittiative #' . $Initiative );
		}
		// проверим не началось ли голосование
		$Voting = new Voting();
		$Voting->Initiative = $Initiative;
		$Voting->Find();
		if ($Voting->Selected && !$Voting->Deleted && 
		strtotime($Voting->startDate)<=time()) {
			$mess = sprintf ( LANG_MessageInitiativeReClassifiedDeny, $init->id, $init->Title);
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			return false;			
		}
		
		
		// Получим запись классификации для обновления
		$sql = sprintf ( "Select ClassificatedInitiatives.id
From ClassificatedInitiatives Inner Join
  classifications On ClassificatedInitiatives.Classification =
    classifications.id
Where ClassificatedInitiatives.Initiative = %s And
  classifications.Classification = %s", self::qs ( $Initiative ), self::qs ( $Class->Classification ) );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		if (mysql_num_rows ( $res ) > 0) {
			$row = mysql_fetch_assoc($res);
			$ClassifInitsId = $row['id'];
		} else {
			$mess = sprintf ( LANG_InitiativeNopReClass_NotYetClassifyByThisClass, $init->id, $init->Title, $Class->id, $Class->Title );
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			return false;			
		}
		// Добавим запись классификации инициативы
		$ClassifInits = new ClassificatedInitiatives ( );
		$ClassifInits->GetByID($ClassifInitsId);
		$ClassifInits->Classification = $Classification;
		$ClassifInits->Initiative = $Initiative;
		$ClassifInits->Author = self::$Member->id;
		$ClassifInits->Save();
		// Сообщим пользователю об успешности
		$mess = sprintf ( LANG_MessageInitiativeReClassified, $init->id, $init->Title, $Class->id, $Class->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	
	static function GetMemberRating($MemberId) {
		return 1000; // ****************
	}
	static function CreateNewInitiative($Title, $Description, $Location, $Information) {
		$Initiative = new Initiative ( );
		$Initiative->Create ();
		$Initiative->Title = $Title;
		$Initiative->Author = self::$Member->id;
		$Initiative->Description = $Description;
		$Initiative->Location = $Location;
		$Initiative->Information = $Information;
		$Initiative->deadLine = date ( 'c', (strtotime ( $Initiative->addDate ) + timer ( self::$Constants->GetValue ( 'RatingTime' ) )) );
		$Initiative->authorRating = self::GetMemberRating ( self::$Member->id );
		$Initiative->initRating = $Initiative->authorRating;
		$Initiative->Voting = false;
		$Initiative->Closed = false;
		$Initiative->Decision = false;
		$Initiative->Save ();
		
		// Новая инициатива должна иметь запиcь в таблице классификаций
		// т.е. классифицирована как глобальная (как минимум)
		$Clas = new ClassificatedInitiatives();
		$Clas->Create();
		$Clas->Initiative = $Initiative->id;
		$Clas->Classification = 1;
		$Clas->Author = self::$Member->id;
		$Clas->Save();
		
		$Loc = new Locations ( );
		$Loc->GetByID ( $Location );
		if (! $Loc->Selected) {
			new SysLog ( 'Econgress class: Location #' . $Location . ' not found.' );
			throw new Exception ( 'Econgress class: Location #' . $Location . ' not found.' );
		}
		// Проверим относится ли автор инициативы к локейшен инициативы
		if (! $Loc->InTree ( self::$Member->memberLocation )) {
			new SysLog ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Location );
			throw new Exception ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Location );
		}
		$TempTableName = 'temp' . mt_rand ();
		// Инициатива создана теперь необходимо заполнить голоса по ней
		// 1. Создать временную таблицу в памяти
		$sql = "CREATE TABLE {$TempTableName} (id int NOT NULL , level int NOT NULL, delegateBack int NOT NULL,  
		PRIMARY KEY (id)) TYPE = HEAP ROW_FORMAT =DEFAULT";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		// 2. Добавить в таблицу члена сообщества
		$sql = sprintf ( "insert ignore into {$TempTableName} set id=%s, level=1, delegateBack=%s", self::qs ( self::$Member->id ), self::qs ( self::$Member->id ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// 3. Выполнить запрос с добавлением
		$level = 2;
		$sql = "insert IGNORE INTO {$TempTableName}  Select distinct Delegations.delegateFrom  as id, %s, Delegations.delegateTo
			From Delegations Inner Join   (Select {$TempTableName}.id
    		From {$TempTableName} where {$TempTableName}.level=%s) Query1 On Delegations.delegateTo = Query1.id
    		where Delegations.delegateType = 1 and delegations.Deleted<>true and delegations.exclude<>true";
		do {
			$_sql = sprintf ( $sql, $level, $level - 1 );
			$res = mysql_query ( $_sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			$level ++;
			// 4. Проверить увеличилась таблица
		// 5. Если таблица увеличилась, то продолжить с п.3
		} while (  mysql_affected_rows ( self::$DB ) > 0 );
		// 6. Проверить нет ли уже голосов из временной таблицы в таблице рейтинга
		// удалить те который уже есть из временной таблицы.
		$sql = "DELETE {$TempTableName}
			From {$TempTableName} Inner Join
  			InitiativesRatingList On {$TempTableName}.id = InitiativesRatingList.Member
  			where {$TempTableName}.level>InitiativesRatingList.level And
  			InitiativesRatingList.Initiative = {$Initiative->id}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// 7. Удалить голоса членов не входящих в зону действия инициативы
		$sql = sprintf ( "Delete  {$TempTableName}
From {$TempTableName} Inner Join
  Members On {$TempTableName}.id = Members.id
Where Members.memberLocation <> All(Select Locations.id From Locations
  Where Locations.left_key >= %s And Locations.right_key <= %s And Locations.Deleted<>true)", self::qs ( $Loc->left_key ), self::qs ( $Loc->right_key ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Добавить голоса из временной таблицы
		$sql = sprintf ( "insert into InitiativesRatingList (Member,level, Initiative,DeclineByDelegant,
addDate, DelegateBackFrom, Deleted, Author) 
SELECT id, level, %s, false, %s, delegateBack, 'false',
%s from {$TempTableName}", self::qs ( $Initiative->id ), self::qs ( date ( 'c' ) ), self::qs ( self::$Member->id ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

		// Удалить временную таблицу
		$sql = 'Drop table '.$TempTableName;
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Получить текущий рейтинг инициативы и апдейтить.
		$sql = sprintf ( "Select Count(*) As Rating
		From InitiativesRatingList
		Where InitiativesRatingList.Initiative = %s And
  		InitiativesRatingList.declineByDelegant <> true", $Initiative->id );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$res = mysql_fetch_assoc ( $res );
		if ($res == false) {
			die ( "Just add initiative has no any Rating records - NULL result" );
		}
		$Initiative->initRating = $res ['Rating'];
		$Initiative->Save ();
		$mess = sprintf ( LANG_MessageNewInitiativeCreated, $Initiative->id, $Initiative->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function SignInitiative($initId, $Decline) {
		$Initiative = new Initiative ( );
		$Initiative->GetByID ( $initId );
		if (! $Initiative->Selected) {
			new SysLog ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
			throw new Exception ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
		}
		if ($Initiative->Deleted) {
			new SysLog ( 'Econgress class: Try to Sign deleted Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to Sign deleted Inittiative #' . $Initiative->id );
		}
		if ($Initiative->Closed) {
			new SysLog ( 'Econgress class: Try to Sign closed Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to Sign closed Inittiative #' . $Initiative->id );
		}
		$Loc = new Locations ( );
		$Loc->GetByID ( $Initiative->Location );
		if (! $Loc->Selected) {
			new SysLog ( 'Econgress class: Initiative Location #' . $Initiative->Location . ' not found.' );
			throw new Exception ( 'Econgress class: Initiative Location #' . $Initiative->Location . ' not found.' );
		}
		// Проверим относится ли автор инициативы к локейшен инициативы
		if (! $Loc->InTree ( self::$Member->memberLocation )) {
			new SysLog ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Loc->id );
			throw new Exception ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Loc->id );
		}
		
		$TempTableName = 'temp' . mt_rand ();
		// Необходимо заполнить голоса по инициативе
		// 1. Создать временную таблицу в памяти
		$sql = "CREATE TABLE {$TempTableName} (id int NOT NULL , level int NOT NULL, delegateBack int NOT NULL,  
		PRIMARY KEY (id)) TYPE = HEAP ROW_FORMAT =DEFAULT";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		// 2. Добавить в таблицу члена сообщества
		$sql = sprintf ( "insert ignore into {$TempTableName} set id=%s, level=1, delegateBack=%s", self::qs ( self::$Member->id ), self::qs ( self::$Member->id ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		// 3. Выполнить запрос с добавлением
		$level = 2;
		$sql = "insert IGNORE INTO {$TempTableName}  Select distinct Delegations.delegateFrom  as id, %s, Delegations.delegateTo
			From Delegations Inner Join   (Select {$TempTableName}.id
    		From {$TempTableName} where {$TempTableName}.level=%s) Query1 On Delegations.delegateTo = Query1.id
    		where Delegations.delegateType = 1 and delegations.Deleted<>true and delegations.exclude<>true";
		do {
			$_sql = sprintf ( $sql, $level, $level - 1 );
			$res = mysql_query ( $_sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			$level ++;
			// 4. Проверить увеличилась таблица
		// 5. Если таблица увеличилась, то продолжить с п.3
		} while ( mysql_affected_rows ( self::$DB ) > 0 );
		// 6. Проверить нет ли уже голосов из временной таблицы в таблице рейтинга
		// удалить те который уже есть из временной таблицы.
		$sql = "DELETE {$TempTableName}
			From {$TempTableName} Inner Join
  			InitiativesRatingList On {$TempTableName}.id = InitiativesRatingList.Member
  			where {$TempTableName}.level>InitiativesRatingList.level And
  			InitiativesRatingList.Initiative = {$Initiative->id}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// 7. Удалить голоса членов не входящих в зону действия инициативы
		$sql = sprintf ( "Delete  {$TempTableName}
		From {$TempTableName} Inner Join
 	 	Members On {$TempTableName}.id = Members.id
		Where Members.memberLocation <> All(Select Locations.id From Locations
  		Where Locations.left_key >= %s And Locations.right_key <= %s And Locations.Deleted<>true)", self::qs ( $Loc->left_key ), self::qs ( $Loc->right_key ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Добавить голоса из временной таблицы
		$sql = sprintf ( "replace into InitiativesRatingList (Member,level, Initiative,DeclineByDelegant,
addDate, DelegateBackFrom, Deleted, Author) 
SELECT id, level, %s, %s, %s, delegateBack, 'false',
%s from {$TempTableName}", self::qs ( $Initiative->id ), $Decline ? 'true' : 'false', self::qs ( date ( 'c' ) ), self::qs ( self::$Member->id ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

		// Удалить временную таблицу
		$sql = 'Drop table '.$TempTableName;
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Получить текущий рейтинг инициативы и апдейтить.
		$sql = sprintf ( "Select Count(*) As Rating
		From InitiativesRatingList
		Where InitiativesRatingList.Initiative = %s And
  		InitiativesRatingList.declineByDelegant <> true", $Initiative->id );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$res = mysql_fetch_assoc ( $res );
		if ($res == false) {
			die ( "Just add initiative has no any Rating records - NULL result" );
		}
		$Initiative->initRating = $res ['Rating'];
		$Initiative->Save ();
		$mess = sprintf ( LANG_MessageInitiativeSign, $Initiative->id, $Initiative->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function ToVote($initId, $ProAndCon) {
		$Initiative = new Initiative ( );
		$Initiative->GetByID ( $initId );
		if (! $Initiative->Selected) {
			new SysLog ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
			throw new Exception ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
		}
		if ($Initiative->Deleted) {
			new SysLog ( 'Econgress class: Try to Vote deleted Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to Vote deleted Inittiative #' . $Initiative->id );
		}
		if ($Initiative->Closed) {
			new SysLog ( 'Econgress class: Try to Vote closed Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to Vote closed Inittiative #' . $Initiative->id );
		}
		if (! $Initiative->Voting) {
			new SysLog ( 'Econgress class: Voting not open for Initiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Voting not open for Inittiative #' . $Initiative->id );
		}
		$Loc = new Locations ( );
		$Loc->GetByID ( $Initiative->Location );
		if (! $Loc->Selected) {
			new SysLog ( 'Econgress class: Initiative Location #' . $Initiative->Location . ' not found.' );
			throw new Exception ( 'Econgress class: Initiative Location #' . $Initiative->Location . ' not found.' );
		}
		// Проверим относится ли автор инициативы к локейшен инициативы
		if (! $Loc->InTree ( self::$Member->memberLocation )) {
			new SysLog ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Loc->id );
			throw new Exception ( 'Econgress class: Member #' . self::$Member->id . ' is not in Initiative Location #' . $Loc->id );
		}
		// Проверим началось ли и не окончено ли голосование
		$Voting = new Voting ( );
		$Voting->Initiative = $Initiative->id;
		$Voting->Find ();
		if (! $Voting->Selected) {
			new SysLog ( 'Econgress class: Voting record to Inittiative #' . $Initiative->id . ' not found.' );
			throw new Exception ( 'Econgress class: Voting record to Inittiative #' . $Initiative->id . ' not found.' );
		}
		if (strtotime ( $Voting->addDate ) > time () || strtotime ( $Voting->deadLine ) < time ()) {
			new SysLog ( 'Econgress class: Wrong time to vote for Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Wrong time to vote for Inittiative #' . $Initiative->id );
		}
		
		$TempTableName = 'temp' . mt_rand ();
		// Необходимо заполнить голоса по инициативе
		// 1. Создать временную таблицу в памяти
		$sql = "CREATE TABLE {$TempTableName} 
		(id int NOT NULL , level int NOT NULL , delegateBack int NOT NULL, classification int NOT NULL, 
		PRIMARY KEY (id)) TYPE = HEAP ROW_FORMAT =DEFAULT";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		// 2. Добавить в таблицу члена сообщества
		$sql = sprintf ( "insert ignore into {$TempTableName} set id=%s, level=1, delegateBack=%s, classification=1", self::qs ( self::$Member->id ), self::qs ( self::$Member->id ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		// 3. Выполнить запрос с добавлением
		// Создадим таблицу стрел классификации
		$TempClassificTable = 'temp' . mt_rand ();
		$Initiative->CreateTempTableCCI ( $TempClassificTable );
		$level = 2;
		// Запрос добавляет id членов и уровень во временную таблицу от текущего уровня, 
		// При этом в таблицу попадают только члены которые делегировали и данная инициатива подпадает 
		//под классифицированную по делегированной и не содержится в исключаемой   
		$sql = "insert ignore into {$TempTableName} select id, %s as level, delegateBack, classification 
		from (Select Delegations.delegateFrom as id, max(Delegations.Exclude) as exclude, 
		max(Delegations.delegateTo) as delegateBack, max(Delegations.Classification) as classification
From Delegations Inner Join
  (Select {$TempTableName}.id
    From {$TempTableName}
    Where {$TempTableName}.level = '%s') Query1 On Delegations.delegateTo =
    Query1.id
Where Delegations.Classification In (Select *
  From {$TempClassificTable})  And Delegations.delegateType = 1 and delegations.Deleted<>true
  group by id) as Query1 
  where Query1.exclude<>true";
		do {
			$_sql = sprintf ( $sql, $level, $level - 1 );
			$res = mysql_query ( $_sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			$level ++;
			// 4. Проверить увеличилась таблица
		// 5. Если таблица увеличилась, то продолжить с п.3
		} while ( mysql_affected_rows ( Econgress::$DB ) > 0 );
		
		// 6. Проверить нет ли уже голосов из временной таблицы в таблице голосов
		// удалить те который уже есть из временной таблицы.
		$sql = "DELETE {$TempTableName}
			From {$TempTableName} Inner Join
  			Votes On {$TempTableName}.id = Votes.Member
  			where {$TempTableName}.level>Votes.level And
  			Votes.Initiative = {$Initiative->id}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// 7. Удалить голоса членов не входящих в зону действия инициативы
		$sql = sprintf ( "Delete  {$TempTableName}
		From {$TempTableName} Inner Join
 	 	Members On {$TempTableName}.id = Members.id
		Where Members.memberLocation <> All(Select Locations.id From Locations
  		Where Locations.left_key >= %s And Locations.right_key <= %s And Locations.Deleted<>true)", self::qs ( $Loc->left_key ), self::qs ( $Loc->right_key ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Добавить голоса из временной таблицы
		$sql = sprintf ( "replace into Votes (Member,level, Initiative,Classification,
addDate, DelegateBackFrom, Deleted, Author, ProAndCon, Passive) 
SELECT id, level, %s, classification, %s, delegateBack, 'false',
%s, %s, 'false' from {$TempTableName}", self::qs ( $Initiative->id ), self::qs ( date ( 'c' ) ), 
self::qs ( self::$Member->id ), $ProAndCon ? 'true' : 'false' );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

		// Удалить временную таблицу
		$sql = 'Drop table '.$TempTableName;
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Получить текущий рейтинг инициативы и апдейтить.
		$sql = sprintf ( "Select count(if(Votes.ProAndCon=false,1,NULL)) As Con, 
count(if(Votes.ProAndCon=true,1,NULL)) As Pro 
        From Votes
        Where Votes.Initiative = %s and Votes.Deleted<>true", self::qs ( $Initiative->id ) );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$res = mysql_fetch_assoc ( $res );
		if ($res == false) {
			die ( "Just add initiative has no any Voting records - NULL result" );
		}
		
		$Voting->votingRating = $res ['Pro'] - $res ['Con'];
		$Voting->Pro = $res ['Pro'];
		$Voting->Con = $res ['Con'];
		$Voting->Save ();
		$mess = sprintf ( LANG_MessageInitiativeVote, $Initiative->id, $Initiative->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
	
	}
	static function ToDelegate($ToMemberId, $ClassificationId, $Exclude, $DelegateType) {
		
		$toMem = new Members ( );
		$toMem->GetByID ( $ToMemberId );
		if (! $toMem->Selected) {
			new SysLog ( "Econgress class: Memeber #{$ToMemberId} To delegate not found." );
			throw new Exception ( "Econgress class: Memeber #{$ToMemberId} To delegate not found." );
		}
		if ($toMem->Deleted) {
			new SysLog ( "Econgress class: Memeber #{$ToMemberId} not exist (deleted)." );
			throw new Exception ( "Econgress class: Memeber #{$ToMemberId} not exist (deleted)." );
		}
		$Class = new Classifications ( );
		$Class->GetByID ( $ClassificationId );
		if (! $Class->Selected) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not found.' );
		}
		if ($Class->Deleted) {
			new SysLog ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
			throw new Exception ( 'Econgress class: Classification element id#' . $Classification . ' not exist (deleted).' );
		
		}
		// Необходимо исключить пересекающееся внутри одной классификации делегирования по 
		// следующим правилам:
		

		// 1. Вышестоящим может быть только пассивное делегирование над актиным
		// 2. Другое пересекающееся в рамках одной классификации запрещено (не имеет смысла)
		$sql = sprintf ( "Select Delegations.id As DelegElem, 'up' As Direction, 
       Delegations.DelegateType As DelegateType, 
       Delegations.Exclude As exclude
From   Classifications As cl1, 
       Classifications As cl2, 
       Delegations
Where Delegations.delegateFrom = %s 
        And Delegations.delegateTo = %s 
        And Delegations.Classification = cl1.id 
        And cl2.left_key <= cl1.left_key
        And cl2.right_key >= cl1.right_key 
        And cl2.id = %s 
        And cl1.Deleted <> true 
        And cl2.Deleted <> true 
        And Delegations.Deleted <> true
union
Select Delegations.id As DelegElem, 'down' As Direction,
       Delegations.DelegateType As DelegateType, 
       Delegations.Exclude As exclude
From   Classifications As cl1, 
       Classifications As cl2, 
       Delegations
Where Delegations.delegateFrom = %s 
       And Delegations.delegateTo = %s 
       And Delegations.Classification = cl1.id 
       And cl2.left_key > cl1.left_key 
       And cl2.right_key < cl1.right_key 
       And cl2.id = %s 
       And cl1.Deleted <> true
       And cl2.Deleted <> true 
       And Delegations.Deleted <> true", self::qs ( self::$Member->id ), self::qs ( $ToMember ), self::qs ( $ClassificationId ), self::qs ( self::$Member->id ), self::qs ( $ToMember ), self::qs ( $ClassificationId ) );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$Decline = false;
		while ( $row = mysql_fetch_assoc ( $res ) ) {
			if ($row ['Direction'] == 'up' && $row ['DelegateType'] <= $DelegateType && $row ['Exclude'] && ! $Exclude) {
				// Элемент классификации нового делегирования пересекается находясь выше
				// существующего (по дереву классификации) и имеет тип делегирования не выше
				// существующего (т.е. не соблюдается правило, что вышестоящая должна быть 
				// пассивной когда как нижестоящая активная), а также не тот случайБ когда
				// вышестояшая делегирующая а нижестоящая исключающая.
				$Decline = true;
				$mess = sprintf ( LANG_MessageDelegationCrossoverAbove, $row [DelegElem] );
				new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			}
			if ($row ['Direction'] == down && $row ['DelegateType'] >= $DelegateType && ! $row ['Exclude'] && $Exclude) {
				// Элемент классификации нового делегирования пересекается находясь ниже
				// существующего (по дереву классификации) и имеет тип делегирования не ниже
				// существующего (т.е. не соблюдается правило, что вышестоящая должна быть 
				// пассивной когда как нижестоящая активная), а также не тот случай, когда
				// вышестояшая делегирующая а нижестоящая исключающая.
				$Decline = true;
				$mess = sprintf ( LANG_MessageDelegationCrossoverBelow, $row [DelegElem] );
				new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			}
		}
		if ($Decline) {
			return false;
		}
		// Всё проверили можно создать новое делегирование.
		$NewDelegation = new Delegations ( );
		$NewDelegation->Create ();
		$NewDelegation->delegateFrom = self::$Member->id;
		$NewDelegation->delegateTo = $ToMemberId;
		$NewDelegation->delegateType = $DelegateType;
		$NewDelegation->Classification = $ClassificationId;
		$NewDelegation->Exclude = $Exclude;
		$NewDelegation->Author = self::$Member->id;
		$NewDelegation->Save ();
		$mess = sprintf ( LANG_MessageNewDelegationCreated, $toMem->id, $toMem->firstName . ' ' . $toMem->lastName . ' (' . $toMem->userName . ')', $Class->id, $Class->Title, $Exclude ? LANG_Exclude : LANG_NonExclude );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function RemoveDelegation($DelegationID) {
		$Delegation = new Delegations ( );
		$Delegation->GetByID ( $DelegationID );
		if (! $Delegation->Selected) {
			new SysLog ( "Econgress class: Delegatin #{$DelegationID} not found." );
			throw new Exception ( "Econgress class: Delegatin #{$DelegationID} not found." );
		}
		if ($Delegation->Deleted) {
			new SysLog ( "Econgress class: Delegation #{$Delegation->id} not exist (deleted)." );
			throw new Exception ( "Econgress class: Delegation #{$Delegation->id} not exist (deleted)." );
		}
		if ($Delegation->delegateFrom != self::$Member->id) {
			new SysLog ( "Econgress class: Delegation #{$Delegation->id} can not be removed because it from another member #" . $Delegation->delegateFrom . "." );
			throw new Exception ("Econgress class: Delegation #{$Delegation->id} can not be removed because it from another member #" . $Delegation->delegateFrom . "."  );
		}
		$Class = new Classifications ( );
		$Class->GetByID ( $Delegation->Classification );
		$toMem = new Members ( );
		$toMem->GetByID ( $Delegation->delegateTo );
		$Exclude = $Delegation->Exclude;
		$Delegation->Delete ();
		$mess = sprintf ( LANG_MessageDelegationDeleted, $toMem->id, $toMem->firstName . ' ' . $toMem->lastName . ' (' . $toMem->userName . ')', $Class->id, $Class->Title, $Exclude ? LANG_Exclude : LANG_NonExclude );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		
		return true;
	}
	static function ReDelegate($DelegationID, $ToMemberId, $ClassificationId, $Exclude, $DelegateType) {
		try {
			self::RemoveDelegation ( $DelegationID );
		} catch ( Exception $Error ) {
			return false;
		}
		try {
			self::ToDelegate ( $ToMemberId, $ClassificationId, $Exclude, $DelegateType );
		} catch ( Exception $Error ) {
			$Delegation = new Delegations ( );
			$Delegation->GetByID ( $DelegationID );
			$Delegation->Deleted = false;
			$Delegation->Save ();
			return false;
		}
		$toMem = new Members ( );
		$toMem->GetByID ( $ToMemberId );
		$Class = new Classifications ( );
		$Class->GetByID ( $ClassificationId );
		$mess = sprintf ( LANG_MessageDelegationDeleted, $toMem->id, $toMem->firstName . ' ' . $toMem->lastName . ' (' . $toMem->userName . ')', $Class->id, $Class->Title, $Exclude ? LANG_Exclude : LANG_NonExclude );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	static function BlockUser($MemberID, $Blocked) {
		$Member = new Members ( );
		$Member->GetByID ( $MemberID );
		if (! $Member->Selected) {
			new SysLog ( "Econgress class: Memeber #{$ToMemberId} To delegate not found." );
			throw new Exception ( "Econgress class: Memeber #{$ToMemberId} To delegate not found." );
		}
		$Member->Blocked = $Blocked;
		$Member->Save ();
		$mess = sprintf ( LANG_MessageMemberBlockedUnblocked, $Member->id, $Member->firstName . ' ' . $Member->lastName . ' (' . $Member->userName . ')', $Blocked ? LANG_Blocked : LANG_Unblocked );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;
	}
	
	//************************************************************
	// Обработка этапа сбора подписей
	// инициатива либо ставиться на голосование либо закрывается
	static function AutoInitiativesRate() {
		
		// Получить константу расчета количества голосов из численность
		$MinRatingPercent = self::$Constants->GetValue('MinRatingPercent');
		
		// Расчитать численность членов для каждого элемента локации и записать
		// таблицу LocationId/Count во временную таблицу
		

		$sql = "
select Initiatives.id AS InitId, Query1.cc as MembersNum, 
       Query1.id as LocationId, ifnull(Query2.Rating,0) as Rating
from Initiatives
left join 
    (Select  lc1.id, Count(Members.id) as cc
     From Locations As lc1, Locations As lc2
     left join Members on lc2.id=Members.memberLocation
     Where lc2.left_key >= lc1.left_key And lc2.right_key <= lc1.right_key And
           lc1.Deleted<>true and lc2.Deleted<>true 
           and ((Members.Deleted<>true and Members.Blocked=false) or ISNULL(Members.id)) 
     Group By lc1.id) Query1 
on Initiatives.Location = Query1.id

left join 
    (Select Init.id as id, Count(InitiativesRatingList.id) as Rating
     From InitiativesRatingList 
     Inner Join
          Initiatives as Init 
     On InitiativesRatingList.Initiative = Init.id
     Where InitiativesRatingList.declineByDelegant <> true And
           InitiativesRatingList.Deleted <> true
     Group By Init.id) as Query2 
on Query2.id = Initiatives.id
Where Initiatives.Closed<>true and Initiatives.Voting<>true and Initiatives.deadLine<CURDate()
ORDER By Initiatives.deadLine";
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		while ( $row = mysql_fetch_assoc ( $res ) ) {
			if ($row ['Rating'] < ($row ['MembersNum'] / 100 * $MinRatingPercent)) {
				// Инициатива не набрала необходимое количество подписей для постановки на голосование
				// Закрыть инициативу
				$Initiative = new Initiative ( );
				$Initiative->GetByID ( $row ['InitId'] );
				$Initiative->Closed = true;
				$Initiative->Save ();
			} else {
				// Инициатива набрала достаточное количество голосов для постановки на голосование
				// Создаем запись голосования
				$ClassificationDeadLine = time () + timer ( self::$Constants->GetValue('ClassificationTime') );
				$Voting = new Voting ( );
				$Voting->Create ();
				$Voting->Initiative = $row ['InitId'];
				$Voting->votingRating = $row ['Rating'];
				$Voting->Author = 1;
				$Voting->startDate = date ( 'c', $ClassificationDeadLine );
				$Voting->Save ();
				// Отправим сообщения авторам и делегатам по классификации
				$mess = sprintf ( LANG_MessageClassifyNewInitiativeByYourClassification, date ( 'r', $ClassificationDeadLine ) );
				$sql = sprintf ( "insert SysMessages (messTo, Message, Type, Author, addDate, Deleted) 
select Member,CONCAT(%s, Classification), 0, 1, curdate(), 'false'     
from (Select classifications.id as Classification, classifications.Author as Member
From classifications
Where classifications.Classification = Classifications.id
Union
Select delegationsToClassify.Classification, delegationsToClassify.delegateTo
From delegationsToClassify) as Query1", $mess );
				mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			}
		
		}
		// Для инициатив поставленных на голосование отправить авторам классификаций
	// и членам имеющим право классифицировать сообщение с указанием инициативы
	// и срока до которого она должны быть классифицирована 
	// При начале голосования, если инициатива не классифицирована, то отправлять
	// атору предупреждение. Считать количество инициатив не классифицированных по классификации
	// для рейтинга классификаций.
	

	}
	static function AutoPassiveVoting() {
		// Выбрать инициативы для закрытия голосования
		// Для каждой инициативы
		// Выбрать всех прголосовавших членов по которым есть пассивное делегирование.
		// Для каждого проголосовать по делегированию. (см. функцию голосования)
		$sql = "Select Votes.Initiative, Votes.Member, Votes.ProAndCon
                  From Votes, Initiatives, Voting 
                Where Initiatives.Closed <> true And 
                        Initiatives.Deleted <> true And
                        Initiatives.Decision <> true And      
                        Initiatives.Voting = True And      
                        Voting.Deleted <> true and       
                        Voting.Initiative = Initiatives.id and      
                        Initiatives.id = Votes.Initiative 
				Order by Votes.Initiative";
		$PassiveVotes = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$CurrentInitiative = false;
		while ( $Vote = mysql_fetch_assoc ( $PassiveVotes ) ) {
			if ($CurrentInitiative!=$Vote['Initiative']) {
				// Если перешли к следующей инициативе, то для предыдущей можно 
				// подвести итоги голосования. 
				if ($CurrentInitiative!=false) {
					try {
						self::CheckAndCloseVoting($CurrentInitiative);
					} catch (Exception $Error) {
						new SysLog($Error->getMessage());
					} 
				}
				$CurrentInitiative = $Vote['Initiative'];
			}
			try {
				self::ToVotePassive($Vote['Initiative'], $Vote['Member'], $Vote['ProAndCon']);
			} catch (Exception $Error) {
				new SysLog($Error->getMessage());
			}
			
		}
		
		if ($CurrentInitiative!=false) {
		//Подведем итоги последней инициативы (если была хоть одна)			
			try {
				self::CheckAndCloseVoting($CurrentInitiative);
			} catch (Exception $Error) {
				new SysLog($Error->getMessage());
			} 
		}
	}

	static function ToVotePassive($InitId, $FromMember, $ProAndCon) {
		$Initiative = new Initiative ( );
		$Initiative->GetByID ( $initId );
		
		$TempTableName = 'temp' . mt_rand ();
		// Необходимо заполнить голоса по инициативе

// 1. Создать временную таблицу в памяти
		$sql = "CREATE TEMPORARY TABLE {$TempTableName} 
		(id int NOT NULL , level int NOT NULL , delegateBack int NOT NULL, classification int NOT NULL 
		PRIMARY KEY (id)) TYPE = HEAP ROW_FORMAT =DEFAULT";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

// 2. Добавить в таблицу члена сообщества
		$sql = sprintf ( "insert ignore into {$TempTableName} set id=%s, level=1, delegateBack=%s, classification=1", 
		self::qs ( $FromMember ), self::qs ( $FromMember ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

// 3. Выполнить запрос с добавлением
// Создадим таблицу стрел классификации
		$TempClassificTable = 'temp' . mt_rand ();
		$Initiative->CreateTempTableCCI ( $TempClassificTable );
		$level = 2;
		// Запрос добавляет id членов и уровень во временную таблицу от текущего уровня, 
		// При этом в таблицу попадают только члены которые делегировали и данная инициатива подпадает 
		//под классифицированную по делегированной и не содержится в исключаемой   
		$sql = "
		insert ignore into {$TempTableName} 
			select id, %s as level, delegateBack, classification 
			from 
				(Select Delegations.delegateFrom as id, max(Delegations.Exclude) as exclude, 
						max(Delegations.delegateTo) as delegateBack, max(Delegations.Classification) as classification
				 From Delegations Inner Join
  					(Select {$TempTableName}.id
    				 From {$TempTableName}
    				 Where {$TempTableName}.level = '%s') Query1 
    			 On Delegations.delegateTo = Query1.id
			     Where 
			      	Delegations.Classification In (Select * From {$TempClassificTable})  
			  	And Delegations.delegateType = 2 
			  	and delegations.Deleted<>true
  				group by id) as Query1 
         	where Query1.exclude<>true";
		do {
			$_sql = sprintf ( $sql, $level, $level - 1 );
			$res = mysql_query ( $_sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			$level ++;
			// 4. Проверить увеличилась таблица
		// 5. Если таблица увеличилась, то продолжить с п.3
		} while ( mysql_affected_rows ( $res ) > 0 );
		
		// 6. Проверить нет ли уже голосов из временной таблицы в таблице рейтинга
		// удалить те который уже есть из временной таблицы.
		$sql = "DELETE {$TempTableName}
			From {$TempTableName} Inner Join
  			Votes On {$TempTableName}.id = Votes.Member
  			where {$TempTableName}.level>Votes.level 
  				  and Votes.Passive = true
  				  And Votes.Initiative = {$Initiative->id}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// 7. Удалить голоса членов не входящих в зону действия инициативы
		$sql = sprintf ( "Delete  {$TempTableName}
		From {$TempTableName} Inner Join
 	 	Members On {$TempTableName}.id = Members.id
		Where Members.memberLocation <> All(Select Locations.id From Locations
  		Where Locations.left_key >= %s And Locations.right_key <= %s And Locations.Deleted<>true)", 
  		self::qs ( $Loc->left_key ), self::qs ( $Loc->right_key ) );
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		
		// Добавить голоса из временной таблицы
		$sql = sprintf ( "insert into Votes (Member,level, Initiative,Classification,
addDate, DelegateBackFrom, Deleted, Author, ProAndCon, Passive) 
SELECT id, level, %s, classification, %s, delegateBack, 'false',
%s, %s, 'true' from {$TempTableName}", self::qs ( $Initiative->id ), self::qs ( date ( 'c' ) ), 
self::qs ( $FromMember ), $ProAndCon ? 'true' : 'false');
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );

		
		
		// Удалим временные таблицы
		$sql = "drop table {$TempTableName}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$sql = "drop table {$TempClassificTable}";
		mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
	}
	
	static function CheckAndCloseVoting($InitId) {
		// Получить текущий рейтинг инициативы и апдейтить.
		$sql = sprintf ( "
select Initiatives.id AS InitId, Query1.cc as MembersNum, 
       ifnull(Query2.Pro,0) as RatingPro, 
       ifnull(Query2.Con,0) as RatingCon
from Initiatives
left join 
    (Select  lc1.id, Count(Members.id) as cc
     From Locations As lc1, Locations As lc2
     left join Members on lc2.id=Members.memberLocation
     Where lc2.left_key >= lc1.left_key And lc2.right_key <= lc1.right_key And
           lc1.Deleted<>true and lc2.Deleted<>true 
           and ((Members.Deleted<>true and Members.Blocked=false) or ISNULL(Members.id)) 
     Group By lc1.id) Query1 
on Initiatives.Location = Query1.id

left join 
    (Select Votes.Initiative as id, count(if(Votes.ProAndCon=false,1,NULL)) As Con, 
count(if(Votes.ProAndCon=true,1,NULL)) As Pro 
        From Votes  
        Where Votes.Initiative = %s and Votes.Deleted<>true
        Group by Votes.Initiative) as Query2 
on Query2.id = Initiatives.id
WHERE Initiatives.id = %s", self::qs ( $InitId), self::qs ( $InitId) );
		$res = mysql_query ( $sql ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		$res = mysql_fetch_assoc ( $res );
		if ($res == false) {
			die ( "Just add initiative has no any Voting records - NULL result" );
		}
		
		if ($res ['RatingPro'] + $res ['RatingCon']>=
		   		($res['MembersNum']*self::$Constants->GetValue('MinVotingsMembersPercent')/100)) {
			// голосование действительно
		   	$voidVoting = false;
		} else {
			// голосование не действительно (мало проголосовало)
			$voidVoting = true;	
		}
		$Voting = new Voting ( );
		$Voting->Initiative = $Initiative->id;
		$Voting->Find ();
		$Voting->votingRating = $res ['RatingPro'] - $res ['RatingCon'];
		$Voting->Pro = $res ['RatingPro'];
		$Voting->Con = $res ['RatingCon'];
		$Voting->voidVoting = $voidVoting;
		$Voting->Save ();
		// Принятие решения
		if ($res['RatingPro']>$res['RatingCon'] && !$voidVoting) {
			$Decision = new Decisions();
			$Decision->Create();
			$Decision->Initiative = $InitId;
			$Decision->votesPro = $res['RatingPro'];
			$Decision->votesCon = $res['RatingCon'];
			$Decision->Save();
		}
		$Initiative = new Initiative();
		$Initiative->GetByID($InitId);
		$Initiative->Closed = true;
		$Initiative->Save();
	}
	static function ChangeInitInform($initID,$inform){
		$Initiative = new Initiative ( );
		$Initiative->GetByID ( $initID );
		if (! $Initiative->Selected) {
			new SysLog ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
			throw new Exception ( 'Econgress class: Inittiative #' . $Initiative->id . ' not found.' );
		}
		if ($Initiative->Author!=self::$Member->id) {
			new SysLog ( 'Econgress class: Not Author try to change Information in Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Not Author try to change Information in Inittiative #' . $Initiative->id);
		}
		
		if ($Initiative->Deleted) {
			new SysLog ( 'Econgress class: Try to change Information in deleted Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to change Information in deleted Inittiative #' . $Initiative->id);
		}
		if ($Initiative->Closed) {
			new SysLog ( 'Econgress class: Try to change Information in closed Inittiative #' . $Initiative->id );
			throw new Exception ( 'Econgress class: Try to change Information in closed Inittiative #' . $Initiative->id );
		}
		$Initiative->Information = substr($inform,0,65535);
		$Initiative->Save();
		$mess = sprintf ( LANG_InitiativeAdditionalInformationChanged, $Initiative->id, $Initiative->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;		
	}

	static function ChangeClassInform($classID,$inform){
		$Classification = new Classifications();
		$Classification->GetByID ( $classID );
		if (! $Classification->Selected) {
			new SysLog ( 'Econgress class: Classification #' . $classID . ' not found.' );
			throw new Exception ( 'Econgress class: Classification #' . $classID . ' not found.' );
		}
		if ($Classification->Author!=self::$Member->id) {
			new SysLog ( 'Econgress class: Not Author try to change Information in Classification #' . $Classification->id );
			throw new Exception ( 'Econgress class: Not Author try to change Information in Classification #' . $Classification->id );
		}
		
		if ($Classification->Deleted) {
			new SysLog ( 'Econgress class: Try to change Information in deleted Classification #' . $Classification->id );
			throw new Exception ( 'Econgress class: Try to change Information in deleted Classification #' . $Classification->id );
		}
		$Classification->Information = substr($inform,0,65535);
		$Classification->Save();
		$mess = sprintf ( LANG_ClassificationAdditionalInformationChanged, $Classification->id, $Classification->Title );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;		
	}
	static function ChangeUserData($userID, $firstName, $lastName, $Email, $Language) {
		$Member = new Members();
		$Member->GetByID($userID);
		if (! $Member->Selected) {
			new SysLog ( 'Econgress class: Member #' . $userID . ' not found.' );
			throw new Exception ( 'Econgress class: Member #' . $userID . ' not found.' );
		}
		$Member->firstName = $firstName;
		$Member->lastName = $lastName;
		$Member->Email = $Email;
		$Member->Language = $Language;
		$Member->Save();
		$mess = sprintf ( LANG_UserProfileChanged, $Member->id, $Member->userName );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;		
	}
    static function NewUser($userName, $userPass, $userPass2, $firstName, $lastName, $Email, $Language) {
		if (!self::$Member->IsSuperAdmin()) {
			new SysLog ( 'Econgress class: User have not permissions to add new users' );
			throw new Exception (  'Econgress class: User have not permissions to add new users' );
		}
    	if ($userPass!=$userPass2) {
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, LANG_NewPasswordsNotMatch );
			throw new Exception ( LANG_NewPasswordsNotMatch );
    	}
    	
    	$Member = new Members();
    	$Member->userName = $userName;
    	$Member->Find();
    	if ($Member->Selected) {
    		$mess = sprintf(LANG_UserNameExistsAlready,$userName,$Member->id);
			new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
			throw new Exception ( $mess );
     	}
    	$Member->Create();
    	$Member->userName = $userName;
    	$Member->Password = $userPass;
    	$Member->firstName = $firstName;
    	$Member->lastName = $lastName;
    	$Member->Email = $Email;
    	$Member->Language = $Language;
    	$Member->Save();
    	$mess = sprintf ( LANG_NewUserCreated, $Member->id, $Member->userName, $Member->firstName.' '.$Member->lastName );
		new SysMessages ( 1, self::$Member->id, SYSMESSAGE_TYPE_NORMAL, $mess );
		return true;		
    }
}

?>