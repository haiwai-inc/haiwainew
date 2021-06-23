<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class archive{
    function start($start_year){
        if(empty($start_year)){
            return;
        }
        
        $obj_article_indexing=load("article_indexing");
        $this->obj_article_post=load("article_post");
        $this->obj_account_user=load("account_user");
        
        //GMT时间
        date_default_timezone_set('GMT');
        $start_time=strtotime("01/01/".$start_year);
        $end_year=$start_year+1;
        $end_time=strtotime("01/01/".$end_year);
        $obj_article_archive=load("article_{$start_year}_indexing");
        
        //晓青 4287 1346
        while( $rs_article_indexing = $obj_article_indexing->getAll("*",['order'=>['create_date'=>'ASC'],'limit'=>20,'create_date,>'=>$start_time]) ){
            foreach($rs_article_indexing as $k=>$v){
                $start_time=$v['create_date'];
                
                unset($v['id']);
                $obj_article_archive->insert($v);
                
                echo date("Y-m-d, H-i-s",$start_time)."\n";
            }
            
            if($v['create_date']>$end_time){
                break;
            }
        }
    }
}

$year = empty($argv[1])?"":$argv[1];
$obj = new archive();
$obj->start($year);



































































