<?php
class account_user_login extends Model{
	protected $tableName="user_login";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");

	//海外登录
	public function haiwai_login($email,$password){
	    //查看用户状态
	    $obj_account_user=load("account_user");
	    $check_account_user=$obj_account_user->getOne("*",['email'=>$email,'login_source'=>"haiwai"]);
	    $rs_status=$this->check_user($check_account_user);
	    if(!$rs_status['status']){
	        return $rs_status;
	    }
	    
	    //检测密码
	    if(md5($password)!=$check_account_user['password']){
	        $rs_status['status']=false;
	        $rs_status['error']="密码错误";
	        return $rs_status;
	    }
	    
	    //设置登录cookie
	    $this->set_user_cookie($check_account_user);
	    
	    //设置session
	    $this->set_user_session($check_account_user);
	    return true;
	}
	
	//文学城登录
	public function wxc_login($login_data,$login_token){
	    //查看用户状态
	    $obj_account_user=load("account_user");
	    $check_account_user=$obj_account_user->getOne("*",['login_data'=>$login_data,'login_source'=>"wxc"]);
	    $rs_status=$this->check_user($check_account_user);
	    if(!$rs_status['status']){
	        return $rs_status;
	    }
	    
	    //检测密码
	    if(md5($login_token)!=$check_account_user['login_token']){
	        $rs_status['status']=false;
	        $rs_status['error']="密码错误";
	        return $rs_status;
	    }
	    
	    //设置登录cookie
	    $this->set_user_cookie($check_account_user);
	    
	    //设置session
	    $this->set_user_session($check_account_user);
	    return true;
	}
	
	//google 注册/登录
	public function google_login($login_token){
	    //$client=new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  
	    //$rs_client=$client->verifyIdToken($login_token);
	    $rs_client=["sub"=>110169484474386276334,"aud"=>"1008719970978-hb24n2dstb40o45d4feuo2ukqmcc6381.apps.googleusercontent.com","email"=>"sida9567_google@gmail.com"];//==========
	    if(!empty($rs_client)) {
	        $login_data=$rs_client['email'];
	        
	        //查看是否注册
	        $obj_account_user=load("account_user");
	        $check_account_user=$obj_account_user->getOne("*",['email'=>$login_data]);
	        if(!empty($check_account_user)){
	            //检测用户信息
	            $rs_status=$this->check_user($check_account_user);
	            if(!$rs_status['status']){
	                return $rs_status;
	            }
	        }
	        
	        //查看是否绑定
	        $obj_account_user_auth=load("account_user_auth");
	        $rs_account_user_auth=$obj_account_user_auth->getOne("*",['login_data'=>$login_data,'login_source'=>"google"]);
	        if(!empty($rs_account_user_auth)){
	            $login_token=md5($rs_client['sub'].$rs_client['aud']);
	            if($check_account_auth['login_token']!=$login_token){
	                $rs_status['status']=false;
	                $rs_status['error']="google认证错误";
	                return $rs_status;
	            }
	        }
	        
	        //注册用户
	        if(empty($check_account_user)){
	            $time=times::gettime();
	            $ip=http::getIP();
	            $fields=[
                    'username'=>strstr($login_data,'@',true),
                    'email'=>$login_data,
                    'verified'=>1,
                    'ip'=>$ip,
                    'login_date'=>$time,
                    'create_date'=>$time,
                    'update_date'=>$time,
                    'update_type'=>"register",
                    'update_ip'=>$ip,
                ];
	            $userID=$obj_account_user->insert($fields);
	        }
	        
	        //绑定google
	        if(empty($rs_account_user_auth)){
	            $obj_account_user_auth->insert(['userID'=>$userID,'login_data'=>$login_data,'login_token'=>$login_token,'login_source'=>"google"]);     
	        }
	        
	        //设置登录cookie
	        $rs_account_user=$obj_account_user->getOne("*",['email'=>$login_data]);
	        $this->set_user_cookie($rs_account_user);
	        
	        //设置session
	        $this->set_user_session($rs_account_user);
	        return true;
	    }else{
	        //非法登录
	        $rs_status['status']=false;
	        $rs_status['error']="登录信息错误";
	        return $rs_status;
	    }
	}
	
	
	//设置cookie
	function set_user_cookie($rs_account_user){
	    $check_account_user_login=$this->getOne(["id","pointer"],["userID"=>$rs_account_user['id']]);
	    $rand=md5(times::gettime().$this->rand());
	    if(empty($check_account_user_login)){
	        $index="index0";
	        $this->insert([$index=>$rand,"userID"=>$rs_account_user['id']]);
	    }else{
	        $index="index{$check_account_user_login['pointer']}";
	        $point=substr($check_account_user_login['pointer']+1,-1);
	        $this->update([$index=>$rand,"pointer"=>$point],["userID"=>$rs_account_user['id']]);
	    }
	    
	    $cookie=$this->encrypt($rand."___".$rs_account_user['id']."___".$index);
	    setcookie("wxc_login",$cookie,time()+(10 * 365 * 24 * 60 * 60),conf()['session']['sessionpath'],conf()['session']['sessiondomain']);
	}
	
	//设置session
	function set_user_session($rs_account_user){
	    $_SESSION['UserID']=$rs_account_user['id'];
	    $_SESSION['UserLevel']=$rs_account_user['auth_group'];
	    $_SESSION['id']=$rs_account_user['id'];
	    $_SESSION['username']=$rs_account_user['username'];;
	    $_SESSION['description']=$rs_account_user['description'];;
	    $_SESSION['background']=$rs_account_user['background'];;
	    $_SESSION['avatar']=$rs_account_user['avatar'];;
	}
	
	//自动登录
	function auto_login(){
	    $cookie=explode("___",$this->decrypt($_COOKIE['wxc_login']));
	    $rand=$cookie[0];
	    $userID=$cookie[1];
	    $pointer=$cookie[2];
	    
	    $rs_account_login=$this->getOne(["userID"],['userID'=>$userID,$pointer=>$rand]);
	    if(!empty($rs_account_login)){
	        $obj_account_user=load("account_user");
	        $check_account_user=$obj_account_user->getOne("*",['id'=>$rs_account_login['userID']]);
	        $rs_status=$this->check_user($check_account_user);
	        if(!$rs_status['status']){
	            return $rs_status;
	        }
	        
	        //设置session
	        $this->set_user_session($check_account_user);
	        return $rs_status;
	    }
	}
	
	//查看用户状态
	function check_user($check_account_user){
	    if(empty($check_account_user)){
	        return ["status"=>false,"error"=>"此用户不存在"];
	    }
	    if(empty($check_account_user['status'])){
	        return ["status"=>false,"error"=>"此用户已经被关闭"];
	    }
	    if(empty($check_account_user['verified'])){
	        return ["status"=>false,"error"=>"此用户还未认证"];
	    }
	    
	    return ["status"=>true];
	}
	
	//查看google绑定
	function check_user_google($check_account_auth,$login_token){
	    if($check_account_auth['login_token']!=$login_token){
	        
	    }
	}
	
	private function rand($min = NULL, $max = NULL) {
	    static $seeded;
	    if (!$seeded) {
	        mt_srand((double)microtime()*1000000);
	        $seeded = true;
	    }
	    if (isset($min) && isset($max)) {
	        if ($min >= $max) {
	            return $min;
	        } else {
	            return mt_rand($min, $max);
	        }
	    } else {
	        return mt_rand();
	    }
	}
	
	private function encrypt ($plain) {
	    $password = '';
	    for ($i=0; $i<10; $i++) {
	        $password .= $this->rand();
	    }
	    $salt = substr(md5($password), 0, 2);
	    $crypttext = new Crypter($salt);
	    $password = $crypttext->encrypt($plain) . ':' . $salt;
	    return $password;
	}
	
	private function decrypt ($encrypted) {
	    $stack = explode(':', $encrypted);
	    if (sizeof($stack) != 2) return false;
	    $crypttext = new Crypter($stack[1]);
	    $plain = $crypttext->decrypt($stack[0]);
	    return $plain;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>

















