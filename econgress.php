<?php
/**
 * Стартовый файл системы принятия решений
 *
 * @author Vladislav Kosilov <kosilov@inbox.ru>
 * @version $Id$
 * @package E-congress
 */
require_once 'econgres_global.php';
// Начать буфферизацию потока
ob_start("ob_gzhandler",9);
ob_start("ob_linearize");

require_once ('Econgress/econgress.php');
require_once 'Tables/Tables.php';
require_once 'Tables/forms.php';
require_once 'econgress_config.php';

require_once 'Econgress/ModalBox.php';

Econgress::$hostName = DB_HOSTNAME;
Econgress::$DBuserName = DB_USERNAME;
Econgress::$DBpassword = DB_PASSWORD;
Econgress::$DbaseName = DB_DATABASE;
Econgress::Init ();
// Получим стандартные параметры
$GURL = new URLparams ( );
$GURL->LoadCurrent ();


// Подготовим заголовок страницы

$pageHeader = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/global.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/modalbox/lib/prototype.js"></script>
<script type="text/javascript" src="js/modalbox/lib/scriptaculous.js"></script>
<script type="text/javascript" src="js/modalbox/modalbox.js"></script>
<link rel="stylesheet" href="js/modalbox/modalbox.css" type="text/css" media="screen" />
<title></title>
</head>
<body>';
$pageBottom = '</body></html>';

// Проверка зарегистрирован ли пользователь
if (! isset ( $_SESSION ['uid'] )) {
	// Пользователь не зарегистрирован
	// отправляем на регистрацию
	echo $pageHeader;
	include 'Login.php';
	echo $pageBottom;
	die ();
}

switch ($GURL->action) {
	case 0 :
	case '' :
		$actionResult = true;
		$GURL->action ='';
		break;
	case 102:
		try {
			$actionResult = Econgress::LogOut();
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 104 :
		$oldPass = var_get('oldPass');
		$newPass = var_get('newPass');
		$newPass2 = var_get('newPass2');
		try {
			$actionResult = Econgress::ChangeMemberPasswordWithCheck($oldPass,$newPass,$newPass2);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;						
	case 106:
		// Перепрописка пользователя (не адиминистратор)
		$id = var_get('id');
		try {
			$actionResult = Econgress::ChangeMemberLocation(Econgress::$Member->id,$id,false);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;				
	case 107:
		// Изменение пользовательских данных
		$firstName = var_get('firstName');
		$lastName = var_get('lastName');
		$Email = var_get('Email');
		$Language = var_get('Language');
		try {
			$actionResult = Econgress::ChangeUserData(Econgress::$Member->id, $firstName, $lastName, $Email,$Language);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
		
	case 201:
		// Добавление новой классификации
		$Title = var_get('Title');
		$Description = var_get('Description');
		$Information = var_get('Information');
		try {
			$actionResult = Econgress::AddNewClassification($Title,$Description,$Information);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
		
	case 202:
		// Добавление элемента классификации
		$id = var_get('id');
		$Title = var_get('Title');
		$Description = var_get('Description');
		$Information = var_get('Information');
		try {
			$actionResult = Econgress::AddNewClassificationElement($id,$Title,$Description,$Information);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
	case 203:
	case 204:
		$Classification = var_get('id');
		try {
			$actionResult = Econgress::DeleteClassification($Classification);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 205:
		// Делегирование права классифицировать
		$toMember = var_get ( 'toMember' );	
		$id = var_get('id');
		try {
			$actionResult = Econgress::DelegateToClassify($id,$toMember);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;		
	case 206:
		// Отмена права классифицировать
		$id = var_get('id');
		try {
			$actionResult = Econgress::DeleteClassifyRight($id);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;	
	case 207 :
		// Классифицировать инициативу
		$Initiative = var_get('Initiative');
		$Classification = var_get('Classification');
		
		try {
			$actionResult = Econgress::ClassifyInitiative($Initiative,$Classification);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 208 :
		// Реклассифицировать инициативу
		$Initiative = var_get('Initiative');
		$Classification = var_get('Classification');
		
		try {
			$actionResult = Econgress::ReClassifyInitiative($Initiative,$Classification);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;

	case 209:
		// Изменить информацию по элементу классификации
		$id = var_get('id');
		$Information = var_get('Information');
		try {
			$actionResult = Econgress::ChangeClassInform($id,$Information);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 301 :
		// Добавление новой инициативы
		$Location = var_get('Location');
		$Title = var_get('Title');
		$Description = var_get('Description');
		$Information = var_get('Information');
		try {
			$actionResult = Econgress::CreateNewInitiative($Title,$Description,$Location,$Information);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
	case 302 :
		// Изменить дополнительную информацию по инициативе
		$id = var_get('id');
		$Information = var_get('Information');
		try {
			$actionResult = Econgress::ChangeInitInform($id,$Information);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 401:
		$toMember = var_get ( 'toMember' );
		$byClassification = var_get('byClassification');
		$delegationType = var_get('delegationType');
		$Exclude = var_get('Exclude')!=''?true:false;

		try {
			$actionResult = Econgress::ToDelegate($toMember,$byClassification,$Exclude,$delegationType);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 402:
		$id = var_get ( 'id' );
		try {
			$actionResult = Econgress::RemoveDelegation($id);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 403:
		$id = var_get('id');
		$toMember = var_get ( 'toMember' );
		$byClassification = var_get('byClassification');
		$delegationType = var_get('delegationType');
		$Exclude = var_get('Exclude')!=''?true:false;

		try {
			$actionResult = Econgress::ReDelegate($id,$toMember,$byClassification,$Exclude,$delegationType);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 501 :
		// Подписать инициативу
		$id = var_get ( 'id' );
		try {
			$actionResult = Econgress::SignInitiative ( $id, false );
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 502 :
		// Отменить подпись инициативы
		$id = var_get ( 'id' );
		try {
			$actionResult = Econgress::SignInitiative ( $id, true );
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 601 :
		// проголосовать за
		$id = var_get ( 'id' );
		try {
			$actionResult = Econgress::ToVote ( $id, true );
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	
	case 602 :
		// проголосовать против
		$id = var_get ( 'id' );
		try {
			$actionResult = Econgress::ToVote ( $id, false );
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	
	case 701:
		// Добавить элемент локации
		$id = var_get('id');
		$Title = var_get('Title');
		$Description = var_get('Description');
		try {
			$actionResult = Econgress::AddNewLocationElement($id,$Title,$Description);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
			case 702:
		// Изменить элемент локации
		$id = var_get('id');
		$Title = var_get('Title');
		$Description = var_get('Description');
		try {
			$actionResult = Econgress::ChangeLocationElement($id,$Title,$Description);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;			
	case 703:
		// Удалить элемент локации
		$id = var_get('id');
		try {
			$actionResult = Econgress::DeleteLocation($id);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}
		break;
	case 803:
		$Member = var_get('Member');
		$Location = var_get('Location');
		try {
			$actionResult = Econgress::ChangeMemberLocation($Member,$Location,true);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}		
		break;
	case 805:
		$LocationFrom = var_get('LocationFrom');
		$LocationTo = var_get('LocationTo');
		try {
			$actionResult = Econgress::GroupRelocation($LocationFrom,$LocationTo);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}		
		break;			
	case 806:
		$RatingTime = var_get('RatingTime');
		$MinRatingPercent = var_get('MinRatingPercent');
		$ClassificationTime = var_get('ClassificationTime');
		$MinVotingsMembersPercent = var_get('MinVotingsMembersPercent');
		$TimeBetweenReLocations = var_get('TimeBetweenReLocations');			
		Econgress::$Constants->SetValue('RatingTime',$RatingTime);
		Econgress::$Constants->SetValue('MinRatingPercent',$MinRatingPercent);
		Econgress::$Constants->SetValue('ClassificationTime',$ClassificationTime);
		Econgress::$Constants->SetValue('MinVotingsMembersPercent',$MinVotingsMembersPercent);
		Econgress::$Constants->SetValue('TimeBetweenReLocations',$TimeBetweenReLocations);
		break;
	case 807:
		$userName = var_get('userName');
		$firstName = var_get('firstName');
		$lastName = var_get('lastName');
		$Email = var_get('Email');
		$Language = var_get('Language');
		$newPass = var_get('newPass');
		$newPass2 = var_get('newPass2');
		try {
			$actionResult = Econgress::NewUser($userName,$newPass,$newPass2,$firstName,$lastName,$Email,$Language);
		} catch ( Exception $error ) {
			$actionResult = $error->getMessage ();
		}		
		break;
		case 808 :
		// Смена пользователя
		$user = var_get ( 'user' );
		$password = var_get ( 'pass' );
		Econgress::LogOut ();
		$actionResult = Econgress::LogIn ( $user, $password );
		break;
	default :
		$actionResult = true;
}
if ($GURL->action != '') {
	$GURL->action = '';
	$_SESSION['LastActionResult'] = $actionResult;
	header ( 'Location: ' . SITE_PATH . $GURL );
	exit ();
}

echo $pageHeader;

//echo $actionResult==true ? '' : 'Action error:' . $actionResult . '<br>';

switch ($GURL->interface) {
	case '0' :
	default :
		include 'Interfaces/Testing/Controller.php';
		break;
}

echo $pageBottom;
die;
?>