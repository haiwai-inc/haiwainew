<?php
class account_user extends Model{
	protected $tableName="user";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");

	/**
	 * 抓取并保存图片
	 * 说明： 对于保持宽度的图片可以设置较大的高，对于保持高度的图片设置较大的宽
	 */
	function cutPic($filepath,$filename,$w,$h){
	    //对图片进行大小处理
	    $pic=new picture();
	    $pic->filepath=$filepath; //材料图地址
	    $pic->filename=$filename; //加工过后的名字
	    $pic->save_dir= dirname($filepath); //材料图路径
	    
	    //设置裁剪信息
	    $info = getimagesize($filepath);
	    if($info[0]<=$info[1]){
	        //宽小于等于高
	        $pic->width=$w;
	        $pic->GetSize( 1 );
	        if($pic->height>$h) {
	            $pic->height_tar = round($pic->height_orig * ($h/$pic->height));
	            $pic->height = $h;
	        }
	    }else{
	        //宽大于高
	        $pic->height = $h;
	        $pic->GetSize( 2 );
	        if($pic->width>$w) {
	            $pic->width_tar = round($pic->width_orig * ($w/$pic->width));
	            $pic->width =$w;
	        }
	    }
	    
	    //执行图片处理
	    $pic->readimage();
	    $pic->writeimage();
	}
	
	/**
	 * Get a id=>user map
	 * @param array $ids | List of ids
	 * @return array $rs | id user map
	 */
	public function get_id_user_map($ids){
		$users = $this->getAll(["id", "username", "avatar", "description", "verified", "status"], ["OR"=>["id"=>$ids]]);
		$rs = [];
		if(!empty($users)){
			foreach($users as $user){
				$rs[$user['id']] = $user;
			}
		}
		return $rs;
	}

	public function search_by_name($keyword){
		$user_rs  = $this->getList(["id", "username", "avatar", "description"], ['username, LIKE' => "%$keyword%"]);
		return $user_rs;
	}
	
	//在包含用户ID的数组里，补全用户基本信息
	function get_basic_userinfo($rs,$hashID='id'){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	            if(!empty($v['postID'])){
	                $tmp_rs_postID[]=$v['postID'];
	            }
	        }
	        
	        //加入名博
	        $obj_memcache = func_initMemcached('cache01');
	        $rs_memcache = $obj_memcache->get("blog_hot_blogger");
	        $userID_rs_memcache=[];
	        if(!empty($rs_memcache)){
	            foreach($rs_memcache as $v){
	                $userID_rs_memcache[]=$v['userID'];
	            }
	        }
	        
	        //如果登录
	        $followerID_accout_follower=[];
	        if(!empty($_SESSION['id'])){
	            //加入关注人
	            $obj_accout_follower=load("account_follower");
	            $rs_accout_follower=$obj_accout_follower->getAll("*",['userID'=>$_SESSION['id'],'limit'=>200]);
	            if(!empty($rs_accout_follower)){
	                foreach($rs_accout_follower as $v){
	                    $followerID_accout_follower[]=$v['followerID'];
	                }
	            }
	        }
	        
	        
	        //加入用户信息
			$rs_account_user = $this->getAll(["id", "username", "avatar", "description", "verified", "status"], ["OR"=>["id"=>$tmp_rs_id]]);
			if(!empty($rs_account_user)){
			    foreach($rs_account_user as $v){
			        $hash_account_user[$v['id']] = $v;
			    }
			}
			
	        foreach($rs as $k=>$v){
	            $item=empty($hash_account_user[$v[$hashID]])?[]:$hash_account_user[$v[$hashID]];
	            
	            //首字母
	            $item['first_letter']=substr(strings::subString($item['username'],1), 0, -3);
	            
	            //是否名博
	            $item['is_hot_blogger']=(in_array($item['id'],$userID_rs_memcache))?1:0;
	            
	            //是否关注
	            $item['is_follower']=(in_array($item['id'],$followerID_accout_follower))?1:0;
	            
	            //大图+小图
	            $item['o_avatar']=$item['avatar'];
	            $item['avatar']=str_replace("{$item['id']}_avatar","{$item['id']}_avatar_100_100",$item['avatar']);
	            
	            $rs[$k]["userinfo_{$hashID}"]=$item;
			}
			
	    }
	    
	    return $rs;
	}
	
	//验证邮箱
	function check_email($email){
	    $rs = ['status' => false, 'init' => false];
	    if (empty($email)) {
	        $rs['error']="邮箱为空";
	        return $rs;
	    }
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        $rs['error']="邮箱格式错误";
	        return $rs;
	    }
	    
	    $check_account_user=$this->getOne(['id'],['status'=>1,'email'=>$email]);
	    if(!empty($check_account_user)){
	        $rs['error']="此用户已经被注册";
	        return $rs;
	    }
	    
	    $rs['status'] = true;
	    return $rs;
	}
	
	//验证密码
	function check_password($password){
	    $rs = ['status' => false, 'init' => false];
	    if (empty($password)) {
	        $rs['error'] = "密码为空";
	        return $rs;
	    }
	    if (strlen($password) < 6 || strlen($password)>24) {
	        $rs['error'] = "密码必为6到24个字符";
	        return $rs;
	    }
	    /*
	    if (!preg_match('/[\'^£$%&*()}{!.@#~?><>,|=_+¬-]/', $password)) {
	        $rs['msg'] = "Must contain a number and a special character";
	        return $rs;
	    }
	    */
	    $rs['status'] = true;
	    return $rs;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>

















