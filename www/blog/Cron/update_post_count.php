<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();
// Get all post from wenxuecity (200 at a time)
// iterate each post
// Extract wxc post tag => blog_yyyyxx_post
// Get blogger id, postid yyyyxx_postid
// 

class CountUpdater{


    function start(){

    }

    function extractNumber($content){
        $str = str_replace(["document.write('", "');", ",", "\");","document.write(\""], "", $content);
        return intval($str);
    }

    // function curl($url){

    // }

    function curl($url){
		// $baseUri = "{$this->search_host}:{$this->search_port}{$url}";
		$ci = curl_init();
		 
		$urlinfo = parse_url($url);
		if(isset($urlinfo['scheme'])){
			$scheme = strtolower($urlinfo['scheme']);
			if($scheme=='https'){
				curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
			}
		}
		
		curl_setopt( $ci, CURLOPT_URL, $url );
		// curl_setopt( $ci, CURLOPT_PORT, $this->search_port );
		curl_setopt( $ci, CURLOPT_TIMEOUT, 200 );
		curl_setopt( $ci, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ci, CURLOPT_FORBID_REUSE, 0 );
		curl_setopt( $ci, CURLOPT_CUSTOMREQUEST, "GET" );
		$json =  curl_exec($ci);
		if(empty($json)){
			debug::d(curl_error($ci));
			debug::d(curl_errno($ci));
		}
		return  $json;
	}

    
}

class PostCountUpdater extends CountUpdater{

    function __construct($postIDs = [])
    {
        $this->postIDs = $postIDs;
        $this->indexWXCObj = load("article_indexing_wxc");
        $this->wxcPostObj = load("blog_legacy_202005_post");
        $this->indexObj = load("article_indexing");
    }

    function start(){
        $i = 0;
        $articles = $this->indexWXCObj->getAll("*", ["OR"=>["postID"=>$this->postIDs],"limit"=>[$i*200, 200]]);
        while(!empty($articles = $this->indexWXCObj->getAll("*", ["OR"=>["postID"=>$this->postIDs],"limit"=>[$i*200, 200]]))){
            $i ++;
            foreach($articles as $article){
                $this->updatePostRead($article);
            }
        }
    }
// 2020-03_blog_1037
// https://count.wenxuecity.com/service/count/script/do.php?clear=1&type=52434&id=201810_6897&refresh=true
    function updatePostRead($article){
        [$tableTime, $postID] = $this->extractTableAndPostID($article['wxc_postid']);
        $wxcPost = $this->wxcPostObj->getOne("*",["postid"=>$postID],"blog_{$tableTime}_post");
        $blogId = $wxcPost['blogid'];
        $url = "https://count.wenxuecity.com/service/count/script/do.php?clear=1&type={$blogId}&id={$tableTime}_{$postID}&refresh=true";
        $content = $this->curl($url);
        $count = $this->extractNumber($content);
        $this->indexObj->Update(["count_read" => $count], ["postID"=>$article['postID']]);
        echo($article['postID']." updated");
    }


    function extractTableAndPostID($wxcPostTag){
        $pieces = explode("_", $wxcPostTag);
        $tableTime = str_replace("-", "", $pieces[0]);
        $postID = intval($pieces[2]);
        return [$tableTime, $postID];
    }
}
$postUpdater = new PostCountUpdater([150977]);
$postUpdater -> start();

class BloggerCountUpdater extends CountUpdater{

    function __construct($bloggerIds = [])
    {
        $this->bloggerIds = $bloggerIds;
        $this->wxcBloggerObj = load("blog_legacy_blogger_haiwai");
        $this->hwBloggerObj = load("blog_blogger");
    }

    function start(){
        // Get each blogger in both wxc and hw
        $bloggers = [];
        if(empty($this->bloggerIds)) {
            $bloggers = $this->wxcBloggerObj->getAll("*");
        }
        else {
            $bloggers = $this->wxcBloggerObj->getAll("*", ['OR'=>['blogid' => $this->bloggerIds]]);
        }
        
        // Update one by one
        foreach ($bloggers as $blogger){
            $this->updateOneBloggerCount($blogger['blogid'], $blogger['haiwai_userID']);
        }
        
    }

    function updateOneBloggerCount($wxcBloggerId, $hwBloggerId){

        // Fetch the count using curl
        $content = $this->curl("https://count.wenxuecity.com/service/count/script/all.php?clear=1&id=$wxcBloggerId&format=true");
        
        // Convert info into number
        $number = $this->extractNumber($content);
        
        // Update haiwai count
        $this->hwBloggerObj->Update(["count_read" => $number],['userID'=>$hwBloggerId]);
    } 
}

// $bloggerUpdater = new BloggerCountUpdater([59729]);
// $bloggerUpdater -> start();