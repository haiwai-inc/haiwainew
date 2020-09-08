<?php
/*发布svn同步指令*/
define( 'ROOT', str_replace("/admin/tools/srcSync","",dirname( __FILE__ )));
include ROOT.'/inc.comm.php';
func_checkCliEnv();

$obj_repository=new repository();
echo $obj_repository->svnTask($argv);
?>