<?php
include '../../../../inc.comm.php';
func_initSession();

//检查权限
if(empty($_SESSION["UserID"])){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	echo "<h1 style='color:red;'>用户没有登录，访问被拒绝!<h1>";exit;
}
		
if(!empty($_POST)){
	if(!empty($_FILES["userPicFile"])){
		$f=$_FILES["userPicFile"];
		if($f["size"]>0){
			$filetype=picture::getPicTrueType( $f['tmp_name'] );
			if( !in_array($filetype,array('gif','jpg','png')) ){
				$_SESSION['uploadmsg']='当前上传图像格式为“'.$filetype.'”，系统不支持此图像格式，请重新上传！';
				unlink($f['tmp_name']);
				go($_SERVER['HTTP_REFERER']);
			}
	
			// 处理上传图片内容
			$obj=load('page_pictext');
			$tmp=$obj->getFileData(array(),"userPicFile");
			$info = picture::getImgWH( picture::getImageInfo(DOCUROOT.$tmp['pic_big']), 600, 600 );
			
			go( $_POST['reurl']."&path={$tmp['pic_big']}&wo={$info['w']}&ho={$info['h']}" );
		}
	}
	
}
go($_SERVER['HTTP_REFERER']);
