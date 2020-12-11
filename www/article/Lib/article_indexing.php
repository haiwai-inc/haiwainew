<?php
class article_indexing extends Model
{
    protected $tableName = "indexing";
    protected $dbinfo = array("config" => "article", "type" => "MySQL");

    //根据跟帖，补全主贴信息
    function get_article_info_by_comment($rs){
        if(empty($rs)){
            return [];
        }
        foreach($rs as $v){
            $typeID_account_notification[]=$v['typeID'];
            $hash_account_notification[$v['typeID']]=$v;
        }
        
        //评论信息
        $rs_article_indexing_comment=$this->getAll(['id','postID','basecode','userID','bloggerID','count_buzz','count_read','count_comment'],['OR'=>['postID'=>$typeID_account_notification]]);
        
        //加入评论ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_article_indexing_comment=$obj_search_article_noindex->get_postInfo($rs_article_indexing_comment);
        
        //加入评论用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing_comment=$obj_account_user->get_basic_userinfo($rs_article_indexing_comment,"userID");
        
        if(empty($rs_article_indexing_comment)){
            return [];
        }
        foreach($rs_article_indexing_comment as $v){
            $basecode_article_indexing_comment[]=$v['basecode'];
            $v['notification']=$hash_account_notification[$v['postID']];
            $hash_article_indexing_comment[$v['basecode']][]=$v;
        }
        
        //主贴信息
        $rs_article_indexing_main=$this->getAll(['id','postID','basecode','userID','bloggerID','count_buzz','count_read','count_comment'],['treelevel'=>0,'OR'=>['basecode'=>$basecode_article_indexing_comment]]);
        if(empty($rs_article_indexing_main)){
            return [];
        }
        
        //加入主贴ES信息
        $rs_article_indexing_main=$obj_search_article_noindex->get_postInfo($rs_article_indexing_main);
        
        //加入主贴用户信息
        $rs_article_indexing_main=$obj_account_user->get_basic_userinfo($rs_article_indexing_main,"userID");
        
        foreach($rs_article_indexing_main as $k=>$v){
            $rs_article_indexing_main[$k]['comment']=$hash_article_indexing_comment[$v['basecode']];
        }
        
        return $rs_article_indexing_main;
    }
    
    //补全帖子信息
    function get_basic_articleinfo($rs_article_indexing,$k,$v){
        //添加标题内容图片
        $post_tbn=substr('0'.$v['userID'],-1);
        $obj_article_post=load("article_post");
        $rs_article_post=$obj_article_post->getOne("*",['id'=>$v['postID']],"post_".$post_tbn);
        if(!empty($rs_article_post)){
            foreach($rs_article_post as $kk=>$vv){
                $rs_article_indexing[$k][$kk]=$vv;
            }
	    $rs_article_indexing[$k]['msgbody_origin']=$rs_article_post['msgbody'];
        }

        //添加点赞
        $post_buzz_tbn=substr('0'.$v['postID'],-1);
        $obj_article_post_tag=load("article_post_tag");
        $rs_article_post_buzz=$obj_article_post_tag->getAll("*",['postID'=>$v['postID']],"post_buzz_".$post_buzz_tbn);
        $rs_article_indexing[$k]['buzz']=[];
        if(!empty($rs_article_post_buzz)){
            foreach($rs_article_post_buzz as $kk=>$vv){
                $rs_article_indexing[$k]['buzz'][]=$vv['userID'];
            }
        }
        
        //添加标签
        $post_tag_tbn=substr('0'.$v['postID'],-1);
        $obj_article_post_buzz=load("article_post_buzz");
        $rs_article_post_tag=$obj_article_post_buzz->getAll("*",['postID'=>$v['postID']],"post_tag_".$post_tag_tbn);
        $rs_article_indexing[$k]['tags']=[];
        if(!empty($rs_article_post_tag)){
            foreach($rs_article_post_tag as $kk=>$vv){
                $rs_article_indexing[$k]['tags'][]=$vv['tagID'];
            }
        }
        
        return $rs_article_indexing;
    }
    
    
    //在包含postID的数组里，补全帖子的 ,点赞计数，留言计数，阅读计数
    function get_article_count($rs,$hashID='postID'){
        if(!empty($rs)){
            foreach($rs as $v){
                $id_rs[]=$v[$hashID];
            }
            
            //点赞计数，留言计数，阅读计数
            $rs_article_count=$this->getAll(['postID','count_buzz','count_read','count_comment'],['OR'=>['postID'=>$id_rs]]);
            if(!empty($rs_article_count)){
                foreach($rs_article_count as $v){
                    $hash_article_count[$v['postID']]=$v;
                }
                foreach($rs as $k=>$v){
                    $rs[$k]["countinfo_{$hashID}"]=$hash_article_count[$v['postID']];
                }
            }
        }
        
        return $rs;
    }
    
	public function format_string($rs, $keys = ["msgbody"], $max_character = 1000){
		foreach($rs as $k=>$v){
			foreach($keys as $key){
				$v[$key] = strip_tags($v[$key]);
				if(!empty($max_character))
					$v[$key] = strings::subString($v[$key], $max_character);
			}
			$rs[$k] = $v;
		}
		return $rs;
	}
	
	public function format_pic($rs, $key="pic"){
	    foreach($rs as $k=>$v){
	        //大图+小图
	        $rs[$k]["o_pic"] = $v[$key];
	        $rs[$k]["s_pic"] = str_replace("{$v['postID']}_headpic","{$v['postID']}_headpic_100_100",$v[$key]);
	        $rs[$k]["pic"] = str_replace("{$v['postID']}_headpic","{$v['postID']}_headpic_320_210",$v[$key]);
	    }
	    return $rs;
	}

	//验证发帖
	public function article_add_validation($data){
	    if(empty($data['typeID'])){
	        return false;
	    }
	    
	    //检查博客
        if($data['typeID']==1){
            if(empty($data['bloggerID'])){
                return false;
            }
            
	        $obj_blog_blogger=load("blog_blogger");
	        $check_blog_blogger=$obj_blog_blogger->getOne(['id'],['status'=>1,'id'=>$data['bloggerID']]);
	        if(empty($check_blog_blogger)){
	            return false;
	        }
	    }
	    
	    return true;
	}
	
	//验证回复
	public function article_reply_validation($data){
	    if(empty($data['postID']) || empty($data['typeID'])){
	        return false;
	    }
	    
	    //检查主贴
	    $check_article_indexing=$this->getOne(['id'],['postID'=>$data['postID']]);
	    if(empty($check_article_indexing)){
	        return false;
	    }
	    
	    return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
