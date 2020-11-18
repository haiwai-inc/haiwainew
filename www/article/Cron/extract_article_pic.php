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
        $this->obj_account_user=load("account_user");
        
        $lastid=0;
        while( $rs_article_indexing = $this->obj_article_indexing->getAll("*",['treelevel'=>0,'is_pic'=>0,'order'=>['postID'=>'ASC'],'limit'=>20,'postID,>'=>$lastid,'visible'=>1]) ){
            $postID_article_indexing=[];
            foreach($rs_article_indexing as $k=>$v){
                $postID_article_indexing[]=$v['postID'];
                $lastid=$v['postID'];
                $post_tbn=substr('0'.$v['userID'],-1);
                
                $rs_article_post=$this->obj_article_post->getOne("*",['id'=>$v['postID']],"post_{$post_tbn}");
                
                //get image url form html
                $doc = new DOMDocument();
                @$doc->loadHTML($rs_article_post['msgbody']);
                $elements = $doc->getElementsByTagName('img');
                $image="";
                foreach($elements as $element) {
                    if(!empty($element->getAttribute('src'))){
                        $image=$element->getAttribute('src');
                        break;
                    }
                }
                
                //format image
                if(!empty($image) && strlen($image)<1000){
                    //文学成本站图片
                    if(substr($image,0,8)=='/upload/' && !empty($v['wxc_postID'])){
                        $image="https://cdn.wenxuecity.com".$image;
                    }
                    
                    //save image
                    $dir="/upload/article/pic_1/".substr('0000'.$v['postID'],-2)."/".substr('0000'.$v['postID'],-4,-2);
                    $path=DOCUROOT.$dir;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $filename=$v['postID']."_headpic";
                    $rs_image=picture::saveImg($image,$path,$filename);
                    $is_pic=1;
                    
                    if(!empty($rs_image)){
                        //小图
                        $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_320_210",320,210);
                        $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_100_100",100,100);
                        
                        //更新分表
                        $this->obj_article_post->update(['pic'=>"{$dir}/{$rs_image}"],['id'=>$v['postID']],"post_{$post_tbn}");
                    }
                }else{
                    $is_pic=-1;
                }
                
                //更新索引
                $this->obj_article_indexing->update(['is_pic'=>$is_pic],['postID'=>$v['postID']]);
                
                echo $lastid."\n";
            }
            
            //更新ES图片地址
            $obj_article_noindex=load("search_article_noindex");
            $obj_article_noindex->fetch_and_insert($postID_article_indexing);
        }
    }
}

$obj = new extract_article_pic();
$obj->start();


































































