<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/account/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class send_email{
	function start(){
		$obj_account_user_email=load("account_user_email");
		$lastid=0;
		while($rs_account_user_email=$obj_account_user_email->getAll("*",["visible"=>1,'id,>'=>$lastid,'limit'=>100,'order'=>['id'=>'ASC']])){
		    foreach($rs_account_user_email as $v){
				$lastid=$v['id'];
				$function=$v['function'];
				$success=$obj_account_user_email->$function($v['name'],$v['email'],unserialize($v['data']));
				$obj_account_user_email->update(['success'=>$success,'visible'=>0],['id'=>$v['id']]);
				
				echo "{$v['id']}\n";
			}
		}
	}
}
$obj = new send_email();
$obj->start();

























