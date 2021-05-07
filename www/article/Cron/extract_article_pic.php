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
        while( $rs_article_indexing = $this->obj_article_indexing->getAll("*",['is_pic'=>0,'order'=>['postID'=>'ASC'],'limit'=>20,'postID,>'=>$lastid,'visible'=>1]) ){
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
                $image_pool=[];
                foreach($elements as $element) {
                    if(!empty($element->getAttribute('src'))){
                        $image_pool[]=$element->getAttribute('src');
                    }
                }
                
                //更新头图
                $count=0;
                if(!empty($image_pool)){
                    foreach($image_pool as $kk=>$vv){
                        //字符串图片类型暂不处理
                        if(strlen($vv)>1000){
                            continue;
                        }
                        
                        //文学城本站图片
                        if(substr($vv,0,8)=='/upload/'){
                            if(!empty(strpos($vv, '/article/pic/blog/'))){
                                $image="http://beta.haiwai.com".$vv;
                            }else{
                                $image="https://cdn.wenxuecity.com".$vv;
                            }
                        }else{
                            $image=$vv;
                        }
                        
                        //保存头图
                        $obj_article_pic=load("article_pic");
                        $rand=$obj_article_pic->random_string(15);
                        $filename=$rand."_".$count;
                        $dir="/upload/article/pic/blog/".substr($rand,-8,-6)."/".substr($rand,-6,-4)."/".substr($rand,-4,-2)."/".substr($rand,-2);
                        $path=DOCUROOT.$dir;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $rs_image=picture::saveImg($image,$path,$filename);
                        if(!empty($rs_image)){
                            //头图压缩为小图
                            if($count==0){
                                $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_320_210",320,210);
                                $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_100_100",100,100);
                                
                                //更新分表
                                $this->obj_article_post->update(['pic'=>"{$dir}/{$rs_image}"],['id'=>$v['postID']],"post_{$post_tbn}");
                                $is_pic=1;
                            }
                            
                            //替换文章内容图片路径
                            $rs_article_post['msgbody']=str_replace($vv,"{$dir}/{$rs_image}", $rs_article_post['msgbody']);
                            $count++;
                        }else{
                            $is_pic=-1;
                        }
                    }
                }else{
                    $is_pic=-1;
                }
                
                //清空图片
                if($is_pic==-1){
                    $this->obj_article_post->update(['pic'=>""],['id'=>$v['postID']],"post_{$post_tbn}");
                }
                
                //更新索引
                $this->obj_article_indexing->update(['is_pic'=>$is_pic],['postID'=>$v['postID']]);
                
                //更新文章内容
                $this->obj_article_post->update(['msgbody'=>$rs_article_post['msgbody']],['id'=>$v['postID']],"post_{$post_tbn}");
                
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

shell_exec("/bin/chown www-data:www-data ".DOCUROOT . "/upload/article/pic/blog");


































































