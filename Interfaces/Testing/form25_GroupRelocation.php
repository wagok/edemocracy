<?php
$LocationFrom = var_get ( 'LocationFrom' );
$LocationTo = var_get ( 'LocationTo' );

echo '<form action="'.$PAGE_LINK ."&p=11&a=805&LocationFrom={$LocationFrom}&LocationTo={$LocationTo}".'" method="post" id="myform" name="myform">';
echo '<table width="100%" class="NoBorderTable">';

if ($LocationFrom == '') {
	// Все локации
	$outLoc = '';
	$mess = new Table ( );
	$sql = "Select Concat(Locations.Path, '/', Locations.id) As Path, Locations.Title,
  Locations.id, Locations.level, Locations.Description, true As attr
From Locations
Where Locations.Deleted <> true
Order By spr.Locations.left_key";
	$res = mysql_query ( $sql, Econgress::$DB );
	$mess->Load ( $res );
	foreach ( $mess as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=25&LocationTo={$LocationTo}&LocationFrom={$row->id}", i_GroupRelocation );
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
	
	echo '<tr><td colspan="3">' . i_SelectLocationToRelocateFrom;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $outClass;
	echo '</div></td></tr>';
} else {
		$SelectedLoc = new Locations();
	$SelectedLoc->GetByID($LocationFrom);
	if (!$SelectedLoc->Selected || $SelectedLoc->Deleted) {
		die;
	}
	
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=25&LocationFrom={$LocationFrom}&LocationTo={$LocationTo}", i_GroupRelocation,0,0,0,true );
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
	
	echo '<tr><td colspan="3">' . i_SelectedLoacationToRelocateFrom . ':</td></tr>';
	echo '<tr><td colspan="3"><div style="overflow: auto; max-height:200px;">' . $outClass . '</div></td></tr>';
	echo '<td colspan="3"><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}

if ($LocationTo == '') {
	
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
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=25&LocationFrom={$LocationFrom}&LocationTo={$row->id}", i_GroupRelocation );
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
	$outClass1 .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectLocationToRelocateTo;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $outClass1;
	echo '</div></td></tr>';
} else {
		$SelectedLoc = new Locations();
	$SelectedLoc->GetByID($LocationTo);
	if (!$SelectedLoc->Selected || $SelectedLoc->Deleted) {
		die;
	}
	
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=25&id={$id}&LocationFrom={$LocationFrom}", i_GroupRelocation,0,0,0,true );
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
	$outClass1 .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectedLoacationToRelocateTo . ':</td></tr>';
	echo '<tr><td colspan="3"><div style="overflow: auto; max-height:200px;">' . $outClass1 . '</div></td></tr>';
	echo '<td colspan="3"><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';


}

echo '</table>';
if ($LocationFrom=='' || $LocationTo=='' ) {
	$SaveLink =  '<span class="a"><font color="#CCCCCC">'.i_Action.'</font></span>';
} else {

$SaveLink = '<a href="javascript: document.myform.submit()" >' . i_Action . '</a>';
}
$CancelLink = '<a href="' . $PAGE_LINK . '" onclick="Modalbox.hide(); return false;">' . i_Cancel . '</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>' . $SaveLink . '</td><td>' . $CancelLink . '</td></tr></table></form>';

?>