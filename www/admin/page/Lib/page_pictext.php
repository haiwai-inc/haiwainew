<?php
class page_pictext{
	function getFileData($tmp,$FileFieldName,$FileDataType='page'){
		
		if(!empty($_FILES[$FileFieldName])){
			$f=$_FILES[$FileFieldName];
			
			if($f["size"]>0){
				if(substr($_POST['path'],0,1)=="/"){
					$savePath=DOCUROOT.$_POST['path']."/";
					$imgPath=$_POST["path"]."/";
				}else{
					$savePath=DOCUROOT."/upload/{$FileDataType}/{$_POST['path']}/";
					$imgPath="/upload/{$FileDataType}/{$_POST['path']}/";
				}

				$file_save_name = empty($_POST["filename"])? substr(md5( $f['name'] ), 0, 8):$_POST["filename"];

				//为上传的文件增加目录深度
				$deepFolder= Cache::getDeepFolder($file_save_name,3);
				$savePath .= $deepFolder;
				$imgPath .= $deepFolder;

				//获得要保存的完整文件名
				$ori_filename='ori_'.$file_save_name.".".files::getExt($f['name']);

				//创建相关目录
				if(!file_exists($savePath)) files::mkdirs($savePath);

				//处理上传文件
				move_uploaded_file( $f['tmp_name'], $savePath.$ori_filename );

				//按设置尺寸对图片进行缩放处理
				$pic= new picture();
				$pic->filepath = $savePath.$ori_filename;
				$pic->save_dir = $savePath;
				$pic->leixing=3;

				$pic->width = $_POST["width"];
				$pic->height = $_POST["height"];
				$pic->filename=$file_save_name;
				$pic->echoimage();


				//获得结果
				$tmp["pic"]=$imgPath.$file_save_name.".".files::getExt($f['name']);
				$tmp["pic_big"]=$imgPath.$ori_filename;
			}
		}
		
		$tmp["pic"]=empty($tmp["pic"])?$_POST["pic"]:$tmp["pic"];
		$tmp["pic_big"]	=empty($tmp["pic_big"])?$_POST["pic"]:$tmp["pic_big"];
		
		return $tmp;
	}
}
