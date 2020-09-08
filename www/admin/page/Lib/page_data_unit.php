<?php
class page_data_unit extends Model{
	static public $rs=null;
	
	protected $tableName = 'data_unit';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");	

	/**
	 * 一次性取出全部页面配置信息,并缓存
	 *
	 * @param int $page_id
	 * @param int $appinfo 对应的应用程序配置信息
	 * @param int $itemid  对于图文混排项目,这个参数用于调用指定分类下的图文单元
	 * @param int $limit   指定返回的单元数据最大值，一般用于非宿主页面的调用
	 * @return array
	 */
	function getRecord($page_id,$appinfo,$itemid=0,$limit=0){
		if(!isset($this->rs[$page_id])){
			$fields=array('id','itemid','pageid','mid','title','highlight','pic','width','height','url','link','summary','configure','updatetime','visit','position');
			$tmp=$this->getAll($fields,array("pageid"=>$page_id,"status"=>"Y",'show'=>'Y','order'=>array("order"=>"DESC")));
			
			$this->rs[$page_id]=array();
			foreach($tmp as $val){				
				if(!empty($val["title"])){
					if($titles=$this->slaveString($val["title"])){
						$val["title"]=$titles[0];
						$val["slavetitle"]=$titles[1];
					}
					if($urls=$this->slaveString($val["url"])){
						$val["url"]=$urls[0];
						$val["slaveurl"]=$urls[1];
					}
				}
				
				if(!isset($this->rs[$page_id][$val["position"]])) {
					$this->rs[$page_id][$val["position"]][]=$val;	
				}else{
					if(empty($limit) || count($this->rs[$page_id][$val["position"]])<$limit )
						$this->rs[$page_id][$val["position"]][]=$val;
				}
			}
		}
		
		//当前某个应用下的全部结果
		$value=empty($this->rs[$page_id][$appinfo['id']])?null:$this->rs[$page_id][$appinfo['id']];

		//提取具体项目的内容
		if( $itemid!=0 && !empty($value) ){
			$list=array();
			foreach($value as $key=>$val){
				if($val["itemid"]==$itemid)
				$list[]=$val;
			}
			$value=$list;
		}
		
		//格式化链接
		if(!empty($value)){
			//当前页面信息
			$pageinfo=null;
			$obj=load( $appinfo['pagetype'].'_page' );
			if(isset($obj->cache[$page_id])){
				$pageinfo=$obj->cache[$page_id];
			}
			if(empty($pageinfo)){
				$pageinfo=$obj->cache[$page_id]=$obj->getOne('*',array('id'=>$page_id));
			}
			
			//处理单元数据
			$value = $this->formatValue($value,$appinfo,$pageinfo);
		}
		
		return $value;
	}
	
	//针对结果为单一app的数据
	public function formatValue($value,$appinfo,$pageinfo){
		$titleLen=empty($appinfo['param']['picTitleLength'])?25:intval($appinfo['param']['picTitleLength']);
		$summaryLen=empty($appinfo['param']['picSumaryLength'])?120:intval($appinfo['param']['picSumaryLength']);
		
		foreach($value as $key=>$val)
			$value[$key] = $this->getVal($val,$titleLen,$summaryLen,$appinfo,$pageinfo);
		
		return $value;
	}
	
	//处理多个app的数据
	public function formatMultiAppValue($value,$appinfoList,$pageinfo){
		
		foreach($value as $key=>$val){
			//根据单元设置加载配置信息
			$appinfo=$appinfoList[$val['mid']];
			if(empty($appinfo)) continue;
			
			$titleLen=empty($appinfo['param']['picTitleLength'])?25:intval($appinfo['param']['picTitleLength']);
			$summaryLen=empty($appinfo['param']['picSumaryLength'])?120:intval($appinfo['param']['picSumaryLength']);
		
			$value[$key] = $this->getVal($val,$titleLen,$summaryLen,$appinfo,$pageinfo);
		}
		
		return $value;
	}
	
	private function getVal($val,$titleLen,$summaryLen,$appinfo,$pageinfo){
		if(!empty($titleLen)){
			//对html进行编码处理
			$val['title']=strings::htmlFilter($val['title']);
			
			//保留原标题
			$val['ori_title']=$val['title'];
			
			//截取
			$val['title']=strings::subString($val['title'],$titleLen);
			
			//增加颜色
			if(!empty($val['highlight']))$val['title']="<span style='color:#{$val['highlight']}'>{$val['title']}</span>";
		}
		
		//截取摘要
		if(!empty($summaryLen))
			$val['summary']=strings::subString($val['summary'],$summaryLen);
		
		//设置标签
		if(!empty($val['configure'])&&!is_array($val['configure'])) $val['configure']=unserialize($val['configure']);
		if(!empty($val['configure']['searchkey']))
			$val['configure']['searchkey']=$this->getKeyWords($val['configure']['searchkey']);
		
		//设置链接
		$val['link']=$this->formatLink($val,$appinfo,$pageinfo);
		
		//处理图片图片信息
		if( !empty($val['pic'] )){
			if( substr($val['pic'],0,1)=='/' )
				$val['pic']="/images/{$val['width']}/{$val['height']}{$val['pic']}";
		}
		
		return $val;
	}
	
	function getResult($pid,$mid){
		static $rs;
		if(empty($rs)) {
			$rs=$this->getOne('*',array('pageid'=>$pid,'mid'=>$mid));
			
			$title=$this->slaveString($rs['title']);
			if(!empty($title)){
				$rs['title']=$title[0];
				$rs['slavetitle']=empty($title[1])?null:$title[1];
			}
			$url=$this->slaveString($rs['url']);
			if(!empty($url)){
				$rs['url']=$url[0];
				$rs['slaveurl']=empty($url[1])?null:$url[1];
			}
		}
		return $rs;
	}
	
	/*过滤关键词标签*/
	private function getKeyWords($str){
		if(empty($str)) return;
		
		$str=str_replace(";", ",", $str);
		$str=str_replace(".", ",", $str);
		$str=str_replace(" ", ",", $str);
		$str=str_replace("，", ",", $str);
		$str=str_replace("；", ",", $str);
		$str=str_replace("|", ",", $str);
		$str=str_replace("\n", ",", $str);
		$str=str_replace("\t", ",", $str);
		
		$arr=explode(",", $str);
		$result=array();
		foreach($arr as $val){
			if(!empty($val)) $result[]=trim($val);
		}
		if(empty($result)) return;
		
		return $result;
	}
	
	/*处理副标题,副url*/
	private function slaveString($str){
		if(!strstr($str,"|"))return false;
		
		if(strstr($str,"\|")){
			$str=$this->encode($str);
			if(!strstr($str,"|")){
				return $this->decode($str);
			}		
		}
		$arr=explode("|",$str);
		$val=array($this->decode($arr[0]),$this->decode($arr[1]));
		return $val;
	}
		
	private function encode($str,$delimiter="|"){
		$str=str_replace("\{$delimiter}","<-*_*->",$str);
		return $str;
	}
	
	
	private function decode($str,$delimiter="|"){
		$str=str_replace("<-*_*->",$delimiter,$str);
		return $str;
	}
	
	/**
	 * 前台显示时获取
	 * 此函数接口于页面调用程序
	 * 
	 * @param int $page_id
	 * @param array $allparam
	 * @return array
	 */
	function getPicText($page_id,$allparam){
		$rs=$this->getRecord($page_id,$allparam["aid"]);
		return $rs;
	}
	
	function getFinalText($rs,$cfg){
		
		//过滤录入的内容
		$pool=array('title','summary');
		foreach($pool as $val){
			if(empty($rs[$val])) continue;
			$rs[$val] = trim($rs[$val]);
			$rs[$val] = strings::htmlFilter($rs[$val]);
		}
		
		//分页
		if(!empty($cfg['pages'])) $rs = $this->breakPage($rs);
		
		//结构目录
		if(!empty($cfg['catalog'])) $rs = $this->makeCatalog($rs);
		
		//生成标签
		if(!empty($cfg['labels'])) $rs['text'] = $this->makeLabels($rs['text']);
		
		//前后页
		if(!empty($cfg['context'])){
			$rs['prev']=$this->getPrev($rs['order'],$rs['mid']);
			$rs['next']=$this->getNext($rs['order'],$rs['mid']);
		}
		
		//整理HTML标签
		$rs['text'] = strings::tidy($rs['text']);
		
		return $rs;
	}
	
	//计算并返回图文单元的链接地址
	public function formatLink($val,$appinfo,$pageinfo){
		//debug::d($val);debug::d($appinfo);debug::d($pageinfo);
		
		//使用直接链接
		if( !empty($val['link']) ) return $val['link'];
		
		//在未指定url前缀的情况下使用内容ID
		if(!isset($val["id"])) func_throwException('系统参数错误！');
		if(empty($val["url"])) $val["url"]=$val["id"];
		
		$tmp=strtolower($val['url']);
		if(substr($tmp,0,7)=='http://'||strstr($tmp,'/')) {
			//使用直接链接
			$url= $val['url'];
		}else{
			//加入首页判断
			$pagePrefix=empty($pageinfo['mid'])?'html':$pageinfo['url'];
			
			//使用内容链接
			$url = "/{$pagePrefix}/{$appinfo['tpl']}/{$val['url']}.htm";
			
			if($appinfo['pagetype']!='page'){
				$url = "/{$appinfo['pagetype']}".$url;
			}
		}
		
		return $url;
	}
	
	//分页
	function breakPage($rs){
		$text=$rs['text'];
		
		//分页字串
		$IE_break = '<div style="PAGE-BREAK-AFTER: always"><span style="DISPLAY: none">&nbsp;</span></div>';
		$FF_break1 = '<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>';
		$FF_break = '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>';
		$text = str_replace($IE_break,$FF_break,$text);
		$text = str_replace($FF_break1,$FF_break,$text);
		$break = $FF_break;

		if ( strstr($text, $break) ) {
			//根据分页标识，分隔内容
			$temp = explode($break, $text);

			//总的分页数
			$rs["num"]=count($temp);

			//当前页面的文章内容
			$key=$rs["page"]-1;
			$rs["text"]=$temp[$key];

			//分页导航
			$list = array ();
			$base=dirname($_SERVER["REQUEST_URI"]);
			$ext=files::getExt($_SERVER["REQUEST_URI"]);
			
			for($i=1;$i<=$rs["num"];$i++){
				$url=$base."/".$_GET["url"]."_{$i}.{$ext}";
					
				if($rs["page"]==$i) $url='';
				$list[] = array (
						"num" => $i,
						"url" => $url,
				);
			}
			$rs["pagenav"]=$list;

		} else {
			$rs["num"] = 1;
			$rs["text"]=$text;
			$rs["pagenav"]='';
		}

		return $rs;
	}
	
	//上一篇
	function getPrev($order,$mid=0){
		$rs=$this->getOne(array('id','url','link','title'),array('SQL'=>" mid ={$mid} and `order`<$order",'order'=>array('order'=>'DESC')));
		if(empty($rs)) return null;
		$rs['title']=strings::htmlFilter($rs['title']);
		$rs['url'] = $this->getURL($rs);
		
		return $rs;
	}
	
	//下一篇
	function getNext($order,$mid=0){
		$rs=$this->getOne(array('id','url','link','title'),array('SQL'=>" mid ={$mid} and `order`>$order",'order'=>array('order'=>'ASC')));
		if(empty($rs)) return null;
		$rs['title'] = strings::htmlFilter($rs['title']);
		$rs['url'] = $this->getURL($rs);
		
		return $rs;
	}
	
	function getURL($val){
		//使用直接链接
		if( !empty($val['link']) ) {
			$ext=http::getPathInfoExt($val['link']);
			if(!in_array($ext,array('swf','flv',''))) return $val['link'];
			if( $val['link'] == '#') return '';//关闭此项链接
		}
		
		//在未指定url前缀的情况下使用内容ID
		if(!isset($val["id"])) func_throwException('系统参数错误！');
		if(empty($val["url"])) $val["url"]=$val["id"];
		
		$tmp=strtolower($val['url']);
		if(substr($tmp,0,7)=='http://'||strstr($tmp,'/')) {
			//使用直接链接
			$url= $val['url'];
		}else{
			//使用内容链接
			$ext=http::getPathInfoExt();
			$base=dirname($_SERVER["REQUEST_URI"]);
			$url=$base."/{$val['url']}.{$ext}";
		}
		
		return $url;
	}
	
}
?>