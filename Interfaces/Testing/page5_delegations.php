<?php
$PAGE_LINK = SITE_PATH.$GURL;
// Делегирование от меня
$outDeleg = '';
$deleg = new Table();
$sql = sprintf("Select Delegations.delegateType, classifications.Title As Classification,
  Members.userName, Members.lastName, Members.firstName, Locations.Title As
  Location, classifications.Description As Class_Description,
  Delegations.Exclude, Delegations.addDate, Locations.Description As LocDescr, 
  Delegations.id as LinkChange, Delegations.id as LinkRemove
From Members Inner Join
  Delegations On Delegations.delegateTo = Members.id Inner Join
  classifications On Delegations.Classification = classifications.id Inner Join
  Locations On Members.memberLocation = Locations.id
Where Delegations.Deleted <> true And Delegations.delegateFrom = %s
Order By Delegations.delegateTo, Delegations.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$deleg->Load($res);
$delegType[1]=i_DelagationType1;
$delegType[2]=i_DelagationType2;
$ex_delegType[1]=i_ex_DelagationType1;
$ex_delegType[2]=i_ex_DelegationType2;

foreach ($deleg as $row) {
	$modalLinkRemove = new ModalBox("{$PAGE_LINK}&f=6&id=".$row->LinkRemove,i_RemoveDelegConfirm);
	$modalLinkChange = new ModalBox("{$PAGE_LINK}&f=7&id=".$row->LinkChange,i_ChangeDelegation);
	
	$row->LinkRemove = '<a href="" '.$modalLinkRemove.'><img src="Interfaces/Testing/Images/action_delete.png"></a>';
	$row->LinkChange = '<a href="" '.$modalLinkChange.'><img src="Interfaces/Testing/Images/change.png"></a>';
	
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.' from '.$row->Location.' ('.$row->LocDescr.')">'.$row->userName.'</span>';
	$row->Classification = '<span title="'.$row->Class_Description.'">'.$row->Classification.'</span>';
	if (!$row->Exclude) {
		$row->delegateType = '<img src="Interfaces/Testing/Images/delegationType'.$row->delegateType.'.png" title="'.$delegType[$row->delegateType].'">';				
	} else {
		$row->delegateType = '<img src="Interfaces/Testing/Images/ex_delegationType'.$row->delegateType.'.png" title="'.$ex_delegType[$row->delegateType].'">';	
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
$delegHTML->Columns[0]->ColumnTags = ' align="center" ';
$delegHTML->Columns[10]->Title = "";
$delegHTML->Columns[10]->Width = 22;
$delegHTML->Columns[10]->ColumnTags = ' align="center" ';
$delegHTML->Columns[11]->Title = "";
$delegHTML->Columns[11]->Width = 22;
$delegHTML->Columns[11]->ColumnTags = ' align="center" ';
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
					date("d.m.y", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.' from '.$row->Location.' ('.$row->LocDescr.')">'.$row->userName.'</span>';
	$row->Classification = '<span title="'.$row->Class_Description.'">'.$row->Classification.'</span>';
	if (!$row->Exclude) {
		$row->delegateType = '<img src="Interfaces/Testing/Images/delegationType'.$row->delegateType.'.png" title="'.$delegType[$row->delegateType].'">';				
	} else {
		$row->delegateType = '<img src="Interfaces/Testing/Images/ex_delegationType'.$row->delegateType.'.png" title="'.$ex_delegType[$row->delegateType].'">';
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

$NewDelegationLink = new ModalBox("{$PAGE_LINK}&f=8",i_NewDelegation);
$NewDelegationLink = '<a href="" '.$NewDelegationLink.'>'.i_NewDelegation.'</a>';

echo '<table height="100%"><tr><td height="260" valign="top">'.
'<div style="overflow: auto; max-height:250px;">'.$outDeleg.$NewDelegationLink.'</div></td></tr>';
if (count($deleg->Rows)>0){
echo '<tr><td height="210" valign="top">'.
'<div style="overflow: auto; max-height:200px;">Delegations to me:'.$outDelegFrom.'</div></td></tr>';
}
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';

?>