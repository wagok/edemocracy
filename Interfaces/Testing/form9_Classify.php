<?php
$ClassificationID = var_get ( 'Classification' );
$Initiative = var_get ( 'Initiative' );
$Selected = var_get('Selected');
$Classification = new Classifications();
$Classification->GetByID($ClassificationID);
if (!$Classification->Selected || $Classification->Deleted) {
	die;
}

echo '<form action="'.$PAGE_LINK ."&a=207&Initiative={$Initiative}&Classification={$Classification->id}".'" method="post" id="myform" name="myform">';
echo '<table width="100%" class="NoBorderTable">';

if ($Selected == '') {
	// Вся классификация 
	$outClass = '';
	$mess = new Table ( );
	$sql = sprintf ( "Select classifications.Title, classifications.level, classifications.id As Link,
  classifications.Description, if(classifications.left_key + 1 =
  classifications.right_key, true, false) As attr
From classifications
Where classifications.Classification = %s And classifications.Deleted <> true
Order By classifications.left_key", $Classification->Classification);
	$res = mysql_query ( $sql, Econgress::$DB );
	$mess->Load ( $res );
	foreach ( $mess as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=9&Initiative={$Initiative}&Classification={$row->Link}&Selected=8", i_Classify);
		$row->Title = '<span title="' . $row->Description . '">' . ($row->level > 1 ? (str_repeat ( '&nbsp;&nbsp;&nbsp;', $row->level - 1 ) . '|--') : '') . '<font style="font-size:' . (12 - $row->level) . 'px;">' . $row->Title . '</font></span>';
if ($row->attr) {
		$row->Link = '<a href=""' . $modalSelect . '><img src="Interfaces/Testing/Images/select.png"></a>';
} else {
	$row->Link = '';
}
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
	$messHTML->DeleteColumn('attr');
	$messHTML->Columns[1]->Title = '';
    $messHTML->Columns[1]->ColumnTags = 'align="center"';
    $messHTML->Columns[1]->Width = '22';
	$outClass .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectClassificationElement;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $outClass;
	echo '</div></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=9&Initiative={$Initiative}&Classification={$Classification->id}", i_Classify);
	// Стрела классификации 
	$outClass = '';
	$mess = new Table ( );
	$sql = sprintf ( "Select classifications.Title, classifications.level, 
  classifications.Description
From classifications
Where classifications.left_key <= %s And classifications.right_key >= %s
  And classifications.Deleted <> true And classifications.Classification <> 0
Order By classifications.left_key", $Classification->left_key, $Classification->right_key);
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
	$messHTML->Columns [0]->Title = i_ClassificationElement;
	$messHTML->DeleteColumn ( 'level' );
	$messHTML->DeleteColumn ( 'Description' );
	$outClass .= $messHTML->toHTML ();

	echo '<tr><td colspan="3">' . i_ClassifyByClassificationElement . ':</td></tr>';
	echo '<tr><td colspan="3"><div style="overflow: auto; max-height:200px;">'.$outClass.'</div></td></tr>';
	echo '<td colspan="3"><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}
echo '</table>';
if ($Selected=='') {
	$SaveLink =  '<span class="a"><font color="#CCCCCC">'.i_Save.'</font></span>';
} else {
$SaveLink = '<a href="javascript: document.myform.submit()" >' . i_Save . '</a>';
}
$CancelLink = '<a href="' . $PAGE_LINK . '" onclick="Modalbox.hide(); return false;">' . i_Cancel . '</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>' . $SaveLink . '</td><td>' . $CancelLink . '</td></tr></table></form>';

?>