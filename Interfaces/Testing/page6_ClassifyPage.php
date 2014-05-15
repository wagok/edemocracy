<?php
$PAGE_LINK = SITE_PATH.$GURL;
$outInitNotClass = '';
$inits = new Table();
$sql = sprintf("Select Distinct Query1.Stat, Query1.Rating, Query1.Voting_rating,
  Query1.Add_date, Query1.Rating_deadLine, Query1.Classify_start,
  Query1.Voting_start, Query1.Voting_end, Query1.userName As Author,
  Query1.Title As InitTitle, Query2.Title As ClassTitle, Query1.Location As
  InitLocation, Query1.Pro, Query1.Con, Query1.voidVoting, Query1.Descrip,
  Query1.InitId, Query2.ByDelegation, Query2.Classification As ClassId,
  Query1.firstName, Query1.lastName
From (Select (Case
        When (Not Initiatives.Closed = true And Initiatives.deadLine >
        CURDATE()) Then 3
        When (Not Initiatives.Closed = true And Voting.startDate > CURDATE() And
        Initiatives.deadLine < CURDATE()) Then 2
        When (Not Initiatives.Closed = true And Voting.startDate < CURDATE() And
        Voting.deadLine > CURDATE()) Then 1 Else 4
      End) As Stat, Initiatives.initRating As Rating, Voting.votingRating As
      Voting_rating, Initiatives.addDate As Add_date, Initiatives.deadLine As
      Rating_deadLine, Voting.addDate As Classify_start, Voting.startDate As
      Voting_start, Voting.deadLine As Voting_end, Initiatives.Title,
      InitLocation.Title As Location, Voting.Pro, Voting.Con, Voting.voidVoting,
      Initiatives.id As InitId, Initiatives.Description As Descrip,
      Members.userName, Members.firstName, Members.lastName
    From Initiatives Inner Join
      Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
      Voting On Voting.Initiative = Initiatives.id Inner Join
      Members On Initiatives.Author = Members.id
    Where Initiatives.Deleted <> true And Initiatives.Closed <> true
    Order By spr.Initiatives.addDate) Query1,
    (Select Distinct if(classifications.Author = %s, false, true) As
      ByDelegation, classifications.Title, classifications.Classification
    From classifications Left Join
      delegationsToClassify On delegationsToClassify.Classification =
        classifications.id
    Where classifications.Classification = classifications.id And
      classifications.Author = %s Or
      delegationsToClassify.delegateTo = %s) Query2
Where ROW(Query2.Classification, Query1.InitId) Not In (Select
    classifications.Classification, ClassificatedInitiatives.Initiative
  From ClassificatedInitiatives Inner Join
      classifications On ClassificatedInitiatives.Classification =
      classifications.id Where classifications.classification <> 0)
Order By Query1.Stat, Query1.Add_date",Econgress::qs(Econgress::$Member->id),
													Econgress::qs(Econgress::$Member->id),
													Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	$modalLinkChange = new ModalBox("{$PAGE_LINK}&f=9&Initiative={$row->InitId}&Classification={$row->ClassId}",i_Classify);
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
	
	$row->Author = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->Author.'</span>';
	$row->InitTitle = '<span title="'.$row->Descrip.'">'.$row->InitTitle;
	$row->ClassId = '<a href="" '.$modalLinkChange.'><img src="Interfaces/Testing/Images/change.png"></a>';
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
//$initsHTML->Columns[9]->Title = '';
//$initsHTML->Columns[8]->ColumnTags = 'align="center"';
//$initsHTML->Columns[9]->ColumnTags = 'align="center"';
$initsHTML->Columns[18]->Title='';
$initsHTML->DeleteColumn(20);
$initsHTML->DeleteColumn(19);
$initsHTML->DeleteColumn(15);
$initsHTML->DeleteColumn('InitId');
//$initsHTML->DeleteColumn(4);
$outInitNotClass .= $initsHTML->toHTML();

$outInitClass = '';
$inits = new Table();
$sql = sprintf("Select Distinct Query1.Stat, Query1.Rating, Query1.Voting_rating,
  Query1.Add_date, Query1.Rating_deadLine, Query1.Classify_start,
  Query1.Voting_start, Query1.Voting_end, Query1.userName As Author,
  Query1.Title As InitTitle, Query2.Title As ClassTitle, classifications.Title
  As SubClassTitle, Query1.Location As InitLocation, Query1.Pro, Query1.Con,
  Query1.voidVoting, Query1.Descrip, Query1.InitId, Query2.ByDelegation,
  Members.userName As ClassifyBy, Query2.Classification As ClassId,
  Query1.firstName, Query1.lastName, Members.firstName As CfirstName,
  Members.lastName As ClastName, classifications.Description As
  SubClassDescription
From (Select (Case
        When (Not Initiatives.Closed = true And Initiatives.deadLine >
        CURDATE()) Then 3
        When (Not Initiatives.Closed = true And Voting.startDate > CURDATE() And
        Initiatives.deadLine < CURDATE()) Then 2
        When (Not Initiatives.Closed = true And Voting.startDate < CURDATE() And
        Voting.deadLine > CURDATE()) Then 1 Else 4
      End) As Stat, Initiatives.initRating As Rating, Voting.votingRating As
      Voting_rating, Initiatives.addDate As Add_date, Initiatives.deadLine As
      Rating_deadLine, Voting.addDate As Classify_start, Voting.startDate As
      Voting_start, Voting.deadLine As Voting_end, Initiatives.Title,
      InitLocation.Title As Location, Voting.Pro, Voting.Con, Voting.voidVoting,
      Initiatives.id As InitId, Initiatives.Description As Descrip,
      Members.userName, Members.firstName, Members.lastName
    From Initiatives Inner Join
      Locations InitLocation On Initiatives.Location = InitLocation.id Left Join
      Voting On Voting.Initiative = Initiatives.id Inner Join
      Members On Initiatives.Author = Members.id
    Where Initiatives.Deleted <> true And Initiatives.Closed <> true
    Order By spr.Initiatives.addDate) Query1 Left Join
  ClassificatedInitiatives On Query1.InitId =
    ClassificatedInitiatives.Initiative Inner Join
  classifications On classifications.id =
    ClassificatedInitiatives.Classification Right Join
  (Select Distinct if(classifications.Author = %s, false, true) As ByDelegation,
      classifications.Title, classifications.Classification
    From classifications Left Join
      delegationsToClassify On delegationsToClassify.Classification =
        classifications.id
    Where classifications.Classification = classifications.id And
      classifications.Author = %s Or
      delegationsToClassify.delegateTo =
      %s) Query2 On classifications.Classification = Query2.Classification
  Left Join
  Members On ClassificatedInitiatives.Author = Members.id
Where ROW(Query2.Classification, Query1.InitId) In (Select
    classifications.Classification, ClassificatedInitiatives.Initiative
  From ClassificatedInitiatives Inner Join
      classifications On ClassificatedInitiatives.Classification =
      classifications.id Where classifications.classification <> 0)
Order By ClassificatedInitiatives.addDate Desc",Econgress::qs(Econgress::$Member->id),
													Econgress::qs(Econgress::$Member->id),
													Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
    $modalLinkChange = new ModalBox("{$PAGE_LINK}&f=10&Initiative={$row->InitId}&Classification={$row->ClassId}&Selected=8",i_Classify);
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
	
	$row->Author = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->Author.'</span>';
	$row->InitTitle = '<span title="'.$row->Descrip.'">'.$row->InitTitle.'</span>';
	$row->SubClassTitle = '<span title="'.$row->SubClassDescription.'">'.$row->SubClassTitle.'</span>';
	
	$row->ClassifyBy = '<span title="'.$row->CfirstName.' '.$row->ClastName.'">'.$row->ClassifyBy.'</span>';
	if ($row->Stat==3 || $row->Stat==2){
	$row->ClassId = '<a href="" '.$modalLinkChange.'><img src="Interfaces/Testing/Images/change.png"></a>';
} else {
	$row->ClassId = '';
}
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
//$initsHTML->Columns[9]->Title = '';
//$initsHTML->Columns[8]->ColumnTags = 'align="center"';
//$initsHTML->Columns[9]->ColumnTags = 'align="center"';

$initsHTML->DeleteColumn('Descrip');
$initsHTML->DeleteColumn('SubClassDescription');
$initsHTML->DeleteColumn('firstName');
$initsHTML->DeleteColumn('lastName');
$initsHTML->DeleteColumn('CfirstName');
$initsHTML->DeleteColumn('ClastName');
$initsHTML->DeleteColumn('InitId');
$initsHTML->Columns[18]->Title='';

$outInitClass .= $initsHTML->toHTML();

echo '<table height="100%" width="100%"><tr><td height="260" valign="top">'.
'<div style="overflow: auto; max-height:250px;">'.i_NotClassifyInitiatives.$outInitNotClass.'</div></td></tr>';
echo '<tr><td height="210" valign="top">'.
'<div style="overflow: auto; max-height:200px;">'.i_ClassifiedInitiatives.$outInitClass.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';

?>