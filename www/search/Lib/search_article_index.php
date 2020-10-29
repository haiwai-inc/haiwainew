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
				"type": { "type": "keyword" },
				"indexID": {
					"type": "integer"
				},
				  "postID": {
					"type": "integer"
				  },
				  "msgbody": {
					"analyzer": "ik_smart",
					"type": "text"
				  },
				  "userID":{
					"type": "integer"
				  },
				  "title": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
                  },
                  "tags":{
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
	

	public function search_tags($tags, $order = array("postID"=>array("order"=>"desc"))){
		// $tag_string = implode(" ", $tags);
		$query["should"] = [];
		foreach ($tags as $tag){
			$query["should"][] = $this->object(array("match" => array("tags"=>$tag)));
		}
		// $query["should"] =[$this->object(array("match" => array("tags"=>$tag_string)))];
		$query["must_not"]=array(
			$this->object(array("term" => array("visible"=>0)))
		);

		// $query["sort"]=[$this->object(array("_score"=>array("order" =>"desc"), "dateline"=>array("order"=>"desc")))];
		$query["sort"]=[$this->object($order)];
		$rs = $this->search($query,null,null, 100);
		// debug::d($rs);
		$rs = json_decode(json_encode($rs), true);
		return $rs;
	}

	public function get_by_postIDs($postIDs){
		$posts = $this->get($postIDs);
		$posts = json_decode(json_encode($posts),true);
		if(is_array($postIDs)){
			$posts_body = [];
			foreach($posts['docs'] as $doc){
				if(!empty($doc['found'])){
					$posts_body[] = $doc['_source'];
				}
			}
			return $posts_body;
		}
		return $posts;
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
			"pic" => $article['pic'],
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
					"pic" => $article['pic'],
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
                debug::D($this->addBulk($data_string));
            }
            return $total;
        } catch (Exception $e) {
			debug::d($e);
            return 0;
        }
    }

}