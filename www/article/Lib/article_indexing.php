<?php
class article_indexing extends Model
{
    protected $tableName = "indexing";
    protected $dbinfo = array("config" => "article", "type" => "MySQL");

    //根据跟帖，补全主贴信息
    function get_article_info_by_comment($rs){
        if(empty($rs)){
            return [];
        }
        foreach($rs as $v){
            $typeID_account_notification[]=$v['typeID'];
            $hash_account_notification[$v['typeID']]=$v;
        }
        
        //评论信息
        $rs_article_indexing_comment=$this->getAll(['id','postID','basecode','userID','bloggerID','count_buzz','count_read','count_comment'],['OR'=>['postID'=>$typeID_account_notification]]);
        
        //加入评论ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_article_indexing_comment=$obj_search_article_noindex->get_postInfo($rs_article_indexing_comment);
        
        //加入评论用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing_comment=$obj_account_user->get_basic_userinfo($rs_article_indexing_comment,"userID");
        
        if(empty($rs_article_indexing_comment)){
            return [];
        }
        foreach($rs_article_indexing_comment as $v){
            $basecode_article_indexing_comment[]=$v['basecode'];
            $v['notification']=$hash_account_notification[$v['postID']];
            $hash_article_indexing_comment[$v['basecode']][]=$v;
        }
        
        //主贴信息
        $rs_article_indexing_main=$this->getAll(['id','postID','basecode','userID','bloggerID','count_buzz','count_read','count_comment'],['treelevel'=>0,'OR'=>['basecode'=>$basecode_article_indexing_comment]]);
        if(empty($rs_article_indexing_main)){
            return [];
        }
        
        //加入主贴ES信息
        $rs_article_indexing_main=$obj_search_article_noindex->get_postInfo($rs_article_indexing_main);
        
        //加入主贴用户信息
        $rs_article_indexing_main=$obj_account_user->get_basic_userinfo($rs_article_indexing_main,"userID");
        
        foreach($rs_article_indexing_main as $k=>$v){
            $rs_article_indexing_main[$k]['comment']=$hash_article_indexing_comment[$v['basecode']];
        }
        
        return $rs_article_indexing_main;
    }
    
    
    //在包含postID的数组里，补全帖子的 ,点赞计数，留言计数，阅读计数
    function get_article_count($rs,$hashID='postID'){
        if(!empty($rs)){
            foreach($rs as $v){
                $id_rs[]=$v[$hashID];
            }
            
            //点赞计数，留言计数，阅读计数
            $rs_article_count=$this->getAll(['postID','count_buzz','count_read','count_comment'],['OR'=>['postID'=>$id_rs]]);
            if(!empty($rs_article_count)){
                foreach($rs_article_count as $v){
                    $hash_article_count[$v['postID']]=$v;
                }
                foreach($rs as $k=>$v){
                    $rs[$k]["countinfo_{$hashID}"]=$hash_article_count[$v['postID']];
                }
            }
        }
        
        return $rs;
    }
    
	public function get_single_article_info($postID){
		$article = $this->getOne("*", ['postID'=>$postID]);
		if(empty($article) ) return 0;
        $article_post_obj = load("article_post");
        $article_tag_obj  = load("article_post_tag");
        $article_buzz_obj = load("article_post_buzz");
		$table_id                      = $article['userID'] % 10;
		$tag_table_id                  = $article['postID'] % 10;
		$article_post = $article_post_obj->getOne("*", ["id" => $postID], "post_{$table_id}");
		$article_tags = $article_tag_obj->getAll("*", ["OR" => ["postID" => $postID]], "post_tag_{$tag_table_id}");
		$article_buzz = $article_buzz_obj->getAll("*", ["OR" => ["postID" => $postID]], "post_buzz_{$tag_table_id}");
		if(empty($article_post)) return 0;
		$article['title'] = $article_post['title'];
		$article['msgbody'] = $article_post['msgbody'];
		$article['tag'] = [];
		$article['buzz'] = [];
		foreach($article_tags as $tag){
			$article['tag'][] = $tag['tagID'];
		}
		foreach($article_buzz as $buzz){
			$article['buzz'][] = $buzz['userID'];

		}
		return $article;
	}
    public function get_article_info($where)
    {
        $article_post_obj = load("article_post");
        $article_tag_obj  = load("article_post_tag");
        $article_buzz_obj = load("article_post_buzz");
        $tag_obj          = load("article_tag");
        try {
            $articles         = $this->getAll("*", $where);
            $id_list          = [];
            $post_id_list     = [];
            $article_post_map = [];
            $article_tag_map  = [];
            $article_buzz_map = [];
            foreach (range(0, 9) as $number) {
                $id_list[]      = [];
                $post_id_list[] = [];
            }

            foreach ($articles as $article) {
                $table_id                      = $article['userID'] % 10;
                $tag_table_id                  = $article['postID'] % 10;
                $id_list[$table_id][]          = $article['postID'];
                $post_id_list[$tag_table_id][] = $article['postID'];
                unset($table_id);
                unset($tag_table_id);
            }

            $tag_id_list = [];

            //Get article title, content and tagIDs
            foreach (range(0, 9) as $i) {
                $ids           = $id_list[$i];
                $tag_post_ids  = $post_id_list[$i];
                $article_posts = [];
                if (!empty($ids)) {
                    $article_posts = $article_post_obj->getAll("*", ["OR" => ["id" => $ids]], "post_{$i}");
                }
                $article_tags = [];
                $article_buzz = [];
                if (!empty($tag_post_ids)) {
                    $article_tags = $article_tag_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_tag_{$i}");
                    $article_buzz = $article_buzz_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_buzz_{$i}");
                }

                foreach ($article_posts as $post) {
                    $article_post_map[$post['id']] = $post;
                }

                foreach ($article_tags as $tag) {
                    if (empty($article_tag_map[$tag['postID']])) {
                        $article_tag_map[$tag['postID']] = [];
                    }
                    $article_tag_map[$tag['postID']][] = $tag["tagID"];
                    $tag_id_list[]                     = $tag["tagID"];
                }

                foreach ($article_buzz as $buzz) {
                    if (empty($article_buzz_map[$buzz['postID']])) {
                        $article_buzz_map[$buzz['postID']] = [];
                    }
                    $article_buzz_map[$buzz['postID']][] = $buzz['userID'];
                }

                unset($ids);
                unset($tag_post_ids);
                unset($article_posts);
                unset($article_tags);
            }

            //Get tag info
            $tag_id_map = [];
            $tags       = [];
            if (!empty($tag_id_list)) {
                $tags = $tag_obj->getAll(["id", "name"], ["OR" => ['ID' => $tag_id_list]]);
            }
            foreach ($tags as $tag) {
                $tag_id_map[$tag['id']] = $tag['name'];
            }
            unset($tags);

            $final_list = [];

            if (count($articles) < 1) {
                return [];
            }
            foreach (range(0, count($articles) - 1) as $i) {
                $postID = $articles[$i]['postID'];
                if (empty($article_post_map[$postID])) {
                    continue;
                }

                $post_info                      = $article_post_map[$postID];
                $articles[$i]['title']          = $post_info['title'];
                $msgbody                        = strip_tags($post_info['msgbody']);
                $articles[$i]['msgbody_origin'] = $post_info['msgbody'];
                $articles[$i]['msgbody']        = strip_tags($post_info['msgbody']);
                $articles[$i]['pic']            = strip_tags($post_info['pic']);
                $articles[$i]['buzz']           = [];
                if (!empty($article_buzz_map[$postID])) {
                    $articles[$i]['buzz'] = $article_buzz_map[$postID];
                }
                $tag_list = [];
                if (!empty($article_tag_map[$postID])) {
                    $tag_list = $article_tag_map[$postID];
                }

                $articles[$i]['tags'] = $tag_list;
                $final_list[]         = $articles[$i];
                unset($msgbody);
                unset($post_info);
                unset($tag_list);
                unset($postID);
            }

            unset($id_list);
            unset($post_id_list);
            unset($article_post_map);
            unset($article_tag_map);
            unset($articles);
            unset($tag_id_list);
            unset($tag_id_map);
            return $final_list;
            unset($final_list);
        } catch (Exception $e) {
            return [];
        }
	}


	public function format_string($rs, $keys = ["msgbody"], $max_character = 1000){
		foreach($rs as $k=>$v){
			foreach($keys as $key){
				$v[$key] = strip_tags($v[$key]);
				if(!empty($max_character))
					$v[$key] = strings::subString($v[$key], $max_character);
			}
			$rs[$k] = $v;
		}
		return $rs;
	}
}
