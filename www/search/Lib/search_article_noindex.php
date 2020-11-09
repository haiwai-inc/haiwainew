<?php
class search_article_noindex extends Search
{

    protected $dictName = "article";
    protected $index    = "hwarticle_noindex";

    public function __construct()
    {
        parent::__construct();
        $this->articleSet = '
		{
			"settings": {
				"index.number_of_replicas":"2",
				"index.number_of_shards":"5",
				"index.max_ngram_diff":"13",
				"analysis": {
					"analyzer": {
						"substring_analyzer": {
						  	"tokenizer": "standard",
						  	"filter": ["lowercase", "substring"]
						}
					},
					"filter": {
						"substring": {
							"type": "nGram",
							"min_gram": 2,
							"max_gram": 15
						}
					}
				}
			},
			"mappings": {
				"properties": {
				  "postID": {
					"enabled": false
				  },
				  "msgbody": {
					"enabled": false
				  },
				  "title": {
					"enabled": false
				  },
				  "pic": {
					"enabled": false
				  },
				  "buzz":{
					"enabled": false
				  },
				  "tags":{
					"enabled": false
				  }
				}
			  }
        }';
    }

    public function initialize_index()
    {
        debug::d($this->indexset(json_decode($this->articleSet)));
    }

    public function insert_doc($article)
    {
        $article_formatted = [
			"postID"  => $article['postID'],
			"title"   => $article['title'],
			"msgbody" => $article['msgbody_origin'],
			"pic"     => $article['pic'],
			"buzz"    => $article['buzz'],
			"tags"    => $article['tags'],
        ];
        return $this->add($article_formatted, $article['postID']);
	}
	
	public function get_formatted_doc($article){
		return [
			"postID"  => $article['postID'],
			"title"   => $article['title'],
			"msgbody" => $article['msgbody_origin'],
			"pic"     => $article['pic'],
			"buzz"    => $article['buzz'],
			"tags"    => $article['tags'],
        ];
	}

    public function update_buzz($postID, $userID)
    {
        $article = $this->get($postID);
        if (!empty($article['_source'])) {
            $buzz_list = $article['_source']['buzz'];
            if (!in_array($userID, $buzz_list)) {
                $buzz_list[] = $userID;
            } else {
                $index     = array_search($userID, $buzz_list);
                $buzz_list = array_splice($buzz_list, $index, 1);
            }
            $this->update(['buzz' => $buzz_list], $postID);
            return 1;
        }
        return 0;
	}
	
	public function get_by_postIDs($postIDs, $full_msg = false){
		$posts = $this->get($postIDs);
		$posts = json_decode(json_encode($posts),true);
		if(is_array($postIDs)){
			$posts_body = [];
			foreach($posts['docs'] as $doc){
				if(!empty($doc['found'])){
					$doc_body = $doc['_source'];
					if(empty($full_msg)){
						$doc_body['msgbody'] = strip_tags($doc_body['msgbody']);
						$doc_body['msgbody'] = substr($doc_body['msgbody'], 0, 1000);
					}
					$posts_body[] = $doc_body;
				}
			}
			return $posts_body;
		}
		return $posts;
	}

	public function get_postID_map($postIDs, $full_msg = false){
		$rs = [];
		$posts = $this->get_by_postIDs($postIDs, $full_msg);
		foreach($posts as $post){
			$rs[$post['postID']] = $post;
		}
		return $rs;
	}

	public function get_postInfo($rs,$hashID='postID', $full_msg=false){
		if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
			}
			;
			$hash_posts = $this->get_postID_map($tmp_rs_id, $full_msg);
	        foreach($rs as $k=>$v){
	            $rs[$k]["postInfo_{$hashID}"]=$hash_posts[$v[$hashID]];
	        }
	    } 
	    
	    return $rs;
	}

    /**
     * 批量添加新文章
     * @param $articles | List of article record
     */
    public function add_new_articles($articles)
    {

        try {
			$count       = 0;
			$total = 0;
			$data_string = "";
			$postID_map = [];
			foreach ($articles as $article) {
				$postIDs[] = $article['postID'];
			}
			$result = $this->get_by_postIDs($postIDs);
			foreach($result as $post){
				$postID_map[$post['postID']] = true;
			}
            foreach ($articles as $article) {
                $article_formatted = [
                    "postID"  => $article['postID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody_origin'],
                    "pic"     => $article['pic'],
                    "buzz"    => $article['buzz'],
                    "tags"    => $article['tags'],
                ];
				$count++;
				$total++;
				if(!empty($postID_map[$article['postID']])){
					$data_string = $data_string.json_encode(['update' => ["_id"=>$article['postID']]]) . "\n";
					$data_string = $data_string.json_encode(array('doc'=>$article_formatted))."\n";
				}
				else{
					$data_string = $data_string.json_encode(['index' => ["_id"=>$article['postID']]]) . "\n";
					$data_string = $data_string.json_encode($article_formatted)."\n";
				}

                if ($count == 1000) {
                    // debug::d("adding");
                    // debug::d($this->addBulk($data_string));
                    $this->addBulk($data_string);
                    $data_string = "";
                    $count       = 0;
                }
            }

            if (!empty($count)) {
                $this->addBulk($data_string);
            }
            return $total;
        } catch (Exception $e) {
            debug::d($e);
            return 0;
        }
    }

    public function fetch_and_insert($postIDs)
    {
		if(!is_array($postIDs)){
			$postIDs = [$postIDs];
		}

        $article_post_obj    = load("article_post");
        $article_tag_obj     = load("article_post_tag");
        $article_buzz_obj    = load("article_post_buzz");
		$tag_obj             = load("article_tag");
		$index_obj    = load("article_indexing");
		

		$articles = $index_obj->getAll(["id", "userID", 'typeID', "postID", "visible"], ["OR"=>["postID"=>$postIDs]]);
		
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

		foreach (range(0, 9) as $i) {
			$ids           = $id_list[$i];
			$tag_post_ids  = $post_id_list[$i];
			$article_posts = [];
			if(!empty($ids)){
				$article_posts = $article_post_obj->getAll("*", ["OR" => ["id" => $ids]], "post_{$i}");
			}
			$article_tags  = [];
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
	
			foreach ($article_buzz as $buzz){
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
		$tags = [];
		if(!empty($tag_id_list)){
			$tags       = $tag_obj->getAll(["id", "name"], ["OR" => ['ID' => $tag_id_list]]);
		}
		foreach ($tags as $tag) {
			$tag_id_map[$tag['id']] = $tag['name'];
		}
		unset($tags);
	
		$final_list = [];
		$final_pool_list = [];
	
		foreach (range(0, count($articles) - 1) as $i) {
			$postID = $articles[$i]['postID'];
			if (empty($article_post_map[$postID])) {
				continue;
			}
	
			$post_info                      = $article_post_map[$postID];
			$articles[$i]['title']          = $post_info['title'];
			$articles[$i]['msgbody_origin'] = $post_info['msgbody'];
			$articles[$i]['pic']            = strip_tags($post_info['pic']);
			$articles[$i]['buzz']  = [];
			if(!empty($article_buzz_map[$postID])){
				$articles[$i]['buzz']            = $article_buzz_map[$postID];
			}
			$tag_list                       = [];
			if (!empty($article_tag_map[$postID])) {
				$tag_list = $article_tag_map[$postID];
			}
	
			$articles[$i]['tags'] = $tag_list;
			$final_pool_list[] = $articles[$i];
			unset($msgbody);
			unset($post_info);
			unset($tag_list);
			unset($postID);
		}

		$this->add_new_articles($final_pool_list);

		$saved_articles = json_decode(json_encode($this->get($postIDs)), true)['docs'];
		$saved_map = [];
		foreach($saved_articles as $saved_article){
			$saved_map[$saved_article['_id']] = !empty($saved_article['found']);
		}
		foreach($final_pool_list as $article){
			if($saved_map[$article['postID']]){
				$this->update($article, $article['postID']);
				debug::d("Updated ".$article['postID']);
			}
			else {
				$this->add($article, $article['postID']);
				debug::d("Added ".$article['postID']);
			}
		}
		return 1;
    }
}
