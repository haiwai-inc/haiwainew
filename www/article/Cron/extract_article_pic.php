<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class extract_article_pic{
    function start(){
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_article_post=load("article_post");
        
        
        $lastid=0;
        while( $rs_article_indexing = $this->obj_article_indexing->getAll("*",['is_pic'=>0,'order'=>['postID'=>'ASC'],'limit'=>20,'postID,>'=>$lastid,'visible'=>1]) ){
            foreach($rs_article_indexing as $k=>$v){
                $lastid=$v['postID'];
                $post_tbn=substr('0'.$v['userID'],-1);
                
                $rs_article_post=$this->obj_article_post->getOne("*",['id'=>$v['postID']],"post_{$post_tbn}");
                
                //get image url form html
                $doc = new DOMDocument();
                @$doc->loadHTML($rs_article_post['msgbody']);
                $elements = $doc->getElementsByTagName('img');
                foreach($elements as $element) {
                    if(!empty($element->getAttribute('src'))){
                        $image=$element->getAttribute('src');
                        break;
                    }
                }
                
                //format image
                if(!empty($image)){
                    if(substr($image,0,8)=='/upload/'){
                        $image=file_domain.$image;
                    }
                    $is_pic=1;
                }else{
                    $is_pic=0;
                }
                $this->obj_article_indexing->update(['is_pic'=>$is_pic],['postID'=>$v['postID']]);
                
                //save image
                $path=DOCUROOT."/upload/article/".substr('0000'.$v['postID'],-2)."/".substr('0000'.$v['postID'],-4,-2);
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $filename=$v['postID']."_1";
                $rs_image=picture::saveImg($image,$path,$filename);
                echo $lastid."\n";
            }
        }
    }
}

$obj = new extract_article_pic();
$obj->start();


































































