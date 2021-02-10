<?php
class article_tag extends Model{
	protected $tableName="tag";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");

	public function search_by_name($keyword){
		return $this->getAll("*", ["name, Like"=>"%".$keyword."%", "limit"=>10]);
	}
	
	//添加文章标签
	public function article_tag_add($article_data){
	    if(!empty($article_data['tagname'])){
	        $obj_article_post_tag=load("article_post_tag");
	        
	        $post_tag_tbn=substr('0'.$article_data['postID'],-1);
	        $obj_article_post_tag->remove(['postID'=>$rs_article_post['postID']],"post_tag_".$post_tag_tbn);
	        foreach($article_data['tagname'] as $v){
	            $check_article_tag=$this->getOne("*",['name'=>$v]);
	            if(empty($check_article_tag)){
	                $check_article_tag['id']=$this->insert(['name'=>$v]);
	            }else{
	                $this->update(['count_article'=>$check_article_tag['count_article']+1],['id'=>$check_article_tag['id']]);
	            }
	            
	            $post_tagID=$obj_article_post_tag->get_id();
	            $fields_post_tag=[
	                "id"=>$post_tagID,
	                "postID"=>$article_data['postID'],
	                "tagID"=>$check_article_tag['id'],
	            ];
	            $obj_article_post_tag->insert($fields_post_tag,"post_tag_".$post_tag_tbn);
	        }
	    }
	}
	
	//添加草稿 tag
	public function draft_tag_add($article_data){
	    $tagID="";
	    if(!empty($article_data['tagname'])){
	        $obj_article_tag=load("article_tag");
	        foreach($article_data['tagname'] as $v){
	            $check_article_tag=$obj_article_tag->getOne("*",['name'=>$v]);
	            if(empty($check_article_tag)){
	                $check_article_tag['id']=$obj_article_tag->insert(['name'=>$v]);
	            }else{
	                $obj_article_tag->update(['count_article'=>$check_article_tag['count_article']+1],['id'=>$check_article_tag['id']]);
	            }
	            $tagID[]=$check_article_tag['id'];
	        }
	        $tagID=implode(",",$tagID);
	    }
	    
	    return $tagID;
	}
	
}
?>