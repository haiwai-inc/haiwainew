<?php
require_once DOCUROOT.'/admin/page/Lib/page_page.php';
class site_site extends page_page{
	
	function init(){
		$domain=load("site_cfg_domain");
		
		$rs=$this->getOne("select * from page order by id");
		if(empty($rs)){
			//没有任何站点时执行初始化操作
			$initData=include DOCUROOT."/admin/site/Config/initData.php";
			$data=$initData['page'];
			$data['url']=conf('global','uid');
			$pid=$this->Insert($data);
			
			
			$data=array('pid'=>$pid,'domain'=>$_SERVER['HTTP_HOST'],'type'=>'domain','order'=>1);
			$domain->Insert($data);
			
			//添加默认的管理员
			$obj=load('passport_user');
			$passwd=$obj->passwd('666666');
			$obj->Insert(array('username'=>'weiqi','password'=>$passwd,'email'=>'weiqi228@gmail.com','nickname'=>'admin','userlevel'=>9));
		}else{
			//没有相关站点时返回，默认站点
			$dd=$domain->getOne('*',array('pid'=>$rs['id']));
			if(empty($dd)){
				alert("Site Init 500 Error!");
			}else{
				go('http://'.$dd['domain']);
			}
		}
		
	}
	
	
	/**
	 * 通过ID调用网站信息的接口
	 *
	 * @param int or int array $info
	 * 123 or array(1,2,3,4,5,6)
	 *
	 * @return array
	 * i.g.
	 * array(
	 * 	123=>array(
	 * 		'name'=>"somename",
	 * 		'slogan'=>"/data/user/somepic.jgp"
	 * 	)
	 * );
	 */
	function getSite($info,$filter=array()){
		$type='multi';

		if(empty($info)) return null;
		if(is_numeric($info)){
			if(isset($this->cache[$info])) return $this->cache[$info];
			
			$info=array($info);
			$type='single';
		}
		if(is_array($info)){
			$fields=empty($filter)?'*':$filter;
			if(is_array($filter)&&!empty($filter)){
				foreach(array('id','name') as $val){
					if(!in_array($val,$filter))$fields[]=$val;
				}
			}
			$rs=$this->getAll($fields,array('SQL'=>"id=".implode(' or id=',$info)));
			
			$list=array();
			$domainObj=load('site_cfg_domain');
			foreach($rs as $v){
				$domainInfo = $domainObj->getDomain( $v['id'] );
				$v['domain'] = $domainInfo['domain'];
				$v['url'] = "http://".$domainInfo['domain']."/";
				
				$this->cache[$v["id"]]=$v;
				if( $type=='single' ) return $v;
				$list[$v["id"]]=$v;
			}
			return $list;
		}
		return null;
	}
}
?>