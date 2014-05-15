<?php
function SysMessagesTable($height=150){
// Системные сообщения
$outMess = '';
$mess = new Table();
$sql = sprintf("Select SysMessages.Type, SysMessages.addDate, SysMessages.Message, 
  Members.userName, Members.lastName, Members.firstName
From SysMessages Inner Join
  Members On SysMessages.Author = Members.id
Where SysMessages.messTo = %s And SysMessages.Deleted <> true
Order By SysMessages.addDate Desc",Econgress::qs(Econgress::$Member->id));
$res = mysql_query($sql,Econgress::$DB);
$mess->Load($res);
foreach ($mess as $row) {
	$row->addDate ='<span title="'.date("F j, Y, g:i a", strtotime($row->addDate)).'">'.
					date("H:i", strtotime($row->addDate));
	$row->userName = '<span title="'.$row->firstName.' '.$row->lastName.'">'.$row->userName.'</span>';
	$row->Type = '<img src="Interfaces/Testing/Images/messType'.$row->Type.'.png">';				
}
$messHTML = new HTMLtable();
$messHTML->setSource($mess);
$messHTML->CreateColumns();
$messHTML->Design->includeHeader = true;
$messHTML->Design->includeFooter = true;
$messHTML->Design->tableTags = 'width="100%" class="scr" ';
//$messHTML->Design->tbodyTags = 'class="scr" style="height: '.$height.'px;"';
$messHTML->Design->headerTags->rowsTags = 'bgcolor="#cccccc" ';
$messHTML->Design->footerTags->rowsTags = 'bgcolor="#cccccc"';
$messHTML->Design->oddRowsTags->rowsTags = 'bgcolor="#ffeeee"';
$messHTML->Columns[3]->Title = i_Author;
$messHTML->Columns[0]->Title = "";
$messHTML->DeleteColumn(5);
$messHTML->DeleteColumn(4);
$outMess .= $messHTML->toHTML();

//return '<div >'.$outMess.'</div>';

return '<div style="overflow: auto; max-height:'.$height.'px;">'.$outMess.'</div>';
}
?>