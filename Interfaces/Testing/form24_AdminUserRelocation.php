<?php
$Member = var_get ( 'Member' );
$Location = var_get ( 'Location' );


echo '<form action="'.$PAGE_LINK ."&p=11&a=803&Member={$Member}&Location={$Location}".'" method="post" id="myform" name="myform">';
echo '<table width="100%" class="NoBorderTable">';

if ($Member == '') {
	$members = new Table ( );
	$sql = "select id as ID, userName as User, Concat(firstName,' ', LastName) as FullName, id as link, password as pass from Members ORDER by userName";
	$res = mysql_query ( $sql, Econgress::$DB );
	$members->Load ( $res );
	foreach ( $members as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=24&Member={$row->link}&Location={$Location}", i_UserReLocation,0,0,0,true );
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
	$mHTML->DeleteColumn ( 4 );
	echo '<tr><td colspan="3">' . i_SelectMemberRelocateTo;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $mHTML->toHTML ();
	echo '</div></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=24&Location={$Location}", i_UserReLocation,0,0,0,true );
	$member = new Members ( );
	$member->GetByID ( $Member );
	echo '<tr><td>' . i_SelectedMemberRelocateTo . ':</td>';
	echo '<td>#' . $member->id . ' ' . $member->userName . ' (' . $member->firstName . ' ' . $member->lastName . ')</td>';
	echo '<td><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}
if ($Location == '') {
	// Все локации
	$outLoc = '';
	$mess = new Table ( );
	$sql = "Select Concat(Locations.Path, '/', Locations.id) As Path, Locations.Title,
  Locations.id, Locations.level, Locations.Description, Not Exists(Select *
  From Locations As Loc
  Where Loc.Deleted <> true And Loc.left_key > Locations.left_key And
    Loc.right_key < Locations.right_key) As attr
From Locations
Where Locations.Deleted <> true
Order By spr.Locations.left_key";
	$res = mysql_query ( $sql, Econgress::$DB );
	$mess->Load ( $res );
	foreach ( $mess as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=24&Member={$Member}&Location={$row->id}", i_UserReLocation );
		$row->Title = '<span title="' . $row->Description . '">' . ($row->level > 1 ? (str_repeat ( '&nbsp;&nbsp;&nbsp;', $row->level - 1 ) . '|--') : '') . '<font style="font-size:' . (12 - $row->level) . 'px;">' . $row->Title . '</font></span>';
		if ($row->attr) {
			$row->id = '<a href=""' . $modalSelect . '><img src="Interfaces/Testing/Images/select.png"></a>';
		} else {
			$row->id = '';
		}
		$row->Path = '<font style="font-size:' . (12 - $row->level) . 'px;">' . $row->Path . '</font>';
		
	}
	$messHTML = new HTMLtable ( );
	$messHTML->setSource ( $mess );
	$messHTML->CreateColumns ();
	$messHTML->Design->includeHeader = true;
	$messHTML->Design->includeFooter = true;
	$messHTML->Design->tableTags = 'width="100%"';
	$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
	$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
	$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
	$messHTML->Columns [0]->Title = i_ClassificationElement;
	$messHTML->DeleteColumn ( 'level' );
	$messHTML->DeleteColumn ( 'Description' );
	$messHTML->DeleteColumn ( 'attr' );
	$messHTML->Columns [2]->Title = '';
	$messHTML->Columns [2]->ColumnTags = 'align="center"';
	$messHTML->Columns [2]->Width = '22';
	$outClass .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectLocationRelocateTo;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $outClass;
	echo '</div></td></tr>';
} else {
		$SelectedLoc = new Locations();
	$SelectedLoc->GetByID($Location);
	if (!$SelectedLoc->Selected || $SelectedLoc->Deleted) {
		die;
	}
	
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=24&id={$id}&Member={$Member}", i_UserReLocation,0,0,0,true );
	// Стрела локаций 
	$outClass = '';
	$mess = new Table ( );
	$sql = sprintf ( "Select Locations.Title, Locations.level, Locations.Description, Locations.Title,
  Locations.Description, Locations.level
From Locations
Where Locations.left_key <= %s And Locations.right_key >= %s And
  Locations.Deleted <> true And Locations.Level > 0
Order By Locations.left_key", $SelectedLoc->left_key, $SelectedLoc->right_key );
	$res = mysql_query ( $sql, Econgress::$DB );
	$mess->Load ( $res );
	foreach ( $mess as $row ) {
		$row->Title = '<span title="' . $row->Description . '">' . ($row->level > 1 ? (str_repeat ( '&nbsp;&nbsp;&nbsp;', $row->level - 1 ) . '|--') : '') . '<font style="font-size:' . (12 - $row->level) . 'px;">' . $row->Title . '</font></span>';
	}
	$messHTML = new HTMLtable ( );
	$messHTML->setSource ( $mess );
	$messHTML->CreateColumns ();
	$messHTML->Design->includeHeader = true;
	$messHTML->Design->includeFooter = true;
	$messHTML->Design->tableTags = 'width="100%"';
	$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
	$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
	$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
    $messHTML->Columns [0]->Title = '';
	$messHTML->Columns [1]->Title = i_ClassificationElement;
	$messHTML->DeleteColumn ( 'level' );
	$messHTML->DeleteColumn ( 'Description' );
	$outClass .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectedLoacation . ':</td></tr>';
	echo '<tr><td colspan="3"><div style="overflow: auto; max-height:200px;">' . $outClass . '</div></td></tr>';
	echo '<td colspan="3"><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';


}

echo '</table>';
if ($Member=='' || $Location=='' ) {
	$SaveLink =  '<span class="a"><font color="#CCCCCC">'.i_Save.'</font></span>';
} else {

$SaveLink = '<a href="javascript: document.myform.submit()" >' . i_Save . '</a>';
}
$CancelLink = '<a href="' . $PAGE_LINK . '" onclick="Modalbox.hide(); return false;">' . i_Cancel . '</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>' . $SaveLink . '</td><td>' . $CancelLink . '</td></tr></table></form>';

?>