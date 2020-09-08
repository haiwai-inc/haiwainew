<?php
/**
 * 设置或取消移动版
 */
include '../_inc.php';

class mobileRedirect{
	public $key = 'noWap';
	public $redirectURL='/';
	
	function __construct(){
		$_GET[$this->key] = isset($_GET[$this->key])?intval($_GET[$this->key]):0;
		
		if(!empty($_GET[$this->key])){
			setcookie($this->key,$_GET[$this->key],time()+31536000,"/",conf('global','session.sessiondomain'));
		}else{
			setcookie($this->key, '', 1, '/', conf('global','session.sessiondomain'));
			if (isset($_COOKIE[$this->key])) unset($_COOKIE[$this->key]);
		}
		
		$this->redirectURL = empty($_SERVER["HTTP_REFERER"])?"/":$_SERVER["HTTP_REFERER"];
	}
	
	function init(){
		if(isset($_GET['pathinfo'])) $this->parsePathInfo($_GET['pathinfo']);
		go($this->redirectURL);
	}
	
	private function parsePathInfo($pathinfo){
		
		$parse_url=explode("/",$pathinfo);
		
		/**
		 * /news
		 * /news/detail/news/6136025
		 * /news/detail/gossip/133902
		 * /news/detail/ent/128673
		 */
		if(strstr($pathinfo,"news")){

			
			
			if(!in_array('detail',$parse_url))
				$redirectURL="http://www.wenxuecity.com/";
			else
			{
				$date=explode(" ",$_GET['info']);
				$date=explode("-",$date[0]);
				
				if($parse_url[3]=='news')
					$redirectURL="http://www.wenxuecity.com/news/{$date[0]}/{$date[1]}/{$date[2]}/{$parse_url[4]}.html";
				else
					$redirectURL="http://www.wenxuecity.com/news/{$date[0]}/{$date[1]}/{$date[2]}/{$parse_url[3]}-{$parse_url[4]}.html";
			}
			
			$this->redirectURL=$redirectURL;
			return;
		}
		
		/**
		 * /blog
		 * /blog/detail/648785/201703_33464
		 */
		if(strstr($pathinfo,"blog")){
			if(!in_array('detail',$parse_url))
				$redirectURL="http://www.wenxuecity.com/blog";
			else
			{
				$parse_url[3]=$_GET['info'];
				$parse_url[4]=explode("_",$parse_url[4]);
				
				$redirectURL="http://blog.wenxuecity.com/myblog/$parse_url[3]/{$parse_url[4][0]}/{$parse_url[4][1]}.html";
			}
			
			$this->redirectURL=$redirectURL;
			return;
		}
		
		/**
		 * /bbs
		 * /bbs/list/znjy/子女教育
		 * /bbs/detail/znjy/3525481
		 */
		if(strstr($pathinfo,"bbs")){
			if(!in_array('detail',$parse_url))
			{
				if(!in_array('list',$parse_url))
					$redirectURL="http://bbs.wenxuecity.com/";
				else
					$redirectURL="http://bbs.wenxuecity.com/{$parse_url['3']}";
			}
			else
				$redirectURL="http://bbs.wenxuecity.com/{$parse_url['3']}/{$parse_url['4']}.html";
			
			$this->redirectURL=$redirectURL;
			return;
		}
	}
}

$mobileObj = new mobileRedirect();
$mobileObj->init();