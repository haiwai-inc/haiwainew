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
	
}
