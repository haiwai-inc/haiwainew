<?php
class article_search extends Search
{

    protected $_dictName = "article";
    protected $_index    = "article";

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
				"id": {
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

    /**
     * 批量添加新文章
     * @param $articles | List of article record
     */
    public function add_new_articles($articles)
    {
        try {
            $article_list = [];
            foreach ($articles as $article) {
                $article_formatted = [
                    "id"      => $article['id'],
                    "userID"  => $article['userID'],
                    "blogID"  => $article['blogID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody'],
                    "tags"    => $article['tags'],
                    "visible" => $article['visible'],
                ];
                $article_list[] = $article_formatted;
                if (count($article_list) == 100) {
                    $this->addBulk($article_list);
                    $article_list = [];
                }
            }

            if (!empty($article_list)) {
                $this->addBulk($article_list);
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

}
