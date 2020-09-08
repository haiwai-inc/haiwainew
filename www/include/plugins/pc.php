<?php
/**
 * 设置或取消移动版
*/
include '../_inc.php';

class pcRedirect{
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
		$parse_url_tmp=explode("://",$pathinfo);
		$parse_url=explode("/",$parse_url_tmp[1]);

		/**
		 * http://www.wenxuecity.com/
		 * http://www.wenxuecity.com/news/
		 * http://m.wenxuecity.com/site/news/news/detail/news/6665130
		 * http://m.wenxuecity.com/site/news/news/detail/gossip/145093
		 * http://m.wenxuecity.com/site/news/news/detail/ent/141021
		 */
		if($parse_url[0]=='www.wenxuecity.com')
		{
			if(empty($parse_url[1]) || $parse_url[1]=='news' && empty($parse_url[2]))
				$this->redirectURL="http://m.wenxuecity.com";
			else
			{
				$parse_subid=explode("-",$parse_url[5]);
				
				//news
				if (empty($parse_subid[1]))
				{
					$parse_postid=explode(".",$parse_url[5]);
					$this->redirectURL="http://m.wenxuecity.com/site/news/news/detail/news/{$parse_postid[0]}";
				}
				else
				{
					//ent 首页
					if($parse_subid[2]=='ent')
						$this->redirectURL="http://m.wenxuecity.com";
					
					//gossip首页
					else if($parse_subid[2]=='socialnews')
						$this->redirectURL="http://m.wenxuecity.com";
					
					else
					{
						$parse_postid=explode(".",$parse_subid[1]);
						
						//ent
						if($parse_subid[0]=='socialnews')
							$this->redirectURL="http://m.wenxuecity.com/site/news/news/detail/gossip/{$parse_postid[0]}";
						
						//gossip
						if($parse_subid[0]=='ent')
							$this->redirectURL="http://m.wenxuecity.com/site/news/news/detail/ent/{$parse_postid[0]}";
					}
				}
			}
			
		}

		/**
		 * http://blog.wenxuecity.com/
		 * http://blog.wenxuecity.com/myoverview/68634/
		 * http://blog.wenxuecity.com/myindex/68634/
		 * http://blog.wenxuecity.com/myblog/68634/all.html
		 * http://blog.wenxuecity.com/myblog/68634/201702/26906.html
		 * 
		 * 
		 * http://m.wenxuecity.com/site/blog
		 * http://m.wenxuecity.com/site/blog/blogger/25182
		 * http://m.wenxuecity.com/site/blog/blogdetail/15283/201710
		 */
		if($parse_url[0]=='blog.wenxuecity.com')
		{
			if(empty($parse_url[1]))
				$this->redirectURL="http://m.wenxuecity.com/site/blog";
			
			if(!empty($parse_url[1]) && ($parse_url[1]=='myoverview' || $parse_url[1]=='myindex'))
				$this->redirectURL="http://m.wenxuecity.com/site/blog/blogger/{$parse_url[2]}";
			
			if(!empty($parse_url[1]) && $parse_url[1]=='myblog')
			{
				if($parse_url[3]=='all.html')
					$this->redirectURL="http://m.wenxuecity.com/site/blog/blogger/{$parse_url[2]}";
				else
				{
					$parse_postid=explode(".",$parse_url[4]);
					$this->redirectURL="http://m.wenxuecity.com/site/blog/blogdetail/{$parse_url[2]}/{$parse_url[3]}";
				}
			}
		}
		
		/**
		 * http://bbs.wenxuecity.com/
		 * http://bbs.wenxuecity.com/health/
		 * http://bbs.wenxuecity.com/health/702780.html
		 */
		if($parse_url[0]=='bbs.wenxuecity.com')
		{
			if(empty($parse_url[1]) && empty($parse_url[2]))
				$this->redirectURL="http://m.wenxuecity.com/site/forum/bbs";
			
			if(!empty($parse_url[1]) && empty($parse_url[2]))
			{
				$all_bbs=conf("bbs.allbbs");
				$title=$all_bbs[$parse_url[1]]['title'];
				
				$this->redirectURL="http://m.wenxuecity.com/site/forum/bbs/list/{$parse_url[1]}";
			}
			
			if(!empty($parse_url[1]) && !empty($parse_url[2]))
			{
				$parse_postid=explode(".",$parse_url[2]);
				$this->redirectURL="http://m.wenxuecity.com/site/forum/bbs/detail/{$parse_url[1]}/{$parse_postid[0]}";
			}
		}
	}
}

$pcObj = new pcRedirect();
$pcObj->init();




















