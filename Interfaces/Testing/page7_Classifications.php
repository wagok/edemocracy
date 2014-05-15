<?php
$PAGE_LINK = SITE_PATH.$GURL;
// Все классификации 
$outClass = '';
$mess = new Table();
$sql = sprintf("Select classifications.Rating, GlobClass.Title As
  GlobTitle,  classifications.Title, Members.firstName,
  Members.lastName, Members.userName As Author, classifications.id,
  classifications.level, classifications.Description
From classifications Inner Join
  classifications GlobClass On classifications.Classification = GlobClass.id
  Inner Join
  Members On GlobClass.Author = Members.id
Where classifications.Deleted <> true
Order By classifications.left_key");
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->Title = '<span title="'.$row->Description.'">'.($row->level>1?(str_repeat('&nbsp;&nbsp;&nbsp;',$row->level-1).'|--'):'').'<font style="font-size:'.(12-$row->level).'px;">'.
					$row->Title.'</font></span>';
		$row->Author = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->Author.'</span>';				
					
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
$messHTML->DeleteColumn('firstName');
$messHTML->DeleteColumn('lastName');
$messHTML->DeleteColumn('id');
$messHTML->DeleteColumn('level');
$messHTML->DeleteColumn('Description');

if (count($mess->Rows)>0){
$outClass .= $messHTML->toHTML();
$outClass = 'All classifications<br><div style="overflow: auto; max-height:150px;">'.$outClass.'</div>';
}

// Делегирование классифицировать 
$outClassDeleg = '';
$mess = new Table();
$sql = sprintf("Select Class.Title As GlobTitle, Members.userName, Members.firstName,
  Members.lastName, Class.Description, delegationsToClassify.id as Link
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
	$modalLinkRemove = new ModalBox("{$PAGE_LINK}&f=16&id=".$row->Link,i_RemoveClassifyRight);	
	$row->Link = '<a href="" '.$modalLinkRemove.'><img src="Interfaces/Testing/Images/action_delete.png"></a>';
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
$messHTML->DeleteColumn('Description');
$messHTML->DeleteColumn('lastName');
$messHTML->DeleteColumn('firstName');
$messHTML->Columns[0]->Title = i_Classification;
$messHTML->Columns[1]->Title = i_delegateTo;
$messHTML->Columns[2]->Title = '';
$messHTML->Columns[2]->ColumnTags = 'align="center"';
$messHTML->Columns[2]->Width = '22';

if (count($mess->Rows)>0){
$outClassDeleg .= $messHTML->toHTML();
$outClassDeleg = 'My delegations to Classify<br><div style="overflow: auto; max-height:150px;">'.$outClassDeleg.'</div>';
}


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
if (count($mess->Rows)>0){
$outClassDelegToMe .= $messHTML->toHTML();
$outClassDelegToMe = 'Delegations to Classify to Me<br><div style="overflow: auto; max-height:150px;">'.$outClassDelegToMe.'</div>';
}

// Мои классификации
$outMyClass = '';
$mess = new Table();
$sql = sprintf("Select  classifications.Rating,
  GlobClass.Title As GlobTitle, '' As EditInformation,  '' As AddChildLink, '' As RemoveLink, classifications.Title, classifications.id,
  classifications.level,  classifications.addDate, classifications.Description, '' As DelegateLink
From classifications Inner Join
  classifications GlobClass On classifications.Classification = GlobClass.id
Where classifications.Deleted <> true And classifications.Author = %s
Order By classifications.left_key",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
		$row->Title = '<span title="'.$row->Description.'">'.($row->level>1?(str_repeat('&nbsp;&nbsp;&nbsp;',$row->level-1).'|--'):'').'<font style="font-size:'.(12-$row->level).'px;">'.
					$row->Title.'</font></span>';
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));

	$modalLinkAdd = new ModalBox("{$PAGE_LINK}&f=11&id=".$row->id,i_AddChildClassification);
	$modalLinkRemove = new ModalBox("{$PAGE_LINK}&f=12&id=".$row->id,i_RemoveClassificationChilds);
	$modalLinkChangeInfo = new ModalBox("{$PAGE_LINK}&f=13&id=".$row->id,i_ChangeInfoClassification);
	
	if ($row->level==1) {
	$modalLinkDelegate = new ModalBox("{$PAGE_LINK}&f=14&id=".$row->id,i_AddDelegationToClassify);
	$row->DelegateLink = '<a href="" '.$modalLinkDelegate.'><img src="Interfaces/Testing/Images/delegationType1.png"></a>';
	}
	$row->AddChildLink = '<a href="" '.$modalLinkAdd.'><img src="Interfaces/Testing/Images/action_add.png"></a>';
	$row->RemoveLink = '<a href="" '.$modalLinkRemove.'><img src="Interfaces/Testing/Images/action_delete.png"></a>';
	$row->EditInformation = '<a href="" '.$modalLinkChangeInfo.'><img src="Interfaces/Testing/Images/change.png"></a>';
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
$messHTML->DeleteColumn('id');
$messHTML->DeleteColumn('level');
$messHTML->DeleteColumn('Description');
$messHTML->Columns[2]->Width = '22';
$messHTML->Columns[2]->ColumnTags = 'align="center"';
$messHTML->Columns[3]->ColumnTags = 'align="center"';
$messHTML->Columns[4]->ColumnTags = 'align="center"';
$messHTML->Columns[7]->ColumnTags = 'align="center"';
$messHTML->Columns[3]->Width = '22';
$messHTML->Columns[4]->Width = '22';
$messHTML->Columns[2]->Title = '';
$messHTML->Columns[3]->Title = '';
$messHTML->Columns[4]->Title = '';
$messHTML->Columns[7]->Width = '22';
$messHTML->Columns[7]->Title = '';
if (count($mess->Rows)>0){
$outMyClass .= $messHTML->toHTML();
$outMyClass = 'My classifications<br><div style="overflow: auto; max-height:150px;">'.$outMyClass.'</div>';
}

$modalNewClass = new ModalBox("{$PAGE_LINK}&f=15",i_AddDelegationToClassify);
$addClassificationLink = '<a href="" '.$modalNewClass.'>'.i_AddNewClassification.'</a>';
echo '<table height="100% width="100%">
     <tr>
      <td height="260" valign="top" width="65%">'.
     '<div style="overflow: auto; max-height:250px;">'.$outClass.'</div>
     </td>'.
     '<td valign="top" width="35%"><div style="overflow: auto; max-height:250px;">'.$outClassDelegToMe.'</div><td>
     </tr>'.
     '<tr>
      <td height="200" valign="top">'.
     '<div style="overflow: auto; max-height:190px;">'.$outMyClass.$addClassificationLink.'</div>
     </td>'.
     '<td valign="top"><div style="overflow: auto; max-height:190px;">'.$outClassDeleg.'</div><td>
     </tr>';
echo '<tr><td height="110" valign="bottom" colspan="2">'.SysMessagesTable(100).'</td></tr></table>';
?>