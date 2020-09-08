<?php
class page_cfg_authz extends Model{
	protected $tableName = 'cfg_authz';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	public function getAuthzInfo($pid,$uid){
		static $info;
		
		$key = $pid.'_'.$uid;
		if( !isset($info[$key]) ) $info[$key] = $this->getOne("*",array("pid"=>$pid,'uid'=>$uid));
		
		return $info[$key];
		
	}
}
?>