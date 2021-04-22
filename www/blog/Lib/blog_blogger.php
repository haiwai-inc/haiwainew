<?php
class blog_blogger extends Model{
	protected $tableName="blogger";
	protected $dbinfo=array("config"=>"blog","type"=>"MySQL");

	/**
	 * Search blogger with keyword
	 * @param string $keyword
	 * @return $for_article
	 */
	public function search_by_name($keyword, $for_article = false){
		//TODO lastid filter
		$where = ['name, LIKE' => "%$keyword%"];
		if(empty($for_article)){
			$blogger_rs  = $this->getList(["id", "name", "userID", "background", "description", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		else{
			$where['limit'] = 3;
			$blogger_rs  = $this->getAll(["id", "name", "userID", "background", "description", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		$user_obj = load("account_user");
		$bloggers = $user_obj->get_basic_userinfo($blogger_rs, "userID");
		return $bloggers;
	}

	//获取博客基本信息
	function get_basic_bloggerinfo($rs,$hashID='id',$nested=false){
	    if(!empty($rs)){
	        //没有bloggerID情况下
	        if(!empty($nested)){
	            foreach($rs as $v){
	                $tmp_hashID[]=$v[$hashID]['id'];
	            }
	            $rs_blog_bloggers=$this->getAll(['id','userID'],['OR'=>['userID'=>$tmp_hashID]]);
	            if(!empty($rs_blog_bloggers)){
	                foreach($rs_blog_bloggers as $v){
	                    $hash_hashID[$v['userID']]=$v['id'];
	                }
	            }
	            if(!empty($hash_hashID)){
	                foreach($rs as $k=>$v){
	                    $rs[$k]['bloggerID']=$hash_hashID[$v[$hashID]['id']];
	                }
	                $hashID='bloggerID';
	            }else{
	                return $rs;
                }
	        }
	        
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
	        
	        $rs_blog_bloggers = $this->getAll(["id", "name", "userID", "background", "description", "count_read", "count_article", "count_buzz", "count_comment"], ["OR"=>["id"=>$tmp_rs_id]]);       
	        if(!empty($rs_blog_bloggers)){
	            foreach($rs_blog_bloggers as $v){
	                $hash_blog_blogger[$v['id']]=$v;
	            }
	        }
			
	        foreach($rs as $k=>$v){
				$item=$hash_blog_blogger[$v[$hashID]];
	            $item['o_avatar']=$item['background'];
	            $item['background']=empty($item['background'])?"/img/default_bg.jpg":str_replace("{$v[$hashID]}_background","{$item['id']}_background_750_420",$item['background']);
				$rs[$k]['bloggerinfo_'.$hashID]=$item;
	        }
	    }
	    
	    return $rs;
	}
	
	//添加文章为博客类型
	function add_blog_article($article_data,$module_data){
	    //是否为公开文章
	    //if(!empty($article_data['is_publish'])){
	        //同步文集计数
	        $obj_blog_category=load("blog_category");
	        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$module_data['bloggerID'],'id'=>$module_data['categoryID']]);
	        $obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1],['id'=>$check_blog_category['id']]);
	    //}
	    
	    //修改索引表
	    $obj_article_indexing=load("article_indexing");
	    $obj_article_indexing->update(['bloggerID'=>$module_data['bloggerID'],'categoryID'=>$module_data['categoryID']],['postID'=>$article_data['postID']]);
	    
	    //修改博主信息
	    $time=times::getTime();
	    $check_blogger=$this->getOne('*',['id'=>$module_data['bloggerID']]);
	    $fields_blogger=[
	        'update_date'=>$time,
	        'update_ip'=>http::getIP(),
	        'count_article'=>$check_blogger['count_article']+1,
	        'update_type'=>'add_article',
	    ];
	    $this->update($fields_blogger,['id'=>$module_data['bloggerID']]);
	    
	    //更新关注时间
	    $obj_account_follow=load("account_follow");
	    $obj_account_follow->update(['following_update'=>$time],["followingID"=>$check_blogger['userID']]);
	}
	
	//添加博客文章
	function update_blog_article($article_data,$module_data){
	    $obj_article_indexing=load("article_indexing");
	    $obj_blog_category=load("blog_category");
	    
	    //是否为公开文章
	    //if(!empty($article_data['is_publish'])){
	        //同步文集计数
	        //老目录-1
	        $check_old_article_indexing=$obj_article_indexing->getOne(['postID','categoryID'],['postID'=>$article_data['postID']]);
	        $check_old_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$module_data['bloggerID'],'id'=>$check_old_article_indexing['categoryID']]);
	        $obj_blog_category->update(['count_article'=>$check_old_blog_category['count_article']-1],['id'=>$check_old_blog_category['id']]);
	        
	        //新目录+1
	        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$module_data['bloggerID'],'id'=>$module_data['categoryID']]);
	        $obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1],['id'=>$check_blog_category['id']]);
	    //}
	       
	    //修改索引表
	    $obj_article_indexing->update(['bloggerID'=>$module_data['bloggerID'],'categoryID'=>$module_data['categoryID']],['postID'=>$article_data['postID']]);
	       
	    //修改博主信息
	    $time=times::getTime();
	    $check_blogger=$this->getOne('*',['id'=>$module_data['bloggerID']]);
	    $fields_blogger=[
	        'update_date'=>$time,
	        'update_ip'=>http::getIP(),
	        'update_type'=>'edit_article'
	    ];
	    $this->update($fields_blogger,['id'=>$module_data['bloggerID']]);
	    
	    //更新关注时间
	    $obj_account_follow=load("account_follow");
	    $obj_account_follow->update(['following_update'=>$time],["followingID"=>$check_blogger['userID']]);
	}
	
	
	//删除博客文章操作
	function delete_blog_article($rs_article_indexing){
	    //是否为隐藏目录
	    //if(!empty($rs_article_indexing['is_publish'])){
	        $obj_blog_category=load("blog_category");
	        $rs_blog_category=$obj_blog_category->getOne("*",['id'=>$rs_article_indexing['categoryID']]);
	        
	        //同步文集计数
	        $obj_blog_category->update(["count_article"=>$rs_blog_category['count_article']-1],['id'=>$rs_article_indexing['categoryID']]);
	        
	        //同步博客计数
	        $rs_blog_blogger=$this->getOne("*",['id'=>$rs_article_indexing['bloggerID']]);
	        $this->update(['count_article'=>$rs_blog_blogger['count_article']-1],['id'=>$rs_article_indexing['bloggerID']]);
	    //}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>











