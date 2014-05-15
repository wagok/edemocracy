<?php
$PAGE_LINK = SITE_PATH.$GURL;
// Таблица инициатив поставленных на голосование по которым не проголосовано
$outVotingNotVoted = '';
$inits = new Table();
$sql = sprintf("Select Voting.votingRating, Voting.startDate, Voting.deadLine, Initiatives.Title,
  Members.firstName, Members.lastName, Members.userName As Author,
  InitLocation.Title As Location, Initiatives.id As signLink, Query1.ProAndCon
  As memberSign, Initiatives.Description As Descrip
From Initiatives Inner Join
  Members On Initiatives.Author = Members.id Inner Join
  Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
  (Select *
    From Votes
    Where Votes.Member = %s) As Query1 On Initiatives.id = Query1.Initiative
  Inner Join
  Voting On Voting.Initiative = Initiatives.id
Where Initiatives.Closed <> true And Initiatives.Decision <> true And
  Initiatives.Voting = true And Initiatives.Deleted <> true And Voting.startDate<CURDATE() 
  and Voting.deadLine>CURDATE() and isnull(Query1.id)
Order By Voting.startDate",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	//print_r($row);
	if (is_null($row->memberSign)) {
		$row->memberSign='<img src="Interfaces/Testing/Images/none.png">';
	} elseif ($row->memberSign) {
		$row->memberSign='<img src="Interfaces/Testing/Images/sign.png">';
	} else {
		$row->memberSign ='<img src="Interfaces/Testing/Images/unsign.png">';
	}
	$modalLink = new ModalBox("{$PAGE_LINK}&f=3&id={$row->signLink}",i_Voting, 300);
	$row->signLink = '<a href="_Voting" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
	$row->startDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->startDate)).'">'.
					date("d.m.y", strtotime($row->startDate));
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
$outVotingNotVoted .= $initsHTML->toHTML();

// Таблица инициатив поставленных на голосование по которым отдан голос
$outVotingVoted = '';
$inits = new Table();
$sql = sprintf("Select Voting.votingRating, Voting.startDate, Voting.deadLine, Initiatives.Title,
  Members.firstName, Members.lastName, Members.userName As Author,
  InitLocation.Title As Location, Initiatives.id As signLink, Query1.ProAndCon
  As memberSign, Initiatives.Description As Descrip
From Initiatives Inner Join
  Members On Initiatives.Author = Members.id Inner Join
  Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
  (Select *
    From Votes
    Where Votes.Member = %s) As Query1 On Initiatives.id = Query1.Initiative
  Inner Join
  Voting On Voting.Initiative = Initiatives.id
Where Initiatives.Closed <> true And Initiatives.Decision <> true And
  Initiatives.Voting = true And Initiatives.Deleted <> true And Voting.startDate<CURDATE() 
  and Voting.deadLine>CURDATE() and not isnull(Query1.id)
Order By Query1.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	//print_r($row);
	if (is_null($row->memberSign)) {
		$row->memberSign='<img src="Interfaces/Testing/Images/none.png">';
	} elseif ($row->memberSign) {
		$row->memberSign='<img src="Interfaces/Testing/Images/sign.png">';
	} else {
		$row->memberSign ='<img src="Interfaces/Testing/Images/unsign.png">';
	}
	$modalLink = new ModalBox("{$PAGE_LINK}&f=3&id={$row->signLink}",i_Voting, 300);
	$row->signLink = '<a href="_Voting" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
	$row->startDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->startDate)).'">'.
					date("d.m.y", strtotime($row->startDate));
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
$outVotingVoted .= $initsHTML->toHTML();


echo '<table height="100%"><tr><td height="260" valign="top">'.
'<div style="overflow: auto; max-height:250px;">'.i_NotVotedInitiatives.$outVotingNotVoted.'</div></td></tr>';
echo '<tr><td height="210" valign="top">'.
'<div style="overflow: auto; max-height:200px;">'.i_VotedInitiatives.$outVotingVoted.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';
?>