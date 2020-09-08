<?php
class page_page extends Model{
	protected $tableName = 'page';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	public $pageinfo;//用于中间计算，缓存当前page的基本信息

	//用页面下单元更多内容页面的程序调用
	public function getPage($mid,$url,$type='page'){
		static $cache;
		$id=$mid.$url;
		if(empty($cache[$id])){
			if($url=='html'){
				$cache[$id]=$this->getResult($mid);
			}else{
				$where=array(
					'url'=>$url,
					'status,!='=>'N',
				);
				if($type=='page'){
					$where['mid']=$mid; //站点下的page使用mid作为关联标识
				}else{
					$where['pid']=$mid; //其它page使用pid作为关联标识
				}
				$cache[$id]=$this->getOne('*',$where);
			}
		}
		return $cache[$id];
	}

	//检查当前page是否为空，没有其它关联内容
	function checkDel($pid){
		//TODO 加入判断条件
		return 0;
	}

	//检查page是否存在
	public function page_exists($url,$id,$categorise,$mid=0){
		if(empty($url))	return false;

		//检查page表中是否已经存在同类站点
		$where=array(
			"url"=>$url,
			"id,!="=>intval($id),
		);
		
		//对需要检测mid的项目增加mid
		if( $mid !='all') $where['mid'] = $mid;
		
		$where=$this->getWhereCategorise($where,$categorise);

		$rs= $this->getOne(array("id"),$where);
		if(!empty($rs)) return 'page';

		//检查用户表中是否已经存在要申请的用户名称
		if($categorise=='Alias'){
			$user = load("passport_user","passport");
			$rs= $user->getOne(array("id"),array('username'=>$url));
			if(!empty($rs)) return 'user';
		}
		return false;
	}

	//检查page是否存在
	public function sub_page_exists($url,$mid,$categorise){
		if(empty($url))	return false;

		//检查page表中是否已经存在同类站点
		$where=array(
				"url"=>$url,
				"mid"=>intval($mid),
		);
		$where=$this->getWhereCategorise($where,$categorise);

		$rs= $this->getOne(array("id"),$where);
		if(!empty($rs)) return 'page';

		if(isset($where["SQL"])){
			//检查用户表中是否已经存在要申请的用户名称
			$user = load("passport_user","passport");
			$rs= $user->getOne(array("id"),array('username'=>$url));
			if(!empty($rs)) return 'user';
		}

		return false;
	}

	function getWhereCategorise($where,$categorise){
		switch ($categorise){
			case 'Alias':
				$where["SQL"]="categorise in('Alias','Folder')";
				break;
			case 'Folder':
				$where["SQL"]="categorise in('Alias','Folder')";
				break;
			default:
				$where["categorise"]=$categorise;
				break;
		}

		return $where;
	}
}
?>