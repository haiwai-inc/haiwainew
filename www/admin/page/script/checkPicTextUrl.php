<?php
include "../../../inc.comm.php";
function checkurl($url,$pid,$mid,$id,$cate) {
	//对于非内容性的链接地址不做唯一性检查
	$tmp=strtolower($url);
	if(substr($tmp,0,7)=='http://'||strstr($tmp,'/')) {
		return 1;
	}
	
	//检查du表中是否已经存在相同的地址
	$obj = load("{$cate}_data_unit");
	if(empty($obj)) return false;
	
	$where=array(
		'pageid'=>$pid,
		'mid'=>$mid,
		'url'=>$url,
		'id,!='=>$id,
	);
	$rs=$obj->getOne(array('id'),$where);
	$status=empty($rs)?1:0;
	
	return $status;
}

function savepicture($source_file_path,$x,$y,$x2,$y2){
	//去掉?后面影响getImageInfo的url参数
	$pos = strpos( $source_file_path, "?");
	$source_file_path = substr( $source_file_path, 0, $pos );
	if( !file_exists( DOCUROOT.$source_file_path ) ) return false; //不存在源文件
	
	//计算原图的缩放比例
	$info1 = picture::getImageInfo( DOCUROOT . $source_file_path );
	$info2 = picture::getImgWH( $info1 , 600, 600 );
	$ratio=$info1['w']/$info2['w'];
	
	//得到新坐标
	$x = round($x*$ratio);
	$y = round($y*$ratio);
	$x2 = round($x2*$ratio);
	$y2 = round($y2*$ratio);
	
	//处理后的文件信息
	$savePath = dirname($source_file_path).'/';
	$filename = basename($source_file_path);
	$filename = strstr($filename, 'ori_')? substr( $filename, 4 ) : $filename ;
	
	//按设置尺寸对图片进行缩放处理
	$pic= new picture();
	$pic->leixing = 4;
	$pic->width = $x2-$x;
	$pic->height = $y2-$y;
	$pic->filename = files::getCleanFilename($filename);
	$pic->zuobiao = array(0, 0, $x, $y);
	
	$pic->width_tar = $x2-$x;
	$pic->height_tar = $y2-$y;
	
	$pic->filepath = DOCUROOT.$source_file_path;
	$pic->save_dir = DOCUROOT.$savePath;
	$pic->echoimage();
	
	$result = array( $savePath.$filename, $source_file_path  );
	
	return $result;	
}

$ajax=new Ajax();
$ajax->export("checkurl","savepicture");
?>