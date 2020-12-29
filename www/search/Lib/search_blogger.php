<?php
class search_blogger extends Search
{

    protected $dictName = "blog";
    protected $index    = "hwblogger";

    public function __construct()
    {
        parent::__construct();
		$this->bloggerSet = '

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
							"delimiter" : "#",
							"keep_both" : false,
							"convert_type" : "t2s"
						}
					  }
				}
			},
			"mappings": {
				"properties": {
				  "bloggerID": {
					"type": "integer"
				  },
				  "userID": {
					"type": "integer"
				  },
				  "username": {
					"analyzer": "substring_analyzer",
                    "search_analyzer":"search_sub_analyzer",
					"boost": 2,
					"type": "text"
				  },
				  "name": {
					"analyzer": "substring_analyzer",
					"boost": 2,
					"type": "text",
                    "search_analyzer":"search_sub_analyzer"
				  },
				  "verified": {
					"type": "integer"
				  },
				  "status":{
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

    public function get_by_bloggerIDs($bloggerIDs)
    {
        $posts = $this->get($bloggerIDs);
        $posts = json_decode(json_encode($posts), true);
        if (is_array($bloggerIDs)) {
            $posts_body = [];
            foreach ($posts['docs'] as $doc) {
                if (!empty($doc['found'])) {
                    $doc_body     = $doc['_source'];
                    $posts_body[] = $doc_body;
                }
            }
            return $posts_body;
        }
        return $posts;
    }

    public function get_bloggerID_map($bloggerIDs)
    {
        $rs    = [];
        $posts = $this->get_by_bloggerIDs($bloggerIDs);
        foreach ($posts as $post) {
            $rs[$post['bloggerID']] = $post;
        }
        return $rs;
    }

    /**
     * 批量添加
     * @param $bloggers | List of bloggers
     */
    public function add_new_bloggers($bloggers)
    {
        try {

            $count       = 0;
            $total       = 0;
            $data_string = "";

            if (empty($bloggers)) {
                return 0;
            }

            $bloggerIDs = [];
            foreach ($bloggers as $blogger) {
                $bloggerIDs[] = $blogger['id'];
            }

            $bloggerMap = $this->get_bloggerID_map($bloggerIDs);

            foreach ($bloggers as $blogger) {

                $user              = $blogger["userinfo_userID"];
                $blogger_formatted = [
                    "bloggerID" => $blogger['id'],
                    "userID"    => $blogger['userID'],
                    "username"  => $user['username'],
                    "name"      => $blogger['name'],
                    "verified"  => $user["verified"],
					"status"    => $user["status"]
                ];

                $count++;
                $total++;
                if (!empty($bloggerMap[$blogger['id']])) {
                    $data_string = $data_string . json_encode(['update' => ["_id" => $blogger['id']]]) . "\n";
                    $data_string = $data_string . json_encode(array('doc' => $blogger_formatted)) . "\n";
                } else {
                    $data_string = $data_string . json_encode(['index' => ["_id" => $blogger['id']]]) . "\n";
                    $data_string = $data_string . json_encode($blogger_formatted) . "\n";
                }

                if ($count == 1000) {
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
            return 0;
        }
	} 
	
	public function search_by_name($keyword, $last_score=0, $type="all", $last_id = 0){
		$query = [];
		if(is_string($keyword)){

			$highlight = array("name"=>$this->object(), "username"=>$this->object());
			$should = [];
			if($type=="all"||$type=="user"){
				$should[]=$this->object(array("match" => array("username"=>$keyword)));
			}
			if($type=="all"||$type=="blogger"){
				$should[]=$this->object(array("match" => array("name"=>$keyword)));
			}
			$query["should"] =$should;
			$query["must_not"]=array(
				$this->object(array("term" => array("status"=>0)))
			);

			$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc"), "userID"=>array("order" =>"desc")))];
			if(!empty($last_score)){
				$query["search_after"] = [$last_score, $last_id];
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
