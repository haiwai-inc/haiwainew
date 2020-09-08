<?php
class page_sync{
	protected $obj,$app,$data;
	protected $appid;
	
	function __construct(){
		$this->obj=load('page_page');
		$this->app=load('page_cfg_app');
	}
	
	
	function syncData($sid,$tid){
		$this->data=load('page_data_unit');
		
		$this->initApp($sid,$tid);
		$this->doSyncData($sid,$tid);
	}
	
	
	function syncArticle($sid,$tid){
		$this->data=load('article_content');
		
		$this->initApp($sid,$tid,'composelist');
		$this->doSyncArticle($sid,$tid);
	}
	
	
	private function initApp($sid,$tid,$mod='pictext'){
		$rs=$this->app->getAll('*',array('SQL'=>"pid={$tid} OR pid={$sid}"));
		foreach($rs as $val){
			if($val['app']==$mod)
			$this->appid[$val['tpl']][$val['pid']]=$val['id'];
		}
	}
	
	
	private function doSyncData($sid,$tid){
		foreach($this->appid as $app){
			$src=$this->data->getAll('*',array('mid'=>$app[$sid]));
			foreach($src as $val){
				$data=$val;
				unset($data['id']);
				unset($data['mid']);
				unset($data['pageid']);
				unset($data['updatetime']);
				unset($data['order']);
				$data['mid']=$app[$tid];
				$data['pageid']=$tid;
				$data['updatetime']=time();
				$this->data->Insert($data);
				$this->debug( $data['title']."is OK!" );
			}
			$this->data->exec('Update data_unit set `order`=id where `order` is null');
		}
	}
	
	
	private function doSyncArticle($sid,$tid){
		foreach($this->appid as $app){
			$src=$this->data->getAll('*',array('mid'=>$app[$sid]));
			foreach($src as $val){
				$data=$val;
				unset($data['id']);
				unset($data['mid']);
				unset($data['pid']);
				unset($data['order']);
				$data['mid']=$app[$tid];
				$data['pid']=$tid;
				$this->data->Insert($data);
				$this->debug( $data['title']."is OK!" );
			}
			$this->data->exec('Update content set `order`=id where `order` is null');
		}
	}
	
	
	private function debug($msg,$line=false){
		if($line){
			echo "===== {$msg} ==========================================\n";
		}else{
			echo $msg."\n";
		}
		
		flush();
	}
	
}