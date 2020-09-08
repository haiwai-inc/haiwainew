<?php
include( "./inc.config.php" );

$_GET['act']=isset($_GET['act'])?$_GET['act']:'404';

if(in_array($_GET['act'],array('400','401','403','404','500'))){
	alert($_GET['act']);
}else{
	alert('404');
}
?>