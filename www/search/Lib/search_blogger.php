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
				  "bloggerID": {
					"type": "integer"
				  },
				  "userID": {
					"type": "integer"
				  },
				  "username": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
				  },
				  "name": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
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

                $user              = $blogger["basic_userinfo_userID"];
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
	
	public function search_by_name($keyword, $last_score=0, $type="all"){
		$query = [];
		if(is_string($keyword)){

			$highlight = array("title"=>$this->object(), "msgbody"=>$this->object());
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
