<?php
class site_cfg_domain extends Model{
	public static $cache=array();
	protected $tableName = 'cfg_domain';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	
	
	function __construct(){
		$this->objPrefix=($this->dbinfo['config']=='main')?'site':$this->dbinfo['config'];
		parent::__construct();
	}
	
	/**
	 * 清理站点应用程序建立的缓存
	 * 调用方式
	 * $obj=load('page_cfg_domain','admin/page');
	 * $obj->clearCache($pid,$url,$deep);
	 *
	 * @param 站点ID $pid
	 * @param 应用程序的URL，如 /article/ $url
	 * @param 应用程序设置的缓存目录  $deep
	 * @return array  清理的结果
	 */
	public function clearCache($pid,$url,$deep){
		$cache=new Cache();
		$rs=$this->getAll('*',array('type'=>'domain','pid'=>$pid));
		
		$list=array();
		foreach($rs as $val){
			$list[$val['domain']]=$cache->removeCache($val['domain'],$url,$deep);
		}
		
		return $list;
	}
	
	function getVisitUrl($pageinfo){
		if(empty($pageinfo))$pageinfo=conf('global','uid');
		
		if( $pageinfo['categorise']=='Portal' ||$pageinfo['categorise']=='Folder' ||$pageinfo['categorise']=='Alias' ){
			//使用main库的情况
			$pid=($pageinfo['mid']==0)?$pageinfo['id']:$pageinfo['mid'];
		}else{
			//调用单元库的情况
			$pid=$pageinfo['id'];
		}	
		$domain=$this->getDomain($pid);
		
		$HTTP_HOST=empty($domain)?$_SERVER['HTTP_HOST']:$domain["domain"];
		
		if($pageinfo["categorise"]=="Portal" && $domain["type"]=="domain"){			
			$url="http://".$HTTP_HOST."/";
			return $url;
		}
		
		if($pageinfo['categorise']=='Portal'){//首页
			$url="/";
		}elseif($pageinfo['categorise']=='Folder' ||$pageinfo['categorise']=='Alias'){//一级页面
			$url="/".$pageinfo["url"]."/";
		}else{//二级页面
			$url="/".$pageinfo["categorise"]."/".$pageinfo["url"]."/";
		}
		
		$url="http://".$HTTP_HOST.$url;
		
		return $url;
	}
	
	function getVisitUrlByID($page_id){
		$page=load($this->objPrefix."_page");
		$pageinfo=$page->getResult($page_id);
		return $this->getVisitUrl($pageinfo);
	}
	
	function getDomain($pid){
		if(empty($this->cache[$pid])){
			$this->cache[$pid] = $this->getOne("*",array('pid'=>$pid,'order'=>array("order"=>"ASC")));
		}
		return $this->cache[$pid];
	}
	
	function delDomain($where){
		$rs=$this->getAll("*",array('SQL'=>'id='.implode(' or id=',$ids)));
		$p=load($this->objPrefix."_page");
		$page=$p->getResult($where['pid']);
		
		if(!empty($rs)){
			foreach($rs as $val){				
				if($page["visit"]=="alias"){
					$aliasfile = DOCUROOT."/cache/page/{$val["domain"]}/{$page["url"]}.php";
					if(file_exists($aliasfile)) @unlink($aliasfile);
				}else{
					$domainfile= DOCUROOT."/cache/page/{$page["url"]}.{$val["domain"]}.php";
					if(file_exists($domainfile)) @unlink($aliasfile);
				}
			}
			
		}
		
		$this->Remove(array('pid'=>$this->tmp['pid'],'SQL'=>'id='.implode(' or id=',$ids)));
	}
	
	/**
	 * 设置域名访问的cache
	 * @return cache
	 */
	function PageDomain( $flag="get",$lb ){
		if(empty($lb)) return false;
		$filename=DOCUROOT."/cache/page/pagedomain_{$lb}.php";

		//获取记录
		if( $flag=="get"&&file_exists( $filename ) ){
			return include($filename);
		}

		//设置缓存
		$list=$this->getCacheRs($lb);

		$tpl=array();
		$content="<?php \nreturn array(\n";
		foreach($list as $val){
			if(!empty($val["info"])){
				$val["info"]=str_replace(" ","",$val["info"]);
				$temp=explode(",",$val["info"]);
				if(!empty($temp)){
					foreach($temp as $v){
						if(!in_array($v,$tpl)){
							$content.="'".$v."',\n";
							$tpl[]=$v;
						}
					}
				}
			}
		}
		$content.=");\n?>";
		$status=file_put_contents($filename,$content);

		if( $flag=="get") {
			return $tpl;
		}else{
			return $status;
		}
	}

	/**
	 * 循环获取用于生成域名访问的缓存数组
	 *
	 * @param string $lb 所属分类
	 * @return array
	 */
	private function getDomainRs($lb){
		$list = array();
		$i=0;
		while(true){
			$rs=$this->getAll(array("tpl"),array("categorise"=>$lb),array("id"=>"ASC"),array($i*50,50));
			if(empty($rs)){
				break;
			}
			foreach($rs as $val){
				$list[]=$val;
			}
			$i++;
		}
		//生成缓存目录
		if(!is_dir(DOCUROOT."/cache/page/"))files::mkdirs(DOCUROOT."/cache/page/");
		return $list;
	}

}
?>