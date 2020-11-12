<?php
class search_category  extends Search{
    protected $dictName = "blog";
    protected $index    = "hwcategory";

    public function __construct()
    {
        parent::__construct();
        $this->categorySet = '{
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
				  "bloggerID": {
					"type": "integer"
				  },
				  "userID": {
					"type": "integer"
				  },
				  "name": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
				  },
				  "visible":{
					"type": "integer"
				  }
				}
			  }
        }';
    }

    public function initialize_index()
    {
        debug::d($this->indexset(json_decode($this->bloggerSet)));
    }


	public function search_by_name($keyword, $last_score=0){
        $query = [];
		if(is_string($keyword)){

			$highlight = array("title"=>$this->object(), "msgbody"=>$this->object());
			$should = [$this->object(array("match" => array("name"=>$keyword)))];
			$query["should"] =$should;
			$query["must_not"]=array(
				$this->object(array("term" => array("status"=>0)))
			);

			$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc")))];
			if(!empty($last_score)){
				$query["search_after"] = [$last_score, 0];
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