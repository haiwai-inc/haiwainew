<?php
class page_plugins_composelist{
	static $pageinfo,$cateinfo;

	/**
	 * 接口页面调用程序
	 *
	 * $app: Array
		(
			[id] => 20
			[pid] => 5
			[tpl] => ttt
			[name] => 单录入内容
			[url] =>
			[app] => composelist
			[description] =>
			[status] => Y
			[param] => Array
			(
				[multi] => 1
				[picnums] => 0
				[itemnum] => 10
				[titleLength] => 20
				[sumaryLength] => 100
				[datetime] => none
				[type] => picture
				[width] => 200
				[height] => 150
				[picTitleLength] => 40
				[picSumaryLength] => 100
				[fields] => box,text
				[islist] => Y
			)
	
			[order] => 16
			[pagetype] => book
		)

	 * @param int $pid
	 * @param array $app
	 */
	function getObjValue($pid,$appinfo){
		$rs=array();
		
		//初始化要用到的各级类别信息
		if(!isset($this->pageinfo)) $this->pageinfo = $this->__getPageInfo($pid,$appinfo);  //当前页面
		if(!isset($this->cateinfo)) $this->cateinfo = $this->__getCateInfo($pid);			//全部文章分类

		//读取当前模块的内容
		$rs=$this->getTextFromCache($appinfo);
		$item=$list=$pics=array();

		if(!empty($rs)){
			$i=intval($appinfo['param']['multi']);
			$titlelen=empty($config['len'])?$appinfo['param']['titleLength']:$config['len'];
			
			if($i>1){//多级录入，可能关联图文
				
				$obj=load('article_article');
				if(!empty($appinfo['param']['picnums']))$unitObj=load( $appinfo['pagetype'].'_data_unit' );
				
				foreach($rs as $itemid=>$val){
					//子分类的信息
					$cateRS=$this->cateinfo[$itemid];
					$cateRS['link'] = $this->getMoreURL($cateRS,$appinfo);//格式化当前app下的更多链接
					$item[]=$cateRS;
					
					//对下级内容进行格式化
					if(!empty($val)){
						foreach($val as $k=>$v){
							if(!empty($titlelen))$v['title']=strings::subString($v['title'],$titlelen);
							$v["link"]=$v["url"]=$obj->formatUrl($v,$appinfo,$this->cateinfo[$itemid],$this->pageinfo);
							$val[$k]=$v;
						}
						$list[]=$val;
					
					
						//如果有图文单元设置，此处加载
						if( isset($unitObj) ){
							$pics[]=$unitObj->getRecord($pid,$appinfo,$itemid);
						}
					}
				}
				
				//多级分类的录入及图文,返回结果有两种分类
				$rs=array('item'=>$item,'list'=>$list,'pics'=>$pics);
				
			}else{//单级录入
				
				$obj=load("article_content");
				foreach($rs as $key=>$val){
					if(!empty($titlelen))$val['title']=strings::subString($val['title'],$titlelen);
					$val["link"]=$val["url"]=$obj->formatUrl($val,$appinfo,null,$this->pageinfo);
					$rs[$key]=$val;
				}
			}
		}
		
		return $rs;
	}

	private function __getPageInfo($pid,$appinfo){
		$pageinfo=null;
		$obj=load( $appinfo['pagetype'].'_page' );
		if(!empty($obj->pageinfo)){
			if($pid==$obj->pageinfo['id']) $pageinfo=$obj->pageinfo;
		}
		if(empty($pageinfo)){
			$pageinfo=$obj->getOne('*',array('id'=>$pid));
		}
		
		return $pageinfo;
	}

	private function __getCateInfo($pid){
		$level=load('article_level');
		$rs=$level->getAll('*',array('pid'=>$pid,'status'=>'Y','order'=>array('order'=>'DESC')));
		
		//对于没有多级文章分类的页面，此处返回一个空值用于设置$this->cateinfo，避免重复检查cateinfo
		if(empty($rs)) return array();

		$cateinfo=array();
		foreach($rs as $val){
			$cateinfo[$val['id']]=$val;
		}

		return $cateinfo;
	}

	//以一个PID为单位，一次性取出全部页面配置信息,并缓存
	private function getTextFromCache($appinfo){
		static $cache;

		if(!isset($cache)){
			//无子级分类的文章内容
			$cache['content'] = $this->__makelist( load("article_content") );

			//多级分类的文章内容
			$cache['article'] = $this->__initMultiArticleApp( load('article_article') );
		}

		//应用模块ID
		$appid=$appinfo['id'];

		//内容类型
		$i=intval($appinfo['param']['multi']);
		$type=($i>1)?'article':'content';

		//当前请求应用模块对应的内容
		$result=empty($cache[$type][$appid])?null:$cache[$type][$appid];
		
		//根据排序设置对内容重新排序，默认是倒序
		if(!empty($appinfo['param']['orderType'])){
			if($appinfo['param']['orderType']=='ASC') $result= strings::sortArray($result,'order','SORT_ASC');
		}

		return $result;
	}

	private function __makelist($obj){
		$fields=array('id','mid','title','summary','url','updatetime','cate','property','filltime','status','order');
		$where=array(
			'pid'=>$this->pageinfo['id'],
			'show'=>'Y',
			'status'=>'Y',
			'order'=>array('order'=>'DESC'),
		);

		$rs=$obj->getAll($fields,$where);
		$list=array();
		if(!empty($rs)){
			foreach($rs as $val){
				$val['updatetime']=empty($val['updatetime'])?null:times::getTime($val['updatetime']);
				$list[$val['mid']][]=$val;
			}
		}

		return $list;
	}
	
	//对于多级文章，还要进一步求得其对应的app信息
	private function __initMultiArticleApp($obj){
		//$list是以article_level主键为key的数组
		$list=$this->__makelist($obj);
		
		//通过之前设置的cateinfo信息,得到appid
		$temp=array();
		if(!empty($list)){
			//$k是文章分类的ID，$v是一个分类下的全部有效文章
			foreach($list as $k=>$v){
				$appid=$this->cateinfo[$k]['app'];
				$temp[$appid][$k]=$v;
			}
		}
		
		//按照cateinfo设置排序
		$result=array();
		foreach($this->cateinfo as $val){
			$result[$val['app']][$val['id']]=empty($temp[$val['app']][$val['id']])?'':$temp[$val['app']][$val['id']];
		}
		
		return $result;
	}
	
	private function getMoreURL($v,$appinfo){
		$sort=empty($v['url'])?$v['id']:$v['url'];
		
		//页面下的内容
		if( $v['cate']=='app' ){
			$prefix = ($this->pageinfo['categorise']=='Portal')?'/html':'/'.$this->pageinfo['url'];
			$subfix = ($this->pageinfo['categorise']=='Folder')?'.shtml':'.html';
			
			$url = $prefix."/{$appinfo['tpl']}/{$sort}/more".$subfix;
		}
		
		//站点下的功能内容
		if( $v['cate'] == 'mod' ){
			$url = "/html/{$appinfo['label']}/{$sort}/more.shtml";
		}
		
		return $url;
	}
	
}