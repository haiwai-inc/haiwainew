<?php
class account_user_email extends Model{
    protected $tableName="user_email";
    protected $dbinfo=array("config"=>"account","type"=>"MySQL");
    function __construct(){
        parent::__construct();
        $this->config=[
            //统计标识
            'headers'=>array('X-MSYS-API'=>'{"campaign_id" : "wenxuecity_notification"}'),
            
            //服务器信息
            'host'=>'smtp.sparkpostmail.com',
            'Secure'=>'tls',
            'port'=> 2525,
            
            //验证信息
            'user'=>'SMTP_Injection',
            'pass'=>SPARK_WXC_API_KEY,
            
            //显示信息
            'name'=>'haiwai.com',
            'fromuser'=>'noreply@mail.wenxuecity.com',
            
            'debug'=>false,
        ];
        
        $this->smartyObj=func_getSmarty("email");
    }
    
    //用户注册
    function register_verified($name,$email,$data){
        if(empty($name) || empty($email)){
            return false;
        }
        
        $this->smartyObj->assign("data",$data);
        $content=$this->smartyObj->fetch(DOCUROOT."/account/Tpl/register_verified.html");
        $title="test!";
        $confirmation= func_sendMail($title,$content,$text='',$email,$name,$this->config);
        
        return $confirmation;
    }
    
    //忘记密码
    function forgotpwd($name,$email,$data){
        if(empty($name) || empty($email)){
            return false;
        }
        
        $this->smartyObj->assign("data",$data);
        $content=$this->smartyObj->fetch(DOCUROOT."/account/Tpl/forgotpwd.html");
        $title="Forgot Your Password?";
        
        $confirmation= func_sendMail($title,$content,$text='',$email,$name,$this->config);
        
        return $confirmation;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}