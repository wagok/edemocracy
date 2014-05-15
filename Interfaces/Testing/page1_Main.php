<?php

$PAGE_LINK = SITE_PATH.$GURL;
$outInit = '';
$inits = new Table();
$sql = sprintf("Select Initiatives.initRating as Rating, Initiatives.addDate, 
Initiatives.deadLine,
  Initiatives.Title, Members.firstName, Members.lastName, Members.userName as Author, 
  InitLocation.Title as Location, Initiatives.id As signLink,
  (CASE WHEN Query1.id Is Null THEN 0
         WHEN Query1.DeclineByDelegant=false THEN 1
         ELSE 2 END) as memberSign, 
         if((Query1.id Is Null or Query1.DeclineByDelegant=true), 0,1) as TNumber,
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
  Initiatives.Deleted <> true
Order By TNumber, Initiatives.initRating, Initiatives.addDate",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$inits->Load($res);
foreach ($inits as $row) {
	$modalLink = new ModalBox("{$PAGE_LINK}&f=1&id={$row->signLink}",i_SignDeclineInitiative);
	$row->signLink = '<a href="_Sign/Unsign_initiative" '.$modalLink.'><img src="Interfaces/Testing/Images/change_sign.png"> </a>';
	$row->memberSign = $row->memberSign?'<img src="Interfaces/Testing/Images/sign.png">':'<img src="Interfaces/Testing/Images/unsign.png">'; 
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
$outInit .= $initsHTML->toHTML();

// Ссылка перехода на другого члена
$modalLink = new ModalBox("{$PAGE_LINK}&f=2",i_ChangeMember,320);
$changeMemberLink = '<a href="_Change_member" '.$modalLink.'>'.i_ChangeMember.'</a>'; 

// Таблица инициатив поставленных на голосование
$outVoting = '';
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
  Initiatives.Voting = true And Initiatives.Deleted <> true
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
	$modalLink = new ModalBox("{$PAGE_LINK}&f=3&id={$row->signLink}",i_Voting);
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
$outVoting .= $initsHTML->toHTML();

// Системные сообщения
$outMess = '';
$mess = new Table();
$sql = sprintf("Select SysMessages.Type, SysMessages.addDate, SysMessages.Message, 
  Members.userName, Members.lastName, Members.firstName
From SysMessages Inner Join
  Members On SysMessages.Author = Members.id
Where SysMessages.messTo = %s And SysMessages.Deleted <> true
Order By SysMessages.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("H:i", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->userName.'</span>';
	$row->Type = '<img src="Interfaces/Testing/Images/messType'.$row->Type.'.png">';				
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[3]->Title = i_Author;
$messHTML->Columns[0]->Title = "";
$messHTML->DeleteColumn(5);
$messHTML->DeleteColumn(4);
$outMess .= $messHTML->toHTML();

// Делегирование от меня
$outDeleg = '';
$deleg = new Table();
$sql = sprintf("Select Delegations.delegateType, classifications.Title As Classification,
  Members.userName, Members.lastName, Members.firstName, Locations.Title As
  Location, classifications.Description As Class_Description,
  Delegations.Exclude, Delegations.addDate, Locations.Description As LocDescr
From Members Inner Join
  Delegations On Delegations.delegateTo = Members.id Inner Join
  classifications On Delegations.Classification = classifications.id Inner Join
  Locations On Members.memberLocation = Locations.id
Where Delegations.Deleted <> true And Delegations.delegateFrom = %s
Order By Delegations.delegateTo, Delegations.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$deleg->Load($res);
foreach ($deleg as $row) {
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("H:i", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.' from '.$row->Location.' ('.$row->LocDescr.')">'.$row->userName.'</span>';
	$row->Classification = '<span title="'.$row->Class_Description.'">'.$row->Classification.'</span>';
	if (!$row->Exclude) {
		$row->delegateType = '<img src="Interfaces/Testing/Images/delegationType'.$row->delegateType.'.png">';				
	} else {
		$row->delegateType = '<img src="Interfaces/Testing/Images/ex_delegationType'.$row->delegateType.'.png">';
	}
}
$delegHTML = new HTMLtable();
$delegHTML->setSource($deleg);
$delegHTML->CreateColumns();
$delegHTML->Design->includeHeader = true;
$delegHTML->Design->includeFooter = true;
$delegHTML->Design->tableTags = 'width="100%"';
$delegHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$delegHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$delegHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$delegHTML->Columns[2]->Title = i_delegateTo;
$delegHTML->Columns[3]->Title = i_Author;
$delegHTML->Columns[0]->Title = "";
$delegHTML->Columns[0]->Width = 22;
$initsHTML->Columns[0]->ColumnTags = ' align="center" ';
$delegHTML->DeleteColumn(9);
$delegHTML->DeleteColumn(7);
$delegHTML->DeleteColumn(6);
$delegHTML->DeleteColumn(4);
$delegHTML->DeleteColumn(3);
$outDeleg .= $delegHTML->toHTML();

// Делегирование ко мне
$outDelegFrom = '';
$deleg = new Table();
$sql = sprintf("Select Delegations.delegateType, classifications.Title As Classification,
  Members.userName, Members.lastName, Members.firstName, Locations.Title As
  Location, classifications.Description As Class_Description,
  Delegations.Exclude, Delegations.addDate, Locations.Description As LocDescr
From Members Inner Join
  Delegations On Delegations.delegateFrom = Members.id Inner Join
  classifications On Delegations.Classification = classifications.id Inner Join
  Locations On Members.memberLocation = Locations.id
Where Delegations.Deleted <> true And Delegations.delegateTo = %s
Order By Delegations.delegateTo, Delegations.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$deleg->Load($res);
foreach ($deleg as $row) {
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("H:i", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.' from '.$row->Location.' ('.$row->LocDescr.')">'.$row->userName.'</span>';
	$row->Classification = '<span title="'.$row->Class_Description.'">'.$row->Classification.'</span>';
	if (!$row->Exclude) {
		$row->delegateType = '<img src="Interfaces/Testing/Images/delegationType'.$row->delegateType.'.png">';				
	} else {
		$row->delegateType = '<img src="Interfaces/Testing/Images/ex_delegationType'.$row->delegateType.'.png">';
	}
}
$delegHTML = new HTMLtable();
$delegHTML->setSource($deleg);
$delegHTML->CreateColumns();
$delegHTML->Design->includeHeader = true;
$delegHTML->Design->includeFooter = true;
$delegHTML->Design->tableTags = 'width="100%"';
$delegHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$delegHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$delegHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$delegHTML->Columns[2]->Title = i_delegateFrom;
$delegHTML->Columns[3]->Title = i_Author;
$delegHTML->Columns[0]->Title = "";
$delegHTML->Columns[0]->Width = 22;
$initsHTML->Columns[0]->ColumnTags = ' align="center" ';
$delegHTML->DeleteColumn(9);
$delegHTML->DeleteColumn(7);
$delegHTML->DeleteColumn(6);
$delegHTML->DeleteColumn(4);
$delegHTML->DeleteColumn(3);
$outDelegFrom .= $delegHTML->toHTML();

// Все классификации 
$outClass = '';
$mess = new Table();
$sql = sprintf("Select classifications.Rating, classifications.Title, classifications.id,
  classifications.level, classifications.Description, GlobClass.Title As
  GlobTitle
From classifications Left Join
  classifications GlobClass On classifications.Classification = GlobClass.id
Where classifications.Deleted <> true
Order By classifications.left_key");
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->Title = '<span title="'.$row->Description.'">'.($row->level>1?(str_repeat('&nbsp;&nbsp;&nbsp;',$row->level-1).'|--'):'').'<font style="font-size:'.(12-$row->level).'px;">'.
					$row->Title.'</font></span>';
					
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[0]->Title = "";
$messHTML->DeleteColumn(4);
$messHTML->DeleteColumn(3);
$messHTML->DeleteColumn(2);
$outClass .= $messHTML->toHTML();

// Делегирование классифицировать 
$outClassDeleg = '';
$mess = new Table();
$sql = sprintf("Select Class.Title As GlobTitle, Members.userName, Members.firstName,
  Members.lastName, Class.Description
From classifications Class Inner Join
  delegationsToClassify On delegationsToClassify.Classification = Class.id
  Inner Join
  Members On delegationsToClassify.delegateTo = Members.id
Where delegationsToClassify.Author = %s And Class.Classification = Class.id And
  delegationsToClassify.Deleted <> true And Class.Deleted <> true",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->GlobTitle = '<span title="'.$row->Description.'">'.$row->GlobTitle.'</span>';
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->userName.'</span>';
		
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[0]->Title = i_Classification;
$messHTML->Columns[1]->Title = i_delegateTo;
$messHTML->DeleteColumn(4);
$messHTML->DeleteColumn(3);
$messHTML->DeleteColumn(2);
$outClassDeleg .= $messHTML->toHTML();

// Делегирование классифицировать мне
$outClassDelegToMe = '';
$mess = new Table();
$sql = sprintf("Select Class.Title As GlobTitle, Members.userName, Members.firstName,
  Members.lastName, Class.Description
From classifications Class Inner Join
  delegationsToClassify On delegationsToClassify.Classification = Class.id
  Inner Join
  Members On delegationsToClassify.Author = Members.id
Where Class.Classification = Class.id And delegationsToClassify.delegateTo = %s
  And Class.Deleted <> true And delegationsToClassify.Deleted <> true",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->GlobTitle = '<span title="'.$row->Description.'">'.$row->GlobTitle.'</span>';
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->userName.'</span>';
		
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[0]->Title = i_Classification;
$messHTML->Columns[1]->Title = i_delegateFrom;
$messHTML->DeleteColumn(4);
$messHTML->DeleteColumn(3);
$messHTML->DeleteColumn(2);
$outClassDelegToMe .= $messHTML->toHTML();

// Мои классификации
$outMyClass = '';
$mess = new Table();
$sql = sprintf("Select Class.Title As GlobTitle, Class.Description, Class.Rating, Class.addDate
From classifications Class
Where Class.Author = %s And Class.Classification = Class.id And
  Class.Deleted <> true",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->GlobTitle = '<span title="'.$row->Description.'">'.$row->GlobTitle.'</span>';
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[0]->Title = i_Classification;
$messHTML->Columns[2]->Title = i_Rating;
$messHTML->DeleteColumn(1);
$outMyClass .= $messHTML->toHTML();

echo $changeMemberLink.'<br>';
$outInit = 'Initiatives to Rate<br>'.$outInit;
$outVoting= 'Voting<br>'.$outVoting;
$outDeleg= 'My delegations<br>'.$outDeleg;
$outDelegFrom = 'Delegations to me<br>'.$outDelegFrom;
$outMyClass = 'My classifications<br><div style="overflow: auto; max-height:150px;">'.$outMyClass.'</div>';

$outClass = 'Classifications<br><div style="overflow: auto; max-height:150px;">'.$outClass.'</div>';
$outClassDeleg = 'My delegations to Classify<br><div style="overflow: auto; max-height:150px;">'.$outClassDeleg.'</div>';
$outClassDelegToMe = 'Delegations to Classify to Me<br><div style="overflow: auto; max-height:150px;">'.$outClassDelegToMe.'</div>';

$outMess = 'System Messages<br><div style="overflow: auto; max-height:150px;">'.$outMess.'</div>';

echo "<table cellspacing=\"5\" cellpadding=\"5\" border=\"2\" class=\"tablesTable\" width=\"100%\"><col span=\"3\" valign=\"top\"/><tr><td width=\"48%\">$outInit</td><td></td><td width=\"48%\">$outVoting</td></tr>";
echo "<tr><td width=\"48%\">$outDeleg</td><td></td><td width=\"48%\">$outDelegFrom</td></tr>";
echo "<tr><td width=\"48%\" rowspan=\"2\">$outClass</td><td></td><td width=\"48%\">$outMyClass</td></tr>";
echo "<tr><td></td><td width=\"48%\">$outClassDeleg</td></tr>";
echo "<tr><td width=\"48%\">$outMess</td><td></td><td width=\"48%\">$outClassDelegToMe</td></tr>";
echo '</table>';
?>