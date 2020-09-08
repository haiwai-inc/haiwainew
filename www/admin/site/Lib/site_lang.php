<?php
class site_lang{

	private $siteID;
	private $langType;

	/**
	 * 语言读取函数,语言系统是根据网站UID来定义，即一个或多个网站对应一套label系统
	 * 如果key是顶级标签返回该标签下的全部数组，其它指定标签直接返回标签值
	 *
	 * $key = "all"; 全部语言数组
	 * array('a','b','c','d','region.china.beijing.haidian','conf:demo:service.api.demo'); 指定多个数组
	 * $key = "region.china.beijing.haidian"; 指定键值
	 * $key = "conf:demo:service.api.demo"; 调用项目配置文件
	 * @param string $key //键值
	 * @param string $default //默认值
	 * @param string $langbase //语言模板,如cndzz,blyz,default ...
	 * @param string $type //返回值类型：名称/name(短)，说明/note(长)
	 */
	function getLang($key,$default='',$langbase='default',$type='name'){

		//获取当前语言模板
		$langbase = empty($langbase)?'default':$langbase;
		$this->getSiteID($langbase);

		//指定返回值类型
		$this->langType = in_array($type,array('name','note'))?$type:"name";

		if(is_array($key)){
			//检查是否有本地文件中定义的语言数据
			$tmp = $this->getLocalConf($key);
			
			$key= $tmp['key'];
			$conf= $tmp['conf'];
				
			//全局标签中定义的语言数据
			$val=$this->getGlobalLabel($key);
			
			if(!empty($conf))$val = array_merge($val,$conf);
		}else{
			$val = array();
			
			if($key=='all'){//全部语言数组
				$lst = label('all',$this->siteID);
				$val = $this->getArr($lst);
			}
			
			//读取本地配置文件
			if(substr($key,0,5)=='conf:'){
				$arr=explode(':',$key);
				$val[$arr[1]]=conf($arr[2]);
			}
			
			if(empty($val)){//指定键值
				$val = $this->langVal( label($key,$this->siteID) );

				//处理顶级标签下的情况
				if( is_array( $val )&&!empty($val) ) {
					$tmp = label('all',$this->siteID);
					$tmpall = label('all',0);
					$val['.'] = empty($tmp[$key][$this->langType])?$tmpall[$key][$this->langType]:$tmp[$key][$this->langType];
				}
			}
		}

		return $val;
	}
	
	private function getLocalConf($key){
		
		$conf=array();
		foreach($key as $k=>$v){
			if(substr($v,0,5)=='conf:'){
				unset($key[$k]);
					
				$arr=explode(':',$v);
				$conf[$arr[1]]=conf($arr[2]);
			}
		}
		
		$result = array('key'=>$key,'conf'=>$conf);
		return $result;
	}
	
	private function getGlobalLabel($key){
		$tmp = label('all',$this->siteID);
		$tmpall = label('all',0);
		$lst=array();
		if(!empty($key)){
			foreach($key as $k){
				$tmpAllPosVal=empty($tmpall[$k])?'':$tmpall[$k];
				$lst[$k]=empty($tmp[$k])?$tmpAllPosVal:$tmp[$k];
			}
		}
		$val=$this->getArr($lst);//指定多个数组
		
		return $val;
	}

	private function getSiteID($langbase){
		static $sitearr;

		if(empty($langbase) || $langbase==conf( 'global','uid' )){
			$siteID = conf( 'global','lid' );
		}else{
			if(isset($sitearr[$langbase])){
				$siteID = $sitearr[$langbase];
			}else{
				$obj=load('site_site');
				$rs=$obj->getOne(array('id'),array('url'=>$langbase));
				if(empty($rs)) $rs['id'] = conf( 'global','lid' );
					
				$siteID = $sitearr[$langbase] = $rs['id'];
			}
		}

		$this->siteID = $siteID;
	}

	private function getArr($arr){
		$val=array();

		//读取指定的全部语言定义
		foreach($arr as $k=>$v){
			$tmp=$this->langVal( label($k,$this->siteID) );
			if(!empty($tmp)){
				$val[$v['idname']]=$tmp;
				$val[$v['idname']]['.']=$v[$this->langType];
			}
		}

		return $val;
	}

	/*
	 * 循环获取全部语言变量
	 */
	private function langVal($val){
		if(empty($val)) return;
		if(!is_array($val)) return;

		$list=array();
		foreach($val as $k=>$v){
			//输入的val即是最后一级的情况
			if(!isset($v['idname'])) return $val[$this->langType];
			if(isset($v['sublist'])){
				//正常情况
				if(is_array($v['sublist'])){
					$list[$k] = $this->langVal($v['sublist']);
					$list[$k]['.'] = $v[$this->langType];
				}else{
					$list[$k] = $v[$this->langType];
				}
			}else{
				$list[$k] = $v[$this->langType];
			}
		}

		return $list;
	}
}
?>