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
						  	"filter": ["lowercase", "substring"],
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
							"type": "edge_ngram",
							"min_gram": 1,
							"max_gram": 50
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
					"type": "date",
					"format": "yyyy-MM-dd HH:mm:ss"
				  },
				  "edit_date": {
					"type": "date",
					"format": "yyyy-MM-dd HH:mm:ss"
				  },
				  "like_date": {
					"type": "date",
					"format": "yyyy-MM-dd HH:mm:ss"
				  },
				  "visible": { 
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
		return gmdate("Y-m-d h:m:s", $timestamp);
	}
	

	public function search_tags($tags, $last_score = 0, $order = array("count_read"=>array("order"=>"desc"))){
		$query["should"] = [];
		foreach ($tags as $tag){
			$tag= intval($tag);
			$query["should"][] = $this->object(array("match" => array("tags"=>$tag)));
		}
		$query["must_not"]=array(
			$this->object(array("term" => array("visible"=>0)))
		); 

		$query["sort"]=[$this->object($order)];
		if(!empty($last_score)){
			$query["search_after"] = [$last_score];
		}
		$rs = $this->search($query,null,null);
		$rs = json_decode(json_encode($rs), true);
		$article_index_obj = load("article_indexing");
		$rs = $article_index_obj -> format_string($rs);
		$rs = $article_index_obj -> format_pic($rs);
		
		$articles = [];
		foreach ($rs as $k=>$v){
			$articles[]= [
				'postID'=>$v['postID'],
				'userID'=>$v['userID'],
				"postInfo_postID" => $v
			];
		}
		return $articles;
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
						$doc_body['msgbody'] = strstr($doc_body['msgbody'], 1000);
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
				if(empty($hash_posts[$v[$hashID]])){
					unset($rs[$k]);
					continue;
				}
	            $rs[$k]["postInfo_{$hashID}"]=$hash_posts[$v[$hashID]];
	        }
	    } 
	    
	    return $rs;
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
			"create_date"    => $this-> get_time_string($article['create_date']),
			"edit_date"    => $this-> get_time_string($article['edit_date']),
			"like_date"    => $this-> get_time_string($article['like_date']),
			"count_read"=>$article['count_read'],
			"count_like"=>$article['count_like'],
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
			$result = $this->get_by_postIDs($postIDs);
			foreach($result as $post){
				$postID_map[$post['postID']] = true;
			}
            foreach ($articles as $article) {
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
                    "like_date"    => $this-> get_time_string($article['like_date']),
					"visible" => $article['visible'],
					"count_read"=>$article['count_read'],
					"count_like"=>$article['count_like'],
					"pic" => $article['pic'],
					"buzz" => $article['buzz'],
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
					$count = 0;
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
	

	function search_by_keyword($keyword, $last_score = 0){
		$query = [];
		if(is_string($keyword)){
			$highlight = array("title"=>$this->object(), "msgbody"=>$this->object());
			
			$query["should"] =[$this->object(array("match" => array("title"=>$keyword))),$this->object(array("match" => array("msgbody"=>$keyword)))];
			$query["must_not"]=array(
				$this->object(array("term" => array("visible"=>0)))
			);

			$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc"),"edit_date"=>array("order"=>"desc")))];
			if(!empty($last_score )){
				$query["search_after"]=[$last_score, 0];
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
}
