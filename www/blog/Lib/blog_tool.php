<?php
class blog_tool{
    
    function __construct(){
        //account
        $this->obj_account_user=load("account_user");
        $this->obj_account_legacy_user=load("account_legacy_user");
        $this->obj_account_legacy_user_details=load("account_legacy_user_details");
        $this->obj_account_legacy_user_album=load("account_legacy_user_album");
        
        //article
        $this->obj_blog_category=load("blog_category");
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_article_tag=load("article_tag");
        $this->obj_article_post=load("article_post");
        $this->obj_article_post_tag=load("article_post_tag");
        
        //blogger
        $this->obj_blog_blogger=load("blog_blogger");
        $this->obj_blog_legacy_blogger=load("blog_legacy_blogger");
        $this->obj_blog_legacy_blogcat=load("blog_legacy_blogcat");
        $this->obj_blog_legacy_blogcat_members=load("blog_legacy_blogcat_members");
        $this->obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $this->obj_blog_legacy_202005_msg=load("blog_legacy_202005_msg");
    }
    
    //import legacy blog data
    function load_all_db(){
        //account
        $this->obj_account_user=load("account_user");
        $this->obj_account_legacy_user=load("account_legacy_user");
        $this->obj_account_legacy_user_details=load("account_legacy_user_details");
        $this->obj_account_legacy_user_album=load("account_legacy_user_album");
        
        //article
        $this->obj_blog_category=load("blog_category");
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_article_tag=load("article_tag");
        $this->obj_article_post=load("article_post");
        $this->obj_article_post_tag=load("article_post_tag");
        
        //blogger
        $this->obj_blog_blogger=load("blog_blogger");
        $this->obj_blog_legacy_blogger=load("blog_legacy_blogger");
        $this->obj_blog_legacy_blogcat=load("blog_legacy_blogcat");
        $this->obj_blog_legacy_blogcat_members=load("blog_legacy_blogcat_members");
        $this->obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $this->obj_blog_legacy_202005_msg=load("blog_legacy_202005_msg");
    }
    
    //start indexing table: blog_202005_post
    function import_post($rs){
        $this->load_all_db();
        
        //user
        $rs['user_new']=$this->add_to_user($rs);
        
        //blogger
        if($rs['treelevel']==0){
            $rs['blogger_new']=$this->add_to_blogger($rs);
            
            //tag
            if($rs['blogcat_id']!=0){
                $rs['tag_new'][]=$this->add_to_tag($rs['blogcat_id']);
            }
            if($rs['parent_id']!=0){
                $rs['tag_new'][]=$this->add_to_tag($rs['parent_id']);
            }
            
            //category
            if($rs['catid']!=0){
                $rs['category_new']=$this->add_to_category($rs);
            }
        }
        
        //article
        $rs['article_new']=$this->add_to_article($rs);
        
        return $rs;
    }
    
    //article
    function add_to_article($rs){
        $check_article_indexing=$this->obj_article_indexing->getOne("*",['wxc_postID'=>substr($rs['dateline'],0,10)."_blog_".$rs['postid']]);
        
        if(empty($check_article_indexing)){
            //msg
            $date=substr($rs['dateline'],0,4).substr($rs['dateline'],5,2);
            $rs_blog_legacy_202005_msg=$this->obj_blog_legacy_202005_msg->getOne("*",['postid'=>$rs['postid']],"blog_{$date}_msg");
            
            //indexing
            $postID=$this->obj_article_post->get_id();
            $basecode=$postID;
            if($rs['treelevel']!=0){
                //comment
                $check_article_indexing_basecode=$this->obj_article_indexing->getOne("*",['wxc_postID'=>substr($rs['dateline'],0,10)."_blog_".$rs['basecode']]);
                $basecode=empty($check_article_indexing_basecode)?0:$check_article_indexing_basecode['postID'];
            }
            $fields_indexing=[
                "postID"=>$postID,
                "wxc_postID"=>substr($rs['dateline'],0,10)."_blog_".$rs['postid'],
                "basecode"=>$basecode,
                "userID"=>$rs['user_new']['id'],
                "bloggerID"=>empty($rs['blogger_new']['id'])?$check_article_indexing_basecode['bloggerID']:$rs['blogger_new']['id'],
                "categoryID"=>empty($rs['category_new']['id'])?-1:$rs['category_new']['id'],
                "treelevel"=>$rs['treelevel'],
                "create_date"=>strtotime($rs['dateline']),
                "edit_date"=>strtotime($rs['dateline']),
                "count_read"=>$rs['view'],
                "count_comment"=>$rs['comments'],
            ];
            $fields_indexing['id']=$this->obj_article_indexing->insert($fields_indexing);
            
            //post
            $post_tbn=substr('0'.$rs['user_new']['id'],-1);
            $fields_post=[
                "id"=>$postID,
                "title"=>empty($rs['title'])?"reply {$basecode}":$rs['title'],
                "msgbody"=>$rs_blog_legacy_202005_msg['msgbody'],
            ];
            $this->obj_article_post->insert($fields_post,"post_{$post_tbn}");
            
            //tag
            if(!empty($rs['tag_new'])){
                $post_tag_tbn=substr('0'.$postID,-1);
                foreach($rs['tag_new'] as $v){
                    $post_tagID=$this->obj_article_post_tag->get_id();
                    $fields_post_tag=[
                        "id"=>$post_tagID,
                        "postID"=>$postID,
                        "tagID"=>$v['id'],
                    ];
                    $this->obj_article_post_tag->insert($fields_post_tag,"post_tag_".$post_tag_tbn);
                }
            }
            
            //blogger count
            if($rs['treelevel']==0){
                $rs_blog_blogger=$this->obj_blog_blogger->getOne("*",['userID'=>$rs['user_new']['id']]);
                $count_article=$rs_blog_blogger['count_article']+1;
                $count_read=$rs_blog_blogger['count_read']+$rs['view'];
                $this->obj_blog_blogger->update(["count_read"=>$count_read,"count_article"=>$count_article],['userID'=>$rs['user_new']['id']]);
            }else{
                if(!empty($basecode)){
                    $rs_article_indexing=$this->obj_article_indexing->getOne("*",['treelevel'=>0,'basecode'=>$basecode]);
                    $rs_blog_blogger=$this->obj_blog_blogger->getOne("*",['userID'=>$rs_article_indexing['userID']]);
                    $count_comment=$rs_blog_blogger['count_comment']+1;
                    $this->obj_blog_blogger->update(["count_comment"=>$count_comment],['userID'=>$rs_article_indexing['userID']]);
                }
            }
        }else{
            $fields_indexing=$check_article_indexing;
        }
        
        return $fields_indexing;
    }
    
    function add_to_user($rs){
        $rs_account_legacy_user=$this->obj_account_legacy_user->getOne("*",['userid'=>$rs['userid']]);
        $check_account_user=$this->obj_account_user->getOne("*",['login_source'=>'wxc','username'=>$rs_account_legacy_user['username']]);
        if(empty($check_account_user)){
            //插入用户
            $rs_account_legacy_user_details=$this->obj_account_legacy_user_details->getOne("*",["user_details_id"=>$rs['userid']]);
            $fields=[
                'username'=>$rs_account_legacy_user['username'],
                'description'=>empty($rs_account_legacy_user_details['summary'])?"":$rs_account_legacy_user_details['summary'],
                'background'=>empty($rs_account_legacy_user_details['aboutme'])?"":$rs_account_legacy_user_details['aboutme'],
                'password'=>md5("wxc123456"),
                'email'=>"sida9567@gmail.com",
                'verified'=>1,
                'ip'=>$rs_account_legacy_user["ipaddress"],
                'login_date'=>strtotime($rs_account_legacy_user['dateline']),
                'create_date'=>strtotime($rs_account_legacy_user['dateline']),
                'update_date'=>strtotime($rs_account_legacy_user['dateline']),
                'update_type'=>"register",
                'update_ip'=>$rs_account_legacy_user["ipaddress"],
                'login_data'=>'wxcusername',
                'login_token'=>md5("wxc123456"),
                'login_source'=>'wxc',
                'avatar'=>empty($avatar)?"":$avatar,
            ];
            $fields['id']=$this->obj_account_user->insert($fields);
            
            //老用户头像拉到本地处理
            $rs_account_legacy_user_album=$this->obj_account_legacy_user_album->getOne("*",['pid'=>$rs_account_legacy_user['pid']]);
            if(!empty($rs_account_legacy_user_album)){
                $folder1=substr('0000'.$rs['userid'],-4,-2);
                $folder2=substr('0000'.$rs['userid'],-2);
                //$old_avatar="https://cdn.wenxuecity.com/cache_data/members/{$folder1}/{$folder2}/400_600-{$rs_account_legacy_user_album['photoname']}";
                $old_avatar="https://cdn.wenxuecity.com/data/members/{$folder1}/{$folder2}/{$rs_account_legacy_user_album['photoname']}";
                
                //save image
                $dir="/upload/user/avatar/".substr('0000'.$fields['id'],-2)."/".substr('0000'.$fields['id'],-4,-2);
                $path=DOCUROOT.$dir;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $filename=$fields['id']."_avatar";
                $rs_image=picture::saveImg($old_avatar,$path,$filename);
                
                //小图
                if(!empty($rs_image)){
                    $this->obj_account_user->update(['avatar'=>"{$dir}/{$rs_image}"],['id'=>$fields['id']]);
                    $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_100_100",100,100);
                }
            }
        }else{
            $fields=$check_account_user;
        }
        
        return $fields;
    }
    
    function add_to_blogger($rs){
        $rs_blog_legacy_blogger=$this->obj_blog_legacy_blogger->getOne("*",['blogid'=>$rs['blogid']]);
        
        $check_blog_blogger=$this->obj_blog_blogger->getOne("*",['userID'=>$rs['user_new']['id']]);
        if(empty($check_blog_blogger)){
            $fields=[
                'userID'=>$rs['user_new']['id'],
                'name'=>$rs_blog_legacy_blogger['title'],
                'description'=>$rs_blog_legacy_blogger['description'],
                'create_date'=>strtotime($rs_blog_legacy_blogger['dateline']),
                'update_date'=>strtotime($rs_blog_legacy_blogger['dateline']),
                'update_type'=>"register",
                'update_ip'=>$rs['user_new']['update_ip'],
            ];
            $fields['id']=$this->obj_blog_blogger->insert($fields);
        }else{
            $fields=$check_blog_blogger;
        }
        
        //保存博客背景图片到本地
        if(!empty($rs_blog_legacy_blogger['blog_pic'])){
            $old_blog_pic="https://cdn.wenxuecity.com/{$rs_blog_legacy_blogger['blog_pic']}";
            
            //save image
            $dir="/upload/blog/background/".substr('0000'.$fields['id'],-2)."/".substr('0000'.$fields['id'],-4,-2);
            $path=DOCUROOT.$dir;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $filename=$fields['id']."_background";
            $rs_image=picture::saveImg($old_blog_pic,$path,$filename);
            
            //小图保存
            if(!empty($rs_image)){
                $this->obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_750_420",750,420);
                $this->obj_blog_blogger->update(['background'=>"{$dir}/{$rs_image}"],['id'=>$fields['id']]);
            }
        }
        
        return $fields;
    }
    
    function add_to_category($rs){
        if(!empty($rs['catid'])){
            $rs_blog_legacy_blogcat_members=$this->obj_blog_legacy_blogcat_members->getOne("*",['catid'=>$rs['catid']]);
            if(empty($rs_blog_legacy_blogcat_members)){
                return false;
            }
            
            $check_blog_category=$this->obj_blog_category->getOne("*",['bloggerID'=>$rs['blogger_new']['id'],'name'=>$rs_blog_legacy_blogcat_members['category']]);
            if(empty($check_blog_category)){
                $field=[
                    "bloggerID"=>$rs['blogger_new']['id'],
                    "name"=>$rs_blog_legacy_blogcat_members['category'],
                ];
                $field['id']=$this->obj_blog_category->insert($field);
            }else{
                $this->obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1],['id'=>$check_blog_category['id']]);
                $field=$check_blog_category;
            }
            
            return $field;
        }
        
        return false;
    }
    
    function add_to_tag($blogcat_id){
        $rs_blog_legacy_blogcat=$this->obj_blog_legacy_blogcat->getOne("*",['blogcat_id'=>$blogcat_id]);
        
        $check_article_tag=$this->obj_article_tag->getOne("*",['name'=>$rs_blog_legacy_blogcat['title']]);
        if(empty($check_article_tag)){
            $field=['name'=>$rs_blog_legacy_blogcat['title']];
            $field['id']=$check_article_tag['id']=$this->obj_article_tag->insert($field);
        }else{
            $this->obj_article_tag->update(['count_article'=>$check_article_tag['count_article']+1],['id'=>$check_article_tag['id']]);
            $field=$check_article_tag;
        }
        
        return $field;
    }
}
?>