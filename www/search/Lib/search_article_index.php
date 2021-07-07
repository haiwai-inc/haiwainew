<?php
class search_article_index extends Search
{

    protected $dictName = "article";
    protected $index    = "hwarticle_index";

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
                            "type":"custom",
                            "tokenizer" :  "ik_max_word",
						  	"filter": ["lowercase"],
                            "char_filter": ["tsconvert"]
                        },
                        
                        "search_sub_analyzer": {
                            "type":"custom",
                            "tokenizer" :  "ik_smart",
						  	"filter": ["lowercase", "substring"],
                            "char_filter": ["tsconvert"]
                        }
					},
					"filter": {
						"substring": {
							"type": "ngram",
							"min_gram": 1,
							"max_gram": 6
						}
					},
					"char_filter": {
                        "tsconvert" : {
                            "type" : "stconvert",
                            "delimiter" : ",",
                            "keep_both" : true,
                            "convert_type" : "t2s"
                        }
                      }
				}
			},
			"mappings": {
				"properties": {
				"type": { "type": "keyword" },
				"indexID": {
					"type": "integer"
				},
				"bloggerID": {
					"type": "integer"
				},
				  "postID": {
					"type": "integer"
				  },
				  "buzz":{
					"enabled": false
				  },
				  "msgbody": {
					"analyzer": "substring_analyzer",
                    "search_analyzer":"search_sub_analyzer",
					"type": "text"
				  },
				  "userID":{
					"type": "integer"
				  },
				  "title": {
					"analyzer": "substring_analyzer",
                    "search_analyzer":"search_sub_analyzer",
					"boost": 2,
					"type": "text"
                  },
                  "tags":{
                      "type":"integer"
				  },
				  "count_read":{
					"type":"integer"
				},
				"count_like":{
					"type":"integer"
				},
				  "create_date": {
					"type": "integer"
				  },
				  "edit_date": {
					"type": "integer"
				  },
				  "buzz_date": {
					"type": "integer"
				  },
				  "visible": { 
					"type": "integer"
				  },
				  "is_publish":{
					"type": "integer"
				  }
				}
			  }
        }';
    }

    public function initialize_index()
    {
        debug::d($this->indexset(json_decode($this->articleSet)));
	}

	public function delete_index(){

	}

	private function get_time_string($timestamp){
		// return gmdate("Y-m-d h:m:s", $timestamp);
		return $timestamp;
	}
	

	/**
	 * 根据标签ID搜索文章
	 * @param array $tags | array of tag ids
	 * @param array/float $last_score | array of last articles' values or single value for paging, consistent with order
	 * @param array $order | how the result is sorted
	 * @param int $limit | number of results per search
	 * 
	 */
	public function search_tags($tags, $last_score = 0, $order = ["count_read"=>["order"=>"desc"]], $limit = 30){
		$query["should"] = [];
		foreach ($tags as $tag){
			$tag= intval($tag);
			$query["should"][] = $this->object(array("match" => array("tags"=>$tag)));
		}
		// Do not search for any article with visible set to 0
		$query["must_not"]=array(
			$this->object(array("term" => array("visible"=>0)))
		); 
		
		$query["sort"]=[$this->object($order)];
		if(!empty($last_score)){
			if(is_array($last_score)){
				$query["search_after"] = $last_score;
			}
			else{
				$query["search_after"] = [$last_score];
			}
		}

		$rs = $this->search($query,null,null, $limit);
		$rs = json_decode(json_encode($rs), true);
		$article_index_obj = load("article_indexing");
		//Remove unused string from the result
		$rs = $article_index_obj -> format_string($rs);
		$rs = $article_index_obj -> format_pic($rs);
		
		$articles = [];
		foreach ($rs as $k=>$v){
			$articles[]= [
				'postID'=>$v['postID'],
				'userID'=>$v['userID'],
			    'create_date'=>$v['create_date'],
				"postInfo_postID" => $v
			];
		}
		return $articles;
	}

	/**
	 * Get one or multiple postInfo with one or a list of postID
	 * @param postIDs | Array of postID
	 * @param full_msg | Whether the full post body should be kept
	 * @return posts | An array of posts
	 */
	public function get_by_postIDs($postIDs, $full_msg = false){

		$posts = $this->get($postIDs);
		$posts = json_decode(json_encode($posts),true);
		// Fetching multiple post id, structure is different 
		if(is_array($postIDs)){
			$posts_body = [];
			foreach($posts['docs'] as $doc){
				if(!empty($doc['found'])){
					$doc_body = $doc['_source'];
					if(empty($full_msg)){
					    $doc_body['msgbody'] = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/is', "$1$3", $doc_body['msgbody']);
					    $doc_body['msgbody'] = strip_tags($doc_body['msgbody']);
						$doc_body['msgbody'] = strstr($doc_body['msgbody'], 1000);
					}
					$posts_body[] = $doc_body;
				}
			}
			return $posts_body;
		}
		return $posts;
	}

	/**
	 * Get a map with postID as key and postInfo as value
	 * @param postIDs | Array of postID
	 * @param full_msg | Whether the full post body should be kept
	 * @return map | Map[postID => postBody]
	 */
	public function get_postID_map($postIDs, $full_msg = false){
		$rs = [];
		$posts = $this->get_by_postIDs($postIDs, $full_msg);
		foreach($posts as $post){
			$rs[$post['postID']] = $post;
		}
		return $rs;
	}

	/**
	 * Insert postinfo into a list of articles or search result
	 * @param rs | The main body, list of item that need postInfo inserted
	 * @param hashID | The name of the field with postID
	 * @param full_msg 
	 * @return rs with postInfo inserted
	 */
	public function get_postInfo($rs,$hashID='postID', $full_msg=false){
		if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
			}
			$hash_posts = $this->get_postID_map($tmp_rs_id, $full_msg);
			
	        foreach($rs as $k=>$v){
				if(empty($hash_posts[$v[$hashID]])){
					unset($rs[$k]);
					continue;
				}
	            $rs[$k]["postInfo_{$hashID}"]=$hash_posts[$v[$hashID]];
	        }
	    } 
	    
	    return $rs;
	}

	/**
	 * Fetch one post and insert into the es
	 * @param int $postID | The id of the post to update
	 * @return boolean true if sucess, none if not
	 */
    public function fetch_and_insert($postID)
    {

        if(empty($postID)){
            return;
        }
        
        if(!is_array($postID)){
            $postID = [$postID];
		}

		$obj_article_indexing=load("article_indexing");
		$rs_article_indexing=$obj_article_indexing->getAll("*",['OR'=>['postID'=>$postID]]);
		
		//补全帖子所有分表信息
		if(!empty($rs_article_indexing)){
		    foreach($rs_article_indexing as $k=>$v){
		        $rs_article_indexing=$obj_article_indexing->get_basic_articleinfo($rs_article_indexing,$k,$v);
		    }
		}
		$rs_article_indexing=$obj_article_indexing->format_string($rs_article_indexing,['msgbody','title'],0);
		
		//导入ES
		$this->add_new_articles($rs_article_indexing);
		return true;
    }

	public function insert_doc($article){
		$article_formatted = [
			"type"	=> $article['typeID'],
			"indexID"      => $article['id'],
			"userID"  => $article['userID'],
			"postID"  => $article['postID'],
			"title"   => $article['title'],
			"msgbody" => $article['msgbody'],
			"tags"    => $article['tags'],
			"visible" => $article['visible'],
			"is_publish" => $article['is_publish'],
			"create_date"    => $this-> get_time_string($article['create_date']),
			"edit_date"    => $this-> get_time_string($article['edit_date']),
			"buzz_date"    => $this-> get_time_string($article['buzz_date']),
			"count_read"=>$article['count_read'],
			"count_buzz"=>$article['count_buzz'],
			"pic" => $article['pic'],
			"buzz" => $article['buzz'],
		];

		return $this->add($article_formatted, $article['postID']);
	}

    /**
     * 批量添加新文章
     * @param $articles | List of article record
     */
    public function add_new_articles($articles)
    {
		
        try {
			$count = 0;
			$total = 0;
			$data_string = "";
			$postIDs = [];
			$postID_map = [];
			foreach ($articles as $article) {
				$postIDs[] = $article['postID'];
			}
			// Search for the postIDs from the ES to see if they exists already
			$result = $this->get_by_postIDs($postIDs);
			foreach($result as $post){
				$postID_map[$post['postID']] = true;
			}
            foreach ($articles as $article) {
				// Only insert articles with tree level = 0
				if(!empty($article['treelevel']))
					continue;
                $article_formatted = [
					"type"	=> $article['typeID'],
                    "indexID"      => $article['id'],
                    "userID"  => $article['userID'],
                    "postID"  => $article['postID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody'],
                    "tags"    => $article['tags'],
                    "create_date"    => $this-> get_time_string($article['create_date']),
                    "edit_date"    => $this-> get_time_string($article['edit_date']),
                    "buzz_date"    => $this-> get_time_string($article['buzz_date']),
					"visible" => $article['visible'],
					"is_publish" => $article['is_publish'],
					"count_read"=>$article['count_read'],
					"count_buzz"=>$article['count_buzz'],
					"pic" => $article['pic'],
					"buzz" => $article['buzz'],
				];
				$count++;
				$total++;
				// If the article already exists in es, update
				if(!empty($postID_map[$article['postID']])){
					$data_string = $data_string.json_encode(['update' => ["_id"=>$article['postID']]]) . "\n";
					$data_string = $data_string.json_encode(array('doc'=>$article_formatted))."\n";
				}
				// If the article does not exists in es, insert
				else{
					$data_string = $data_string.json_encode(['index' => ["_id"=>$article['postID']]]) . "\n";
					$data_string = $data_string.json_encode($article_formatted)."\n";
				}
					
				// Bulk update, 1000 at a time
                if ($count == 1000) {
					$this->addBulk($data_string);
					$data_string = "";
					$count = 0;
                }
            }
			// Insert the result
            if (!empty($count)) {
                $this->addBulk($data_string);
            }
            return $total;
        } catch (Exception $e) {
            return 0;
        }
	}
	

	/**
	 * Search for post with keyword
	 * @param string $keyword | The keyword to search for
	 * @param double $last_score | The lowest score from previous search, used for paging
	 * @param int $last_id | The last id of the previous search, used for paging
	 * @return array rs Array of posts
	 */
	function search_by_keyword($keyword, $last_score = 0, $last_id = 0){
		$query = [];
		if(is_string($keyword)){
			$highlight = array("title"=>$this->object(), "msgbody"=>$this->object());
			
			$query["should"] =[$this->object(array("match" => array("title"=>$keyword))),$this->object(array("match" => array("msgbody"=>$keyword)))];
			$query["must_not"]=array(
				$this->object(array("term" => array("visible"=>0))),
				$this->object(array("term" => array("is_publish"=>0)))
			);

			$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc") ,"postID"=>array("order"=>"desc"),"edit_date"=>array("order"=>"desc")))];
			if(!empty($last_score)){ 
				$query["search_after"]=[$last_score, $last_id, 0];
			}
			$query["highlight"] = array(
				"pre_tags" => array( "<span style='color:#39B8EB'>" ),
				"post_tags" => array( "</span>" ),
				"fields" =>  $highlight
			);
		}
		else{
			$query = $keyword;
		}
		$rs = $this->search($query);
		$rs = json_decode(json_encode($rs), true);
		return $rs;
	}



	public function update_data(){
		$obj = load("search_update");
		$obj->start();
	}
}
