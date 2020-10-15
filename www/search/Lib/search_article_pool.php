<?php
class search_article_pool extends Search
{

    protected $dictName = "article";
    protected $index    = "article_body";

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
				  "blogID": {
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
				  }
				}
			  }
        }';
    }

    public function initialize_index()
    {
        debug::d($this->indexset(json_decode($this->articleSet)));
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
                    "blogID"  => $article['postID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody_origin'],
					"pic" => $article['pic'],
					"buzz"=>[]
                    // "visible" => $article['visible'],
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
	
	public function add_buzz($postID, $userID){
		
	}

}
