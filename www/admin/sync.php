<?php
include "../inc.comm.php";

func_initSession();
if(!func_isSuperAdmin()) alert(404);

//要创建 inc.updateid.php
$obj_repository=new repository();
$obj_repository->init_sync();

?>