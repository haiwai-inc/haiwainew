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
			$blogger_rs  = $this->getList(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		else{
			$where['limit'] = 3;
			$blogger_rs  = $this->getAll(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		$user_obj = load("account_user");
		$bloggers = $user_obj->get_basic_userinfo($blogger_rs, "userID");
		return $bloggers;
	}

	//获取博客基本信息
	function get_basic_bloggerinfo($rs,$hashID='id',$user=false){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
			
	        $rs_blog_bloggers = $this->getAll(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], ["OR"=>["id"=>$tmp_rs_id]]);       
	        if(!empty($rs_blog_bloggers)){
	            foreach($rs_blog_bloggers as $v){
	                $hash_blog_blogger[$v['id']]=$v;
	            }
	        }
			
	        foreach($rs as $k=>$v){
				$item=$hash_blog_blogger[$v[$hashID]];
	            $item['o_avatar']=$item['background'];
	            $item['background']=str_replace("{$v[$hashID]}_background","{$item['id']}_background_750_420",$item['background']);
				$rs[$k]['bloggerinfo_'.$hashID]=$item;
				if($user){
					$rs[$k]['userID'] = $item['userID'];
				}
	        }
	    }
	    
	    return $rs;
	}
	
	//转文章为博客类型
	function to_blog_article($postID,$module_data){
	    $fields_indexing=[
	        'bloggerID'=>$module_data['bloggerID'],
	    ];
	    
	    //文集
	    $obj_blog_category=load("blog_category");
	    $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$module_data['bloggerID'],'id'=>$module_data['categoryID']]);
	    
	    if(!empty($check_blog_category)){
	        $obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1],['id'=>$check_blog_category['id']]);
	        $fields_indexing['categoryID']=$check_blog_category['id'];
	    }
	    
	    //修改索引表
	    $obj_article_indexing=load("article_indexing");
	    $obj_article_indexing->update($fields_indexing,['postID'=>$postID]);
	    
	    //修改博主计数信息
	    $time=times::getTime();
	    $check_blogger=$this->getOne('*',['id'=>$module_data['bloggerID']]);
	    $fields_blogger=[
	        'update_date'=>$time,
	        'update_ip'=>http::getIP()
	    ];
	    if(!empty($module_data['add'])){
	        $fields_blogger['count_article']=$check_blogger['count_article']+1;
	        $fields_blogger['update_type']='add_article';
	    }
	    if(!empty($module_data['edit'])){
	        $fields_blogger['update_type']='edit_article';
	    }
	    $this->update($fields_blogger,['id'=>$module_data['bloggerID']]);
	    
	    //更新关注时间
	    $obj_account_follow=load("account_follow");
	    $obj_account_follow->update(['following_update'=>$time],["followingID"=>$check_blogger['userID']]);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>











