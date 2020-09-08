<?php
class member_page_selectlist {
	
	protected $obj;
	
	function __construct(){
		$this->obj=load('member_user');
	}
	
	/**
	 * 页面中读取推荐内容
	 * @param int $pid
	 * @param array $app
	 * 
	 * Array
		(
		    [id] => 198
		    [pid] => 1
		    [tpl] => member
		    [name] => 推荐会员
		    [url] => 
		    [app] => selectlist
		    [description] => 
		    [status] => Y
		    [param] => Array
		        (
		            [app] => member
		            [param] => Array
		                (
		                    [type] => hot
		                )
		
		            [itemnum] => 9
		            [titleLengh] => 20
		            [sumaryLengh] => 100
		            [datetime] => none
		            [islist] => N
		        )
		
		    [order] => 198
		    [pagetype] => page
		)

	 */
	function getPageList($pid,$app,$pageapp){
		//TODO 建立复合缓存
		//static $cache;
		$dr=load( $pageapp.'_data_relation' );
		$rs = $dr->getAll('*',array('pid'=>$pid,'status'=>'Y','aid'=>$app['id'],'order'=>array('ord'=>'DESC')));

		if(!empty($rs)){
			$ids=array();
			foreach($rs as $v){$ids[]=$v['mid'];}
			$pos = $this->obj->getUser($ids,array('nickname','avatar','aboutme','point','totaldoc'));
			
			foreach($rs as $k=>$v){
				if(empty($pos[$v['mid']])) continue;
				$tmp= $pos[$v['mid']];
				$tmp['id']=$v['id'];   //推荐ID
				$tmp['order']=$v['ord'];  //推荐排序
				$tmp['status']=$v['status'];  //页面显示状态
				$rs[$k] = $tmp;
			}
		}
		
		return $rs;
	}

	/**
	 * @param $poolsObj
	 * some project extends page_data_relation
	 *
	 * @param $config: Array
		 (
			 [id] => 194
			 [pid] => 1
			 [tpl] => dianlu
			 [name] => 最新电路
			 [url] =>
			 [app] => selectlist
			 [description] =>
			 [status] => Y
			 [param] => Array
				 (
					 [app] => download
					 [param] => Array
						 (
							 [x] => 1
							 [y] => 2
							 [z] => 3
						 )
					 [itemnum] => 10
					 [titleLengh] => 20
					 [sumaryLengh] => 100
					 [datetime] => none
					 [tpl] => _index
					 [islist] => Y
				 )
			 	
			 [order] => 194
			 [pagetype] => page
		 )
	 *
	 * @return array $result: Array
		(
		//N条记录,由配置信息决定
		array(
			'id'=>'',
			'some_field_name'=>'some_field_value',
			...
			),
			.....
		)
		
		@param $obj 参数据单元对应的 data_relation对象
		@param $app 应用程序配置信息
	 */
	public function initItemList($obj,$app){
		$config = $app['param'];
		$result = ($config['listtype']=='list')? $this->show($obj,$app) : $this->pool($obj,$app) ;
		return $result;
	}

	//获得有序的推荐
	private function show($poolsObj,$config){
		$where = array(
			'pid'=>$config['pid'],//页面ID
			'aid'=>$config['id'],//应用ID
			'order'=>array('ord'=>'DESC')//排序
		);
		$conf = $config['param']['param'];
		$rs=$poolsObj->getList( '*', $where, empty($conf['pagesize'])?15:$conf['pagesize'] );
		
		if(!empty($rs)){
			$ids=array();
			foreach($rs as $v){$ids[]=$v['mid'];}
			$pos = $this->obj->getUser($ids);
			
			foreach($rs as $k=>$v){
				if(empty($pos[$v['mid']])) continue;
				$tmp= $pos[$v['mid']];
				$tmp['id']=$v['id'];   //推荐ID
				$tmp['order']=$v['ord'];  //推荐排序
				$tmp['status']=$v['status'];  //页面显示状态
				$tmp['idbox']=true;
				
				$rs[$k] = $tmp;
			}
		}
		
		//debug::d($rs);
		return $rs;
	}

	//获得所有备选的
	private function pool($poolsObj,$config){

		$conf = $config['param']['param'];
		if(empty($conf['type'])) $conf['type']='new';
		$rs = $this->obj->getList(array('id'), $this->getWhere($conf),empty($conf['pagesize'])?15:$conf['pagesize'] );
		$ids=array();
		foreach($rs as $v){$ids[]=$v['id'];}
		$list = $this->obj->getUser($ids);
		
		//检查当前数据是否已经推荐
		$pos=array();
		$tmp=$poolsObj->getAll('*',array('SQL'=>strings::idstr($list, 'id', 'mid')));
		if( !empty($tmp) ){
			foreach($tmp as $val){
				$pos[$val['mid']]=true;
			}
		}
		
		//对已经推荐的数据，取消选择项
		$result=array();
		foreach($rs as $key=>$val){
			$tmp=$list[$val['id']];
			$tmp['idbox']=(isset($pos[$val['id']]))?false:true;
			$result[]=$tmp;
		}
		
		return $result;
	}
	
	private function getWhere($conf){
		$where = array('id,!='=>0);
		if(isset($conf['type'])){
			if($conf['type']=='new') $where['order']=array('id'=>'DESC');
			if($conf['type']=='hot') {
				$order=empty($conf['order'])?'point':$conf['order'];
				$where['order']=array($order=>'DESC');
			}
		}else{
			foreach($conf as $key=>$val){
				$where[$key]=$val;
			}
		}
		
		return $where;
	}
	
	
	
}