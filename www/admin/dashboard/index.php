<?php
include "../../inc.comm.php";
$act=empty($_GET['act'])?'':'?act='.$_GET['act'];
go('/admin/'.$act);
?>