<?php
class article_indexing extends Model
{
    protected $tableName = "indexing";
    protected $dbinfo = array("config" => "article", "type" => "MySQL");

    //archive分表
    function getOne($condition,$where=NULL,$table=NULL){
        $obj_archive=load("article_2020_indexing");
        $archive_pool=conf("article.archive_maping");
        
        $rs_article_indexing=parent::getOne($condition,$where);
        if($table!="archive"){
            //return $rs_article_indexing;
        }
        
        if(empty($rs_article_indexing)){
            foreach($archive_pool as $k=>$v){
                $rs_article_indexing=$obj_archive->getOne($condition,$where,"{$k}_indexing");
                if(!empty($rs_article_indexing)){
                    break;
                }
            }
        }
        
        return $rs_article_indexing;
    }
    
    //archive分表
    function update($condition,$where=NULL,$table=NULL,$all=NULL){
        $obj_archive=load("article_2020_indexing");
        $archive_pool=conf("article.archive_maping");
        
        if(!empty($all)){
            //全部循环更新archive
            parent::update($condition,$where);
            foreach($archive_pool as $k=>$v){
                $tbn="{$k}_indexing";
                $id=$obj_archive->update($condition,$where,$tbn);
            }
        }else{
            //循环查询更新archive
            $rs_article_indexing=parent::getOne(['id','postID','create_date'],$where);
            if(!empty($rs_article_indexing)){
                $id=parent::update($condition,$where);
            }else{
                $rs_article_indexing=$this->getOne(['id','postID','create_date'],$where);
                $tbn=gmdate('Y',$rs_article_indexing['create_date'])."_indexing";
                $id=$obj_archive->update($condition,$where,$tbn);
            }
        }
        
        return $id;
    }
    
    //archive分表
    function getAll($condition,$where=NULL,$table=NULL){
        $obj_archive=load("article_2020_indexing");
        $archive_pool=conf("article.archive_maping");
        
        if($table=="indexing"){
            $rs_article_indexing=parent::getAll($condition,$where);
            return $rs_article_indexing;
        }
        
        $rs_article_indexing=[];
        if(!empty($where) && !array_key_exists('OR',$where)){
            //list查询
            $rs_article_indexing=parent::getAll($condition,$where);
            $limit=$where['limit'];
            if(!empty($limit) && count($rs_article_indexing)<$limit){
                foreach($archive_pool as $k=>$v){
                    $rs_archive=$obj_archive->getAll($condition,$where,"{$k}_indexing");
                    if(!empty($rs_archive)){
                        $rs_article_indexing=array_merge($rs_article_indexing,$rs_archive);
                    }
                    
                    //达到分页条数后断开
                    $where['limit']=$limit-count($rs_article_indexing);
                    if($where['limit']<=0){
                        break;
                    }
                }
            }
        }else{
            //解析OR
            $key=key($where['OR']);
            $id_rs=array_pop($where['OR']);
            if(empty($id_rs)){
                return [];
            }
            
            //生成select
            if($condition=="*"){
                $select_sql="SELECT * ";
            }else{
                foreach($condition as $v){
                    if(empty($select_sql)){
                        $select_sql="SELECT {$v} ";
                    }else{
                        $select_sql.=",{$v} ";
                    }
                }
            }
            
            //生成where
            foreach($id_rs as $v){
                if(empty($where_sql)){
                    $where_sql="WHERE ( {$key}={$v} ";
                }else{
                    $where_sql.="or {$key}={$v} ";
                }
            }
            $where_sql.=") ";
            unset($where['OR']);
            if(!empty($where)){
                foreach($where as $k=>$v){
                    if($k=="order"){
                        continue;
                    }
                    $where_sql.="and `{$k}`={$v} ";
                }
            }
            
            //组合全部sql
            $sql=$select_sql."FROM indexing ".$where_sql;
            foreach($archive_pool as $k=>$v){
                $sql.="UNION ALL ".$select_sql."FROM {$obj_archive->conn->config['database']}.{$k}_indexing ".$where_sql;
            }
            if(!empty($where['order'])){
                $sql.="order by ";
                foreach($where['order'] as $k=>$v){
                    $sql.="`{$k}` {$v} ";
                }
            }
            
            
            $rs_article_indexing=parent::getAll($sql);
        }
        return $rs_article_indexing;
    }
    
    //根据跟帖，补全主贴信息
    function get_article_info_by_comment($rs_account_notification){
        if(empty($rs_account_notification)){
            return [];
        }
        foreach($rs_account_notification as $v){
            $typeID_account_notification[]=$v['typeID'];
            $hash_account_notification[$v['typeID']]=$v;
        }
        
        //评论信息
        $rs_article_indexing_comment=$this->getAll("*",['order'=>['postID'=>"DESC"],'OR'=>['postID'=>$typeID_account_notification]]);
        
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
        $rs_article_indexing_main=$this->getAll("*",['treelevel'=>0,'OR'=>['basecode'=>$basecode_article_indexing_comment]]);
        if(empty($rs_article_indexing_main)){
            return [];
        }
        
        //加入主贴ES信息
        $rs_article_indexing_main=$obj_search_article_noindex->get_postInfo($rs_article_indexing_main);
        
        //加入主贴用户信息
        $rs_article_indexing_main=$obj_account_user->get_basic_userinfo($rs_article_indexing_main,"userID");
        
        //合并主贴和评论
        foreach($rs_article_indexing_main as $k=>$v){
            $rs_article_indexing_main[$k]['comment']=$hash_article_indexing_comment[$v['basecode']];
        }
        
        //排序
        $rs=[];
        foreach($rs_article_indexing_main as $v){
            $key = $v['comment'][0]['postID'];
            $rs[$key]=$v;
        }
        krsort($rs);
        $rs=array_values($rs);
        
        return $rs;
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
	        $rs_article_indexing[$k]['msgbody']=strip_tags($rs_article_post['msgbody']);
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
			    if(empty($v[$key])){
			        continue;
			    }
			    $v[$key] = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/is', "$1$3", $v[$key]);
			    $v[$key] = strip_tags($v[$key]);
				if(!empty($max_character)){
				    $v[$key] = strings::subString($v[$key], $max_character);
				}
			}
			$rs[$k] = $v;
		}
		return $rs;
	}
	
	public function format_pic($rs, $key="pic"){
	    foreach($rs as $k=>$v){
	        //大图+小图
	        $rs[$k]["o_pic"] = $v[$key];
	        $rs[$k]["s_pic"] = str_replace("_0","_0_100_100",$v[$key]);
	        $rs[$k]["pic"] = str_replace("_0","_0_320_210",$v[$key]);
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
	
	//验证用户修改帖子信息
	public function article_update_validation($data){
	    if(empty($data['postID']) || empty($data['typeID'])){
	        return false;
	    }
	    
	    //检查主贴
	    $check_article_indexing=$this->getOne(['id','userID','bloggerID'],['postID'=>$data['postID']]);
	    if(empty($check_article_indexing)){
	        return false;
	    }
	    
	    if($check_article_indexing['userID']!=$_SESSION['id']){
	        return false;
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
	
	//前端微调
	public function format_article_view_comment($rs_article_indexing,$userID){
	    if(!empty($rs_article_indexing)){
	        foreach($rs_article_indexing as $k=>$v){
	            $rs_article_indexing[$k]['has_author']=false;
	            if(!empty($v['reply'])){
	                foreach($v['reply'] as $vv){
	                    if($vv['userID']==$userID){
	                        $rs_article_indexing[$k]['has_author']=true;
	                        break;
	                    }
	                }
	            }
	        }
	    }
	    
	    return $rs_article_indexing;
	}
	
	//图片域名处理
	public function add_image_domian($msgbody){
	    if(empty($msgbody)){
	        return $msgbody;
	    }
	    
	    //文学城域名
	    $doc = new DOMDocument();
	    @$doc->loadHTML($msgbody);
	    $elements = $doc->getElementsByTagName('img');
	    $image_pool=[];
	    foreach($elements as $element) {
	        if(!empty($element->getAttribute('src'))){
	            $image_pool[]=$element->getAttribute('src');
	        }
	    }
	    if(!empty($image_pool)){
	        foreach($image_pool as $k=>$v){
	            if(substr($v,0,14)=='/upload/album/'){
	                $new_image=str_replace("/upload/album/","https://cdn.wenxuecity.com/upload/album/",$v);
	                $msgbody=str_replace($v,$new_image,$msgbody);
	            }
	        }
	    }
	    
	    return $msgbody;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
