<?php
set_time_limit(0);
$ajax_sess_check = array('getdata','updatePost');

//从post获得内容源进行抓取
function getdata(){
	//验证是否是管理员
    if(!func_checkAdmin()){
        if(!func_checkEditor()) return array(0,'只有管理员才能执行当前操作!');
    }
	
	foreach($_POST as $k=>$v) $_POST[$k]=rawurldecode($v);
	$url = $_POST['url'];
	$subid = $_POST['subid'];
	
	//过滤目标内容
	$content = '';
	if(!empty($_POST['content'])){
		$content = strings::tidy($_POST['content']);
		$content = strip_tags($content,"<br><p><a><img><ul><ol><li><h1><h2><h3><span><div><table><tr><td>");
	}
	
	//抓取内容
	$obj = new web2db();
	$result = $obj->init($url,$content,$subid);
	if(empty($result)) return array(0,'没有可供抓取的图片!');
	
	//debug::d($result);exit;
	$i=0;
	foreach($result as $val){
		if(!empty($val['src'])) $content = str_replace($val['old'], $val['src'], $content);
		$i++;
	}

	return array($i,$content);
}

//直接更新数据库中的内容
function updatePost(){
	//验证是否是管理员
    if(!func_checkAdmin()){
        if(!func_checkEditor() && !func_checkManager()) return array(0,'只有管理员才能执行当前操作!');
    }
    
	//目标内容
	$postid = intval($_POST['postid']);
	$subid = $_POST['subid'];
	
	$postObj = load("bbs_post");
	$postObj->getPostObj($subid);
	$postmsg = $postObj->getOne("select * from `{$postObj->prefixTbn}_msg` where postid={$postid}");
	if(empty($postmsg['msgbody'])) return array(0,'没有找到要更新的内容!');
	
	//帖子内容
	$content = $postmsg['msgbody'];
	
	//抓取内容
	$obj = new web2db();
	$result = $obj->init('',$content,$subid);
	if(empty($result)) return array(0,'没有可供抓取的图片!');
	
	//debug::d($result);exit;
	$i=0;
	foreach($result as $val){
		if(!empty($val['src'])) $content = str_replace($val['old'], $val['src'], $content);
		$i++;
	}

	$postObj->checkConn();
	$contentSqlValue = dbtools::escape($content);
	$status = $postObj->exec("UPDATE `{$postObj->prefixTbn}_msg` set `msgbody`='{$contentSqlValue}' WHERE postid={$postid}");
	
	//同步搜索引擎
	$obj_bbs_search=load("bbs_search");
	$fields=array('postid'=>$postid,'subid'=>$subid,'msgbody'=>$contentSqlValue);
	$obj_bbs_search->update_search($fields);
	
	if(empty($status)) return array(0,'没有完成内容更新!');
	
	return array($status,$content);
}

class web2db{
	
	function init($refe, $content, $subid){
		$images = $this->getImg($content);
		$images = $this->formatSrc($refe,$images);
		if(empty($images)) return false;
		
		$today = date("Ym/d");
		$position = in_array($subid,array('news','gossip','ent'))?'news':'bbs';
		$conf = array('path'=>"/data/{$position}/{$today}", 'refe'=>$refe, 'w'=>600, 'h'=>800) ;
		
		foreach($images  as $key=>$val){
			$images[$key]['src']=$this->savePic($val['new'],$conf);
		}

		return $images;
	}
	
	private function getImg($content){
		if(empty($content)) return false;
		$html = strings::tidy($content);
		
		$dom = new domDocument;
		$dom->loadHTML($content);
		$dom->preserveWhiteSpace = false;
		$images_dom = $dom->getElementsByTagName('img');
		$images=array();
		
		//普通类型图片
		foreach ($images_dom as $element)
		{
			$src=$element->getAttribute('src');
			
			//已经抓过的
			if(substr($src,0,11)=='/data/news/'||substr($src,0,10)=='/data/bbs/'||substr($src,0,14)=='/upload/album/') 
				continue;
			
			$ext = strtolower(files::getExt($src));
			if( !in_array($src, $images) ) 
				$images[]=$src;
		}
		
		return $images;
	}
	
	private function formatSrc($refe,$images){
		if(empty($images)) return;
		
		if(!empty($refe)){
			$urlinfo = parse_url($refe);
			$basehost = $urlinfo['scheme']."://".$urlinfo['host'];
			$baseurl = dirname($refe)."/";
		}else{
			$basehost='';
			$baseurl ='';
		}
		
		$result=array();
		foreach($images as $img){
			//绝对路径
			if(substr($img,0,1)=='/'){
				$result[]=array('old'=>$img,'new'=>$basehost.$img);
				continue;
			}
			
			//相对路径
			if(strtolower(substr($img,0,7))!='http://' && strtolower(substr($img,0,8))!='https://'){
				$result[]=array('old'=>$img,'new'=>$baseurl.$img);
				continue;
			}
			
			//网路路径
			$result[]=array('old'=>$img,'new'=>$img);
		}
		
		return $result;
	}
	
	/**
	 * 抓取并保存图片
	 * 说明： 对于保持宽度的图片可以设置较大的高，对于保持高度的图片设置较大的宽
	 *
	 * @param $url  图片地址
	 * @param $conf  抓取的图片尺寸及存储位置 $conf = array('path'=>"/data/headline/web",'refe'=>'http://somewhere/','w'=>80)
	 */
	private function savePic($url,$conf){
		//base64特殊图片处理
		$check_base64=strstr($url,"data:image/");
		
		if(!empty($check_base64))
		{
			$tmp1=explode(';', $url);
			$tmp2=explode('/', $tmp1[0]);
			$image_type=$tmp2[1];
		}
		else
			$image_type=files::getExt($url);
		
		$filename = md5($url);
		$imgname = $conf['path'] ."/".$filename.".".$image_type;
		$filepath = DOCUROOT.$imgname;
		
		if(!is_file($filepath)) {
			$dir = dirname( $filepath );
			if( !is_dir($dir) ) files::mkdirs($dir);
			
			if(!empty($check_base64))
			{
				list($type, $url) = explode(';', $url);
				list(, $url)      = explode(',', $url);
				$url = base64_decode($url);
				
				file_put_contents($filepath,$url);
				$fullfilename=$filename.".".$image_type;
			}
			else
				$fullfilename = picture::saveImg($url, $dir, $filename,true,$conf['refe']);
			
			//如遇到不支持的图片，返回失败
			if(empty($fullfilename)) return false;
			
			//完整图片路径
			$filepath = $dir."/".$fullfilename;
				
			//有的时候图像的名字中的类型与图片的实际类型不同，此处取实际名称对$imgname重新附值
			$imgname = substr( $filepath, strlen(DOCUROOT) );
				
			//对图片进行大小处理
			/*
			$pic=new picture();
			$pic->filepath=$filepath;
			$pic->save_dir= $dir;
			$pic->filename=$filename;
				
			$pic->width=$conf['w'];
			$pic->height=$conf['h'];
			$pic->GetSize( 3 );
				
			//执行图片处理
			$pic->readimage();
			$pic->writeimage();
			*/	
			//调试
			//$this->debug("Got {$filepath} From {$url} !");
		}

		return $imgname;
	}
	
	private function debug($msg){echo $msg;echo "<br>";}
}