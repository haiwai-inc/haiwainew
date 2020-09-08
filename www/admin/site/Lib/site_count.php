<?php
/**
 * 计数器原理
 * 变实时更新实时显示为着时更新实时显示。
 * 如果中间出现memcache死机的情况，最多损失一个统计周期的数据，但可以有效的减少服务器的负载
 * 循环更新时，要判断目标值和原始值的大小，再判断使用相加更新还是替换更新
 * 
 * 1、使用memcache实时显示及记录用户访问数
 * 2、使用mysql在各相关的类型表中永久记录统计结果
 * 3、在各程序的调用环节使用getNum或setNum
 * 4、定时执行当前类，汇总临时数据到永久表中
 * 
 * 汇总时执行：
 * 1、转移数据到临时表 count_tmp
 * 2、为了尽量减少对用户访问的影响，立即重建数据记录表 count
 * 3、对临时表count_tmp加索引
 * 4、遍历临时表count_tmp
 * 5、在遍历的过程中先执行更新，后清除缓存
 * 6、最后删除临时表count_tmp
 * 
 * @author weiqi
 *
 */
class site_count extends Model {
	protected $tableName="count";
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	
	function init($type,$id){
		$this->Insert(array('type'=>$type,'mid'=>$id,'datetime'=>time()));
	}
	
	
	//定时创建日志记录表
	function createTB(){
		//创建临时计算表
		$this->exec("RENAME TABLE `count` TO `count_tmp`;");
		
		//创建新的记录表
		$sql = "
				CREATE TABLE IF NOT EXISTS `count` (
				  `mid` int(12) NOT NULL,
				  `type` varchar(50) NOT NULL,
				  `datetime` int(12) NOT NULL
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			   ";
		$this->exec($sql);
		
		//对临时表加索引
		$this->exec( "ALTER TABLE `count_tmp` ADD INDEX ( `type` );" );
	}
	
	//定时更新永久记录表
	function doUpdate(){
		$this->createTB();
		
		$obj=load('site_count_tmp');
		$typelist=$obj->getAll("SELECT db FROM count_tmp GROUP BY db");
		
		if(empty($typelist)) return;
		
		$config = conf('admin.site.countInfo');
		foreach($typelist as $value){
			$conn=load( $config[$value['type']]['obj'] );
			
			$i=0;
			$start=0;
			while ($rs=$obj->getAll("select * from count_tmp where `type`={$value['type']} limit {$start},50")){
				$i++;
				$start=$i*50;
				
				//进行更新
				$this->$value['db']($conn,$rs,$config[$value['type']]['field']);
				
				if( $start%2000==0)	$obj->resetConn();
				
			}
		}
		
		$this->exec( "DROP TABLE `count_tmp`;" );
	}
	
	function do_bbs($conn,$rs,$field){
		
		foreach ($rs as $val){
			$db->Update( array('SQL'=>'SET `xxx`= ') , array( 'id'=>$val['mid'] ));
		}
	}
}
