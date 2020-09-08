<?php
class page_plugins_html{
	protected $obj;
	
	function __construct(){
		$this->obj=load("page_data_relation");
	}
	
	/**
	 * 
	 * @param $rs  存储的推荐数据
	 * @param $id  原数据主键对应的存储字段
	 * @param $mid  原数据主键
	 * 
	 * @return string
	 */
	function idstr($rs,$id='aid',$mid='id'){
		if( empty($rs) )return "{$mid}=0";
		
		$ids=array();
		foreach($rs as $val){
			$ids=$val[$id];
		}
		
		$str="{$mid}=".implode(" or {$mid}=",$ids);
		return $str;
	}
}