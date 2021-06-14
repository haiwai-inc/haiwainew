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
							"type": "ngram",
							"min_gram": 1,
							"max_gram": 14
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

    /**
	 * Get one or multiple bloggers' info with one or a list of bloggerID
	 * @param bloggerIDs | Array of bloggerID
	 * @return bloggers | An array of bloggers
	 */
    public function get_by_bloggerIDs($bloggerIDs)
    {
        $bloggers = $this->get($bloggerIDs);
        $bloggers = json_decode(json_encode($bloggers), true);
        if (is_array($bloggerIDs)) {
            $bloggers_body = [];
            foreach ($bloggers['docs'] as $doc) {
                if (!empty($doc['found'])) {
                    $doc_body     = $doc['_source'];
                    $bloggers_body[] = $doc_body;
                }
            }
            return $bloggers_body;
        }
        return $bloggers;
    }

    /**
	 * Get a map with bloggerID as key and bloggerInfo as value
	 * @param $bloggerIDs | Array of bloggerIDs
	 * @return map | Map[bloggerID => blogger info]
	 */
    public function get_bloggerID_map($bloggerIDs)
    {
        $rs    = [];
        $bloggerIDs = $this->get_by_bloggerIDs($bloggerIDs);
        foreach ($bloggerIDs as $blogger) {
            $rs[$blogger['bloggerID']] = $blogger;
        }
        return $rs;
    }

    /**
     * 批量添加新博客
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

    /**
     * Search for bloggers using username or blog name
     * @param string $keyword | keyword to search
     * @param double $last_score | Lowest score from previous search, for pagination, 0 if it's the first page
     * @param string $type | "user" => use only username, "blogger" => use only blogger name, "all" => use both
     * @param int $last_id | The last blogger id from previous search, for pagination, 0 if it's the first page
     * @return array rs | Array of bloggers
     */
    public function search_by_name($keyword, $last_score = 0, $type = "all", $last_id = 0)
    {
        $query = [];
        if (is_string($keyword)) {

            $highlight = array("name" => $this->object(), "username" => $this->object());
            $should = [];
            if ($type == "all" || $type == "user") {
                $should[] = $this->object(array("match" => array("username" => $keyword)));
            }
            if ($type == "all" || $type == "blogger") {
                $should[] = $this->object(array("match" => array("name" => $keyword)));
            }
            $query["should"] = $should;
            $query["must_not"] = array(
                $this->object(array("term" => array("status" => 0)))
            );

            $query["sort"] = [$this->object(array("_score" => array("order" => "desc"), "userID" => array("order" => "desc")))];
            if (!empty($last_score)) {
                $query["search_after"] = [$last_score, $last_id];
            }
            $query["highlight"] = array(
                "pre_tags" => array("<span style='color:#39B8EB'>"),
                "post_tags" => array("</span>"),
                "fields" =>  $highlight
            );
        } else {
            $query = $keyword;
        }
        $rs = $this->search($query);
        $rs = json_decode(json_encode($rs), true);
        return $rs;
    }

    public function update_data($time = 0)
    {
        $search_blogger = load("search_blogger");
        $blogger_obj = load("blog_blogger");
        $user_obj = load("account_user");
        $search_category = load("search_category");
        $blog_category = load("blog_category");

        // $first_update_time = 0;
        $iter = 0;
        $total = 0;
        $total_category = 0;
        while (true) {
            $bloggers = $blogger_obj->getAll(["id", "userID", "name"], ["update_date, >=" => $time, "limit" => [$iter * 200, 200]]);
            if (count($bloggers) == 0) {
                break;
            }
            $total += count($bloggers);
            $bloggerIDs = [];
            foreach ($bloggers as $blogger) {
                $bloggerIDs[] = $blogger['id'];
            }
            $bloggers = $user_obj->get_basic_userinfo($bloggers, "userID");
            $search_blogger->add_new_bloggers($bloggers);
            $categories = $blog_category->getAll("*", ["OR" => ['bloggerID' => $bloggerIDs]]);
            $total_category += count($categories);
            $search_category->add_new_categories($categories);
            echo ("$total blogger updated\n");
            echo ("$total_category category updated\n");
            $iter++;
        }
    }
}
