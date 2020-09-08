<?php
include "../../../inc.comm.php";

function setupTpl($id){
	$filepath='/'.$_GET['cate'].'/Tpl/'.conf($_GET['cate'].'.htmltpl',$id.'.filepath');
	$html=is_file(DOCUROOT.$filepath)?file_get_contents(DOCUROOT.$filepath):'';
	return $html;
}

$ajax=new Ajax();
$ajax->export("setupTpl");