<?php
$PAGE_LINK = SITE_PATH.$GURL;
$outLocations = '';
$mess = new Table();
$sql = "Select Locations.Title, Locations.addDate, '' As EditInformation,
  '' As AddChildLink, '' As RemoveLink,  Locations.id,
  Locations.Description, Locations.level
From Locations
Where Locations.Deleted = False";
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
		$row->Title = '<span title="'.$row->Description.'">'.($row->level>1?(str_repeat('&nbsp;&nbsp;&nbsp;',$row->level-1).'|--'):'').'<font style="font-size:'.(12-$row->level).'px;">'.
					$row->Title.'</font></span>';
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));

	$modalLinkAdd = new ModalBox("{$PAGE_LINK}&f=21&id=".$row->id,i_AddChildLocation);
	$modalLinkRemove = new ModalBox("{$PAGE_LINK}&f=22&id=".$row->id,i_RemoveLocationChilds);
	$modalLinkChangeInfo = new ModalBox("{$PAGE_LINK}&f=23&id=".$row->id,i_ChangeInfoLocation);

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

$messHTML->Columns[0]->Title = i_LocationsTree;
$messHTML->Columns[2]->ColumnTags = 'align="center"';
$messHTML->Columns[2]->Width = '22';
$messHTML->Columns[3]->ColumnTags = 'align="center"';
$messHTML->Columns[3]->Width = '22';
$messHTML->Columns[4]->ColumnTags = 'align="center"';
$messHTML->Columns[4]->Width = '22';

$messHTML->Columns[2]->Title = '';
$messHTML->Columns[3]->Title = '';
$messHTML->Columns[4]->Title = '';


$outLocations .= $messHTML->toHTML();

echo '<table height="100%"><tr><td height="470" valign="top">'.
'<div style="overflow: auto; max-height:450px;">'.i_LocationsTree.$outLocations.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';


?>