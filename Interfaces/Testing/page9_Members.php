<?php
$PAGE_LINK = SITE_PATH.$GURL;
$members = new Table ( );
$sql = "Select Members.id As ID, Members.userName As User, Concat(Members.firstName,
  ' ', Members.LastName) As FullName, Members.Email As Email, Locations.Title As
  Location, Members.Language, Members.addDate, Members.id As link,
  Locations.Description as LocDescription
From Members Inner Join
  Locations On Members.memberLocation = Locations.id
Order By Members.userName";
$res = mysql_query ( $sql, Econgress::$DB );
$members->Load ( $res );
foreach ( $members as $row ) {
	$row->Location = '<span title="'.$row->LocDescription.'">'.$row->Location.'</span>';
	$modalLink = new ModalBox("{$PAGE_LINK}&f=20&id={$row->link}",i_MemberInformation, 300);

	$row->link = '<a href="#" '.$modalLink.'><img src="Interfaces/Testing/Images/info.png"></a>';
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("d.m.y", strtotime($row->addDate));
}
$mHTML = new HTMLtable ( );
$mHTML->setSource ( $members );
$mHTML->CreateColumns ();
$mHTML->Design->includeHeader = true;
$mHTML->Design->includeFooter = true;
$mHTML->Design->tableTags = 'width="100%"';
$mHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$mHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$mHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$mHTML->DeleteColumn('LocDescription');
//$mHTML->DeleteColumn('');
$mHTML->Columns [7]->Width = '22';
$mHTML->Columns [7]->Title = '';
$memberList =  $mHTML->toHTML ();

echo '<table height="100%"><tr><td height="470" valign="top">'.
'<div style="overflow: auto; max-height:450px;">'.i_MemberList.$memberList.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';

?>