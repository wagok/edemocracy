<?php
require_once('Econgress/econgress.php');
require_once 'Tables/Tables.php';
$TypeSQL= "mysql";
$hostname="localhost";
$DBusername="root";
$DBpassword="";
$Dbase="SPR";

$connect = mysql_connect ( $hostname, $DBusername, $DBpassword ) or die ( "Unable to connect to MySQL" );

mysql_select_db ( $Dbase, $connect ) or die ( "Could not select the database" );
mysql_query("SET NAMES 'utf8'");
session_start ();
header('Content-type: text/html; charset=utf-8');

$query = 'select * from blockedtime limit 100';
$res = mysql_query($query);
$mytable = new Table();
$mytable->Load($res);
$mytable->DeleteColumn(0);

$mytable->Sort('Counter Asc, idSalesAgent Desc');

$htmlT = new HTMLtable();
$htmlT->setSource($mytable);
$htmlT->CreateColumns();
$htmlT->DeleteColumn(0);
$htmlT->AddColumn(0);
//$htmlT->ChangeColumns(0,3);
//$htmlT->ChangeColumns(0,4);

$htmlT->Design->includeHeader = true;
$htmlT->Design->includeFooter = true;
$htmlT->Design->tableTags = 'width="100%"';
$htmlT->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$str = $htmlT->toHTML();

$query = 'select * from holiday limit 100';
$res = mysql_query($query);
$mytable = new Table();
$mytable->Load($res);

$htmlT = new HTMLtable();
$htmlT->setSource($mytable);
$htmlT->CreateColumns();

$htmlT->Design->includeHeader = true;
$htmlT->Design->includeFooter = true;
$htmlT->Design->tableTags = 'width="100%"';
$htmlT->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$str1 = $htmlT->toHTML();

$query = 'select * from login limit 100';
$res = mysql_query($query);
$mytable = new Table();
$mytable->Load($res);

$htmlT = new HTMLtable();
$htmlT->setSource($mytable);
$htmlT->CreateColumns();

$htmlT->Design->includeHeader = true;
$htmlT->Design->includeFooter = true;
$htmlT->Design->tableTags = 'width="100%"';
$htmlT->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$str2 = $htmlT->toHTML();

$query = 'select * from rolesrights limit 100';
$res = mysql_query($query);
$mytable = new Table();
$mytable->Load($res);

$htmlT = new HTMLtable();
$htmlT->setSource($mytable);
$htmlT->CreateColumns();

$htmlT->Design->includeHeader = true;
$htmlT->Design->includeFooter = true;
$htmlT->Design->tableTags = 'width="100%"';
$htmlT->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$htmlT->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$str3 = $htmlT->toHTML();


$str = '<table width="1000"><tr><td><div class="scrol">'.$str.'</div></td><td><div class="scrol">'.$str1.'</div></td></tr><tr><td><div class="scrol">'.$str2.'</div></td><td><div class="scrol">'.$str3.'</div></td></tr>';
$str = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="global.css" rel="stylesheet" type="text/css">
</head>'.$str;

echo $str;


Initiative::Init($connect);
/*
$MyInit = new Locations();
$MyInit->GetByID(1);
//$MyInit->DeleteNode();

$x=1;
do{
$MyInit->GetByID(rand(1,20));
if ($MyInit->Selected){
$MyInit->CreateChild();
$MyInit->Title='New Location!';
$MyInit->Save();
$MyInit->GetParent();
$x++;}
}while ($x<10);

$MyInit = new Locations();
$MyInit->GetByID(1);
$MyInit->SelectAllChilds();
echo '<table>';
do {
	if (!$MyInit->Selected) break;
	echo '<tr>';
	for ($i=$MyInit->level; $i>0; $i--){
		echo '<td></td>';
	}
	echo '<td>'.$MyInit->id.'</td></tr>';
}while ($MyInit->Next());
echo '</table>';	
*/

?>
