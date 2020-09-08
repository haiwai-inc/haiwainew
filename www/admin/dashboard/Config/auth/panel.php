<?php
/**
 * 此处应该与Action中的逻辑方法一一对应，
 * 标识的方法名在执行时要根据session情况返回对应的值
 * 
 * 不设定权限文件，
 * $app->admin=true时，默认任何方法都是admin权限
 * $app->admin=false时（默认情况），任何方法都不检查权限
 */

return array(
	"visit"=>array('ctrl'),//任何人都可以访问的
	"user"=>array('changeSkin','changeSite','desktop'),//登录后的用户可以访问的
	"admin"=>array('index','menu','frame'),//管理员可以访问的
	//"superadmin"=>true,//要求超管权限的页面
);
?>