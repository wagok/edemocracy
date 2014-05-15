<?php
$PAGE_LINK = SITE_PATH.$GURL;
$outInit = '';
$inits = new Table();
$sql = sprintf("Select 
(case 
     when (Initiatives.Closed and Initiatives.Decision) then 'Decision' 
     when (Initiatives.Closed and not Initiatives.Decision) then 'Closed'
     when (Initiatives.Closed and Voting.voidVoting) then 'Closed'
     when (not Initiatives.Closed and Initiatives.deadLine>CURDATE() ) then 'Rating'
     when (not Initiatives.Closed and Voting.startDate>CURDATE() and Initiatives.deadLine<CURDATE()) then 'Classify'
     when (not Initiatives.Closed and Voting.startDate<CURDATE() and Voting.deadLine>CURDATE()  ) then 'Voting'
     else 'None' 
     end) as Stat, 
Initiatives.initRating
  As Rating, Voting.votingRating As Voting_rating, Initiatives.addDate
  As Add_date, Initiatives.deadLine As Rating_deadLine, Voting.addDate
  As Classify_start, Voting.startDate As Voting_start, Voting.deadLine
  As Voting_end, Initiatives.Title, InitLocation.Title As Location, Voting.Pro,
  Voting.Con, Voting.voidVoting, Initiatives.id As InitId,
  Initiatives.Description As Descrip, Initiatives.id as Link
From Initiatives Inner Join
  Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
  Voting On Voting.Initiative = Initiatives.id
Where Initiatives.Deleted <> true And Initiatives.Author = %s
Order By spr.Initiatives.addDate",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	$modalLink = new ModalBox("{$PAGE_LINK}&f=4&id={$row->Link}",i_InitiativeAdditionalInformation);
	$row->Add_date ='<span title="'.date("F j, Y, g:i a", strtotime($row->Add_date)).'">'.
					date("d.m.y", strtotime($row->Add_date));
	$row->Rating_deadLine ='<span title="'.date("F j, Y, g:i a", strtotime($row->Rating_deadLine)).'">'.
					date("d.m.y", strtotime($row->Rating_deadLine));				
	$row->Classify_start ='<span title="'.date("F j, Y, g:i a", strtotime($row->Classify_start)).'">'.
					date("d.m.y", strtotime($row->Classify_start));
	$row->Voting_start ='<span title="'.date("F j, Y, g:i a", strtotime($row->Voting_start)).'">'.
					date("d.m.y", strtotime($row->Voting_start));
	$row->Voting_end ='<span title="'.date("F j, Y, g:i a", strtotime($row->Voting_end)).'">'.
					date("d.m.y", strtotime($row->Voting_end));

	$row->Title = '<span title="'.$row->Descrip.'">'.$row->Title;
	$row->Link = '<a href="" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
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
//$initsHTML->Columns[8]->Width = '22';
//$initsHTML->Columns[8]->Title = 'SL';
//$initsHTML->Columns[9]->Width = '22';
$initsHTML->Columns[15]->Title = '';
$initsHTML->Columns[15]->Width = '22';
//$initsHTML->Columns[8]->ColumnTags = 'align="center"';
//$initsHTML->Columns[9]->ColumnTags = 'align="center"';
$initsHTML->DeleteColumn(14);
$initsHTML->DeleteColumn(13);
//$initsHTML->DeleteColumn(4);
$outInit .= $initsHTML->toHTML();

$NewInitiativeLink = new ModalBox("{$PAGE_LINK}&f=5",i_CreateNewInitiative);
$NewInitiativeLink = '<a href="" '.$NewInitiativeLink.'>'.i_CreateNewInitiative.'</a>';
echo '<table width="100%" height="100%"><tr><td height="400" valign="top">'.$outInit.$NewInitiativeLink.'</td></tr>';
echo '<tr><td height="160" valign="bottom">'.SysMessagesTable(150).'</td></tr></table>';

?>