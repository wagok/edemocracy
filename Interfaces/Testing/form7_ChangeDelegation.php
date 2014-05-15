<?php
$id = var_get ( 'id' );
$toMember = var_get ( 'toMember' );
$byClassification = var_get ( 'byClassification' );
$delegationType = var_get ( 'delegationType' );
$Exclude = var_get('Exclude')!=''?'checked':'';
if ($toMember=='' && $byClassification=='' && $delegationType=='' && $id!='') {
	$deleg = new Delegations();
	$deleg->GetByID($id);
	if ($deleg->Selected) {
		$toMember = $deleg->delegateTo;
		$byClassification = $deleg->Classification;
		$delegationType = $deleg->delegateType;
		$Exclude = $deleg->Exclude?'checked':'';
	}
}
echo '<form action="'.$PAGE_LINK ."&a=403&id={$id}&toMember={$toMember}&byClassification={$byClassification}&delegationType={$delegationType}".'" method="post" id="myform" name="myform">';
echo '<table width="100%" class="NoBorderTable">';
if ($delegationType == '') {
	$modalActive = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$toMember}&byClassification={$byClassification}&delegationType=1", i_NewDelegation,0,0,0,true);
	$modalPassive = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$toMember}&byClassification={$byClassification}&delegationType=2", i_NewDelegation,0,0,0,true );
	echo '<tr><td>' . i_DelegationType . '</td>
	<td><a href="" ' . $modalActive . '>' . i_ActiveDelegation . '</a></td>
	<td><a href="" ' . $modalPassive . '>' . i_PassiveDelegation . '</a></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$toMember}&byClassification={$byClassification}", i_NewDelegation,0,0,0,true );
	echo '<tr><td>' . i_DelegationType . ':</td>';
	echo '<td>' . (($delegationType == 1) ? (i_ActiveDelegation) : (i_PassiveDelegation)) . '</td>';
	echo '<td><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}
if ($toMember == '') {
	$members = new Table ( );
	$sql = "select id as ID, userName as User, Concat(firstName,' ', LastName) as FullName, id as link, password as pass from Members ORDER by userName";
	$res = mysql_query ( $sql, Econgress::$DB );
	$members->Load ( $res );
	foreach ( $members as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$row->link}&byClassification={$byClassification}&delegationType={$delegationType}", i_NewDelegation,0,0,0,true );
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
	echo '<tr><td colspan="3">' . i_SelectMemberDelegationTo;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $mHTML->toHTML ();
	echo '</div></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&byClassification={$byClassification}&delegationType={$delegationType}", i_NewDelegation,0,0,0,true );
	$member = new Members ( );
	$member->GetByID ( $toMember );
	echo '<tr><td>' . i_MemberDelegationTo . ':</td>';
	echo '<td>#' . $member->id . ' ' . $member->userName . ' (' . $member->firstName . ' ' . $member->lastName . ')</td>';
	echo '<td><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}
if ($byClassification == '') {
	// Все классификации 
	$outClass = '';
	$mess = new Table ( );
	$sql = sprintf ( "select GlobClass.Title As GlobTitle, 
                 classifications.Title, 
                 classifications.id as Link,
                 classifications.level, 
                 classifications.Description 
From classifications Left Join
  classifications GlobClass On classifications.Classification = GlobClass.id
Where classifications.Deleted <> true
Order By classifications.left_key" );
	$res = mysql_query ( $sql, Econgress::$DB );
	$mess->Load ( $res );
	foreach ( $mess as $row ) {
		$modalSelect = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$toMember}&byClassification={$row->Link}&delegationType={$delegationType}", i_NewDelegation,0,0,0,true );
		$row->Title = '<span title="' . $row->Description . '">' . ($row->level > 1 ? (str_repeat ( '&nbsp;&nbsp;&nbsp;', $row->level - 1 ) . '|--') : '') . '<font style="font-size:' . (12 - $row->level) . 'px;">' . $row->Title . '</font></span>';
		$row->Link = '<a href=""' . $modalSelect . '><img src="Interfaces/Testing/Images/select.png"></a>';
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
	$messHTML->Columns [0]->Title = i_Classification;
	$messHTML->Columns [0]->Title = i_ClassificationElement;
	$messHTML->DeleteColumn ( 4 );
	$messHTML->DeleteColumn ( 3 );
	$outClass .= $messHTML->toHTML ();
	
	echo '<tr><td colspan="3">' . i_SelectClassificationElement;
	echo '<div style="overflow: auto; max-height:200px;">';
	echo $outClass;
	echo '</div></td></tr>';
} else {
	$modalChange = new ModalBox ( "{$PAGE_LINK}&f=7&id={$id}&toMember={$toMember}&delegationType={$delegationType}", i_NewDelegation,0,0,0,true );
	$class = new Classifications();
	$class->GetByID ( $byClassification );
	echo '<tr><td>' . I_DelegationByClassificationElement . ':</td>';
	echo '<td>#' . $class->id . ' ' . $class->Title . ' (' . $class->Description .')</td>';
	echo '<td><a href="" ' . $modalChange . '>' . i_Change . '</a></td></tr>';
}

echo '<tr><td>'.i_ExcludeDelegation.':<input type="checkbox" name="Exclude" '.$Exclude.'></td><td></td><td></td></tr>';
echo '</table>';
if ($toMember=='' || $byClassification=='' || $delegationType=='') {
	$SaveLink =  '<span class="a"><font color="#CCCCCC">'.i_Save.'</font></span>';
} else {

$SaveLink = '<a href="javascript: document.myform.submit()" >' . i_Save . '</a>';
}
$CancelLink = '<a href="' . $PAGE_LINK . '" onclick="Modalbox.hide(); return false;">' . i_Cancel . '</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>' . $SaveLink . '</td><td>' . $CancelLink . '</td></tr></table></form>';

?>