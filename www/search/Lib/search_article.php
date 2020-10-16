<?php
class search_article extends Search
{

    protected $dictName = "article";
    protected $index    = "article";

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
				"indexID": {
					"type": "integer"
				},
				  "blogID": {
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
                      "type":"text"
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
	

	public function search_tags($tags){
		$tag_string = implode(" ", $tags);
		$query["should"] = [];
		foreach ($tags as $tag){
			$query["should"][] = $this->object(array("match" => array("tags"=>$tag)));
		}
		// $query["should"] =[$this->object(array("match" => array("tags"=>$tag_string)))];
		$query["must_not"]=array(
			$this->object(array("term" => array("visible"=>0)))
		);

		$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc")))];
		$rs = $this->search($query);
		$rs = json_decode(json_encode($rs), true);
		return $rs;
	}

    /**
     * 批量添加新文章
     * @param $articles | List of article record
     */
    public function add_new_articles($articles)
    {
		
        try {
			$count = 0;
			$data_string = "";
            foreach ($articles as $article) {
                $article_formatted = [
                    "indexID"      => $article['id'],
                    "userID"  => $article['userID'],
                    "blogID"  => $article['postID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody'],
                    "tags"    => $article['tags'],
                    "visible" => $article['visible'],
				];
				$count++;
				$data_string = $data_string.json_encode(['index' => ["_id"=>$article['postID']]]) . "\n";
				$data_string = $data_string.json_encode($article_formatted)."\n";

                if ($count == 100) {
					debug::d("adding");
                    debug::d($this->addBulk($data_string));
					$data_string = "";
					$count = 0;
                }
            }

            if (!empty($article_list)) {
                $this->addBulk($article_list);
            }
            return 1;
        } catch (Exception $e) {
			debug::d("aa");
			debug::d($e);
            return 0;
        }
    }

}
