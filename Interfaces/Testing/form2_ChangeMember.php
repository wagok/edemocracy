<?php
$members = new Table ( );
$sql = "select id as ID, userName as User, Concat(firstName,' ', LastName) as FullName, id as link, password as pass from Members ORDER by userName";
$res = mysql_query ( $sql, Econgress::$DB );
$members->Load ( $res );
foreach ( $members as $row ) {
	$link = $PAGE_LINK . '&a=808&user=' . $row->User . '&pass=' . $row->pass;
	$row->link = '<a href="' . $link . '"><img src="Interfaces/Testing/Images/select.png"></a>';
}
$mHTML = new HTMLtable ( );
$mHTML->setSource ( $members );
$mHTML->CreateColumns ();
$mHTML->Design->includeHeader = true;
$mHTML->Design->includeFooter = true;
$mHTML->Design->tableTags = 'width="300"';
$mHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc"';
$mHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$mHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$mHTML->Columns [3]->Width = '22';
$mHTML->Columns [3]->Title = '';
$mHTML->DeleteColumn ( 4 );
echo $mHTML->toHTML ();
?>