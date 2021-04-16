<?php
class blog_tool{
    
    function __construct(){
        //account
        $this->obj_account_user=load("account_user");
        $this->obj_account_legacy_user=load("account_legacy_user");
        $this->obj_account_legacy_user_details=load("account_legacy_user_details");
        $this->obj_account_legacy_user_album=load("account_legacy_user_album");
        
        //article
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_article_tag=load("article_tag");
        $this->obj_article_post=load("article_post");
        $this->obj_article_post_tag=load("article_post_tag");
        $this->obj_article_indexing_wxc=load("article_indexing_wxc");
        
        //blogger
        $this->obj_blog_blogger=load("blog_blogger");
        $this->obj_blog_category=load("blog_category");
        $this->obj_blog_legacy_blogger=load("blog_legacy_blogger");
        $this->obj_blog_legacy_blogcat=load("blog_legacy_blogcat");
        $this->obj_blog_legacy_blogcat_members=load("blog_legacy_blogcat_members");
        $this->obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $this->obj_blog_legacy_202005_msg=load("blog_legacy_202005_msg");
        $this->obj_blog_legacy_hot_blogger=load("blog_legacy_hot_blogger");
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
            $rs['category_new']=$this->add_to_category($rs);
        }
        
        //article
        $rs['article_new']=$this->add_to_article($rs);
        
        return $rs;
    }
    
    //article
    function add_to_article($rs){
        //查看是否曾经导入
        $check_article_indexing_wxc=$this->obj_article_indexing_wxc->getOne('*',['wxc_postid'=>substr($rs['dateline'],0,7)."_blog_".$rs['postid']]);
        if(!empty($check_article_indexing_wxc)){
            //查看原导入数据
            $check_article_indexing=$this->obj_article_indexing->getOne("*",['postID'=>$check_article_indexing_wxc['postID']]);
        }
        
        //导入新wxc博客数据
        if(empty($check_article_indexing)){
            //查看是否为隐藏
            $rs_blog_legacy_blogcat_members=$this->obj_blog_legacy_blogcat_members->getOne("*",['catid'=>$rs['catid']]);
            
            //查看内容
            $date=substr($rs['dateline'],0,4).substr($rs['dateline'],5,2);
            $rs_blog_legacy_202005_msg=$this->obj_blog_legacy_202005_msg->getOne("*",['postid'=>$rs['postid']],"blog_{$date}_msg");
            
            //获取postID
            $postID=$this->obj_article_post->get_id();
            $basecode=$postID;
            
            //评论
            if($rs['treelevel']!=0){
                //获取wxc主贴basecode
                $check_article_indexing_wxc_basecode=$this->obj_article_indexing_wxc->getOne("*",['wxc_basecode'=>substr($rs['dateline'],0,7)."_blog_".$rs['basecode']]);
                $basecode=empty($check_article_indexing_wxc_basecode)?0:$check_article_indexing_wxc_basecode['basecode'];
            }
            $fields_indexing=[
                "postID"=>$postID,
                "basecode"=>$basecode,
                "userID"=>$rs['user_new']['id'],
                "bloggerID"=>empty($rs['blogger_new']['id'])?0:$rs['blogger_new']['id'],
                "categoryID"=>empty($rs['category_new']['id'])?0:$rs['category_new']['id'],
                "treelevel"=>$rs['treelevel'],
                "create_date"=>strtotime($rs['dateline']),
                "edit_date"=>strtotime($rs['dateline']),
                "count_read"=>$rs['view'],
                "count_comment"=>$rs['comments'],
                'is_publish'=>$rs_blog_legacy_blogcat_members['visible'],
            ];
            $fields_indexing['id']=$this->obj_article_indexing->insert($fields_indexing);
            
            //记录文学城旧博客ID
            $this->obj_article_indexing_wxc->insert([
                'postID'=>$fields_indexing['postID'],
                'basecode'=>$fields_indexing['basecode'],
                'wxc_postid'=>substr($rs['dateline'],0,7)."_blog_".$rs['postid'],
                'wxc_basecode'=>substr($rs['dateline'],0,7)."_blog_".$rs['basecode']]);
            
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
        $check_account_user=$this->obj_account_user->getOne("*",['username'=>$rs_account_legacy_user['username']]);
        if(empty($check_account_user)){
            //获取文学城用户密码信息
            $obj_legacy_user_passwd_new=load("account_legacy_user_passwd_new");
            $rs_legacy_user_passwd_new=$obj_legacy_user_passwd_new->getOne("*",['userid'=>$rs['userid']]);
            
            //插入用户
            $rs_account_legacy_user_details=$this->obj_account_legacy_user_details->getOne("*",["user_details_id"=>$rs['userid']]);
            $fields=[
                'username'=>$rs_account_legacy_user['username'],
                'description'=>empty($rs_account_legacy_user_details['summary'])?"":$rs_account_legacy_user_details['summary'],
                'background'=>empty($rs_account_legacy_user_details['aboutme'])?"":$rs_account_legacy_user_details['aboutme'],
                'email'=>"sida9567@gmail.com", //$rs_legacy_user_passwd_new['email']
                'verified'=>1,
                'ip'=>$rs_account_legacy_user["ipaddress"],
                'login_date'=>strtotime($rs_account_legacy_user['dateline']),
                'create_date'=>strtotime($rs_account_legacy_user['dateline']),
                'update_date'=>strtotime($rs_account_legacy_user['dateline']),
                'update_type'=>"register",
                'update_ip'=>$rs_account_legacy_user["ipaddress"],
                'avatar'=>empty($avatar)?"":$avatar,
            ];
            $fields['id']=$this->obj_account_user->insert($fields);
            
            //绑定文学城用户
            $obj_account_user_auth=load("account_user_auth");
            $check_account_user_auth=$obj_account_user_auth->getOne(['id'],['login_source'=>"wxc",'login_data'=>$rs_account_legacy_user['username']]);
            if(empty($check_account_user_auth)){
                $obj_account_user_auth->insert(['userID'=>$rs['userid'],'login_source'=>'wxc','login_data'=>$rs_legacy_user_passwd_new['username'],'login_token'=>$rs_legacy_user_passwd_new['password']]);
            }
            
            //老用户头像拉到本地处理
            $rs_account_legacy_user_album=$this->obj_account_legacy_user_album->getOne("*",['pid'=>$rs_account_legacy_user['pid']]);
            if(!empty($rs_account_legacy_user_album)){
                $folder1=substr('0000'.$rs['userid'],-4,-2);
                $folder2=substr('0000'.$rs['userid'],-2);
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
        $rs_blog_legacy_hot_blogger=$this->obj_blog_legacy_hot_blogger->getOne("*",['blogid'=>$rs['blogid']]);
        
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
                'is_hot'=>empty($rs_blog_legacy_hot_blogger)?0:1,
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
        //添加默认文集
        if(empty($rs['catid'])){
            $rs_blog_legacy_blogcat_members['category']="我的文章";
        }else{
            $rs_blog_legacy_blogcat_members=$this->obj_blog_legacy_blogcat_members->getOne("*",['catid'=>$rs['catid']]);
            if(empty($rs_blog_legacy_blogcat_members)){
                $rs_blog_legacy_blogcat_members['category']="我的文章";
            }
        }
        
        $check_blog_category=$this->obj_blog_category->getOne("*",['bloggerID'=>$rs['blogger_new']['id'],'name'=>$rs_blog_legacy_blogcat_members['category']]);
        if(empty($check_blog_category)){
            $field=[
                "bloggerID"=>$rs['blogger_new']['id'],
                "name"=>$rs_blog_legacy_blogcat_members['category'],
                'count_article'=>1,
                'is_publish'=>$rs_blog_legacy_blogcat_members['visible']
            ];
            if($field['name']=="我的文章"){
                $field['is_default']=1;
            }
            $field['id']=$this->obj_blog_category->insert($field);
            $this->obj_blog_category->update(['sort'=>$field['id']],['id'=>$field['id']]);
        }else{
            $this->obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1],['id'=>$check_blog_category['id']]);
            $field=$check_blog_category;
        }
        
        return $field;
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