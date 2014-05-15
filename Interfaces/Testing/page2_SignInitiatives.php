<?php
$PAGE_LINK = SITE_PATH.$GURL;

// Таблица не подписанных и отклоненных инициатив
$outInitNotSign = '';
$inits = new Table();
$sql = sprintf("Select Initiatives.initRating as Rating, Initiatives.addDate, 
Initiatives.deadLine,
  Initiatives.Title, Members.firstName, Members.lastName, Members.userName as Author, 
  InitLocation.Title as Location, Initiatives.id As signLink,
  (CASE WHEN Query1.id Is Null THEN 0
         WHEN Query1.DeclineByDelegant=false THEN 1
         ELSE 2 END) as memberSign, 
        Initiatives.Description as Descrip
From Initiatives Inner Join
  Members On Initiatives.Author = Members.id Inner Join
  Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
  (Select *
    From InitiativesRatingList
    Where InitiativesRatingList.Member = %s ) As Query1 On Initiatives.id =
    Query1.Initiative
Where Initiatives.deadLine > curdate() And Initiatives.Closed <> true And
  Initiatives.Decision <> true And Initiatives.Voting <> true And
  Initiatives.Deleted <> true And (Query1.id Is Null or Query1.DeclineByDelegant=true) 
Order By Initiatives.initRating, Initiatives.addDate",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	$modalLink = new ModalBox("{$PAGE_LINK}&f=1&id={$row->signLink}",i_SignDeclineInitiative, 300);
	$row->signLink = '<a href="_Sign/Unsign_initiative" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
	switch ($row->memberSign) {
		CASE 0:
			$row->memberSign = '<img src="Interfaces/Testing/Images/none.png">'; 
			break;
		CASE 1:
			$row->memberSign = '<img src="Interfaces/Testing/Images/sign.png">';
			break;
		CASE 2:
		    $row->memberSign = '<img src="Interfaces/Testing/Images/unsign.png">';
			break;
	}
	
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));
	$row->deadLine ='<span title="'.date("F j, Y, g:i a", strtotime($row->deadLine)).'">'.
					date("d.m.y", strtotime($row->deadLine));				

	$row->Author = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->Author.'</span>';
	$row->Title = '<span title="'.$row->Descrip.'">'.$row->Title;
}

$initsHTML = new HTMLtable();
$initsHTML->setSource($inits);
$initsHTML->CreateColumns();
$initsHTML->Design->includeHeader = true;
$initsHTML->Design->includeFooter = true;
$initsHTML->Design->tableTags = 'width="100%"';
$initsHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$initsHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$initsHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$initsHTML->Columns[8]->Width = '22';
$initsHTML->Columns[8]->Title = 'SL';
$initsHTML->Columns[9]->Width = '22';
$initsHTML->Columns[9]->Title = '';
$initsHTML->Columns[8]->ColumnTags = 'align="center"';
$initsHTML->Columns[9]->ColumnTags = 'align="center"';
$initsHTML->DeleteColumn(10);
$initsHTML->DeleteColumn(5);
$initsHTML->DeleteColumn(4);
$outInitNotSign .= $initsHTML->toHTML();


$outInitSign = '';
$inits = new Table();
$sql = sprintf("Select Initiatives.initRating as Rating, Initiatives.addDate, 
Initiatives.deadLine,
  Initiatives.Title, Members.firstName, Members.lastName, Members.userName as Author, 
  InitLocation.Title as Location, Initiatives.id As signLink,
  (CASE WHEN Query1.id Is Null THEN 0
         WHEN Query1.DeclineByDelegant=false THEN 1
         ELSE 2 END) as memberSign, 
         Initiatives.Description as Descrip
From Initiatives Inner Join
  Members On Initiatives.Author = Members.id Inner Join
  Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
  (Select *
    From InitiativesRatingList
    Where InitiativesRatingList.Member = %s ) As Query1 On Initiatives.id =
    Query1.Initiative
Where Initiatives.deadLine > curdate() And Initiatives.Closed <> true And
  Initiatives.Decision <> true And Initiatives.Voting <> true And
  Initiatives.Deleted <> true And (Query1.id Is Not Null and Query1.DeclineByDelegant=false) 
Order By Query1.addDate Desc, Initiatives.addDate",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	$modalLink = new ModalBox("{$PAGE_LINK}&f=1&id={$row->signLink}",i_SignDeclineInitiative, 300);
	$row->signLink = '<a href="_Sign/Unsign_initiative" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
	switch ($row->memberSign) {
		CASE 0:
			$row->memberSign = '<img src="Interfaces/Testing/Images/none.png">'; 
			break;
		CASE 1:
			$row->memberSign = '<img src="Interfaces/Testing/Images/sign.png">';
			break;
		CASE 2:
		    $row->memberSign = '<img src="Interfaces/Testing/Images/unsign.png">';
			break;
	}
	
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));
	$row->deadLine ='<span title="'.date("F j, Y, g:i a", strtotime($row->deadLine)).'">'.
					date("d.m.y", strtotime($row->deadLine));				

	$row->Author = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->Author.'</span>';
	$row->Title = '<span title="'.$row->Descrip.'">'.$row->Title;
}

$initsHTML = new HTMLtable();
$initsHTML->setSource($inits);
$initsHTML->CreateColumns();
$initsHTML->Design->includeHeader = true;
$initsHTML->Design->includeFooter = true;
$initsHTML->Design->tableTags = 'width="100%"';
$initsHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$initsHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$initsHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$initsHTML->Columns[8]->Width = '22';
$initsHTML->Columns[8]->Title = 'SL';
$initsHTML->Columns[9]->Width = '22';
$initsHTML->Columns[9]->Title = '';
$initsHTML->Columns[8]->ColumnTags = 'align="center"';
$initsHTML->Columns[9]->ColumnTags = 'align="center"';
$initsHTML->DeleteColumn(10);
$initsHTML->DeleteColumn(5);
$initsHTML->DeleteColumn(4);
$outInitSign .= $initsHTML->toHTML();

echo '<table height="100%"><tr><td height="260" valign="top">'.
'<div style="overflow: auto; max-height:250px;">'.i_NotSignInitiatives.$outInitNotSign.'</div></td></tr>';
echo '<tr><td height="210" valign="top">'.
'<div style="overflow: auto; max-height:200px;">'.i_SignInitiatives.$outInitSign.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';


?>