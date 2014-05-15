<?php
$id = var_get ( 'id' );
$toMember = var_get ( 'toMember' );

echo '<form action="'.$PAGE_LINK ."&a=205&id={$id}&toMember={$toMember}".'" method="post" id="myform" name="myform">';
echo '<table width="100%" class="NoBorderTable">';

if ($toMember == '') {
	$members = new Table ( );
	$sql = "select id as ID, userName as User, Concat(firstName,' ', LastName) as FullName, id as link from Members ORDER by userName";
	$res = mysql_query ( $sql, Econgress::$DB );
	$members->Load ( $res );
	foreach ( $members as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=14&id={$id}&toMember={$row->link}", i_NewDelegation );
		$row->link = '<a href=""' . $modalSelect . '><img src="Interfaces/Testing/Images/select.png"></a>';
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
	$mHTML->Columns [3]->Width = '22';
	$mHTML->Columns [3]->Title = '';
	echo '<tr><td colspan="3">' . i_SelectMemberDelegationTo;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $mHTML->toHTML ();
	echo '</div></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=14&id={$id}", i_NewDelegation );
	$member = new Members ( );
	$member->GetByID ( $toMember );
	echo '<tr><td>' . i_MemberDelegationTo . ':</td>';
	echo '<td>#' . $member->id . ' ' . $member->userName . ' (' . $member->firstName . ' ' . $member->lastName . ')</td>';
	echo '<td><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}
echo '</table>';
if ($toMember=='') {
	$SaveLink =  '<span class="a"><font color="#CCCCCC">'.i_Save.'</font></span>';
} else {

$SaveLink = '<a href="javascript: document.myform.submit()" >' . i_Save . '</a>';
}
$CancelLink = '<a href="' . $PAGE_LINK . '" onclick="Modalbox.hide(); return false;">' . i_Cancel . '</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>' . $SaveLink . '</td><td>' . $CancelLink . '</td></tr></table></form>';

?>