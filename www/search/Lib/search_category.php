<?php
class search_category  extends Search{
    protected $dictName = "blog";
    protected $index    = "hwcategory";

    public function __construct()
    {
        parent::__construct();
        $this->categorySet = '
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
                            "delimiter" : ",",
                            "keep_both" : true,
                            "convert_type" : "t2s"
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
				  "name": {
					"analyzer": "substring_analyzer",
					"boost": 2,
                    "type": "text",
                    "search_analyzer": "search_sub_analyzer"
                  },
                  "count_article":{
					"type": "integer"
				  },
				  "visible":{
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
        // debug::d(json_decode($this->categorySet, true));
        // exit;
        debug::d($this->indexset(json_decode($this->categorySet)));
    }

    public function get_by_categoryIDs($categoryIDs)
    {
        $categories = $this->get($categoryIDs);
        $categories = json_decode(json_encode($categories), true);
        if(!empty($categories["error"])){
            return [];
        }
        if (is_array($categoryIDs)) {
            $categories_body = [];
            foreach ($categories['docs'] as $doc) {
                if (!empty($doc['found'])) {
                    $doc_body     = $doc['_source'];
                    $categories_body[] = $doc_body;
                }
            }
            return $categories_body;
        }
        return $categories;
    }

    // elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-stconvert/releases/download/v7.6.2/elasticsearch-analysis-stconvert-7.6.2.zip
	public function search_by_name($keyword, $last_score=0, $last_id = 0){
        $query = [];
		if(is_string($keyword)){

			$highlight = array("name"=>$this->object());
			$should = [$this->object(array("match" => array("name"=>$keyword)))];
			$query["should"] =$should;
			$query["must_not"]=array(
				$this->object(array("term" => array("status"=>0))),
				$this->object(array("term" => array("is_publish"=>0)))
			);

			$query["sort"]=[$this->object(array("_score"=>array("order" =>"desc"), "id"=>array("order" =>"desc")))];
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

    public function add_new_categories($categories){
        try{
            $category_ids = [];
            foreach($categories as $category){
                $category_ids[] = $category['id'];
            }
            $result = $this->get_by_categoryIDs($category_ids);
            $categoryID_map = [];
            foreach($result as $post){
				$categoryID_map[$post['id']] = true;
            }
            $count = 0;
            $total = 0;
            $data_string = "";
            foreach($categories as $category){
                $category_formatted = $this->get_formatted_category($category);
                $count++;
                $total++;
                
				if(!empty($categoryID_map[$category['id']])){
					$data_string = $data_string.json_encode(['update' => ["_id"=>$category['id']]]) . "\n";
					$data_string = $data_string.json_encode(array('doc'=>$category_formatted))."\n";
				}
				else{
					$data_string = $data_string.json_encode(['index' => ["_id"=>$category['id']]]) . "\n";
					$data_string = $data_string.json_encode($category_formatted)."\n";
				}
					

                if ($count == 1000) {
					$this->addBulk($data_string);
					$data_string = "";
					$count = 0;
                }
            }
            if (!empty($count)) {
                $this->addBulk($data_string);
            }
            return $total;

        }
        catch(Exception $e){
            return 0;
        }
    }

    public function get_formatted_category($category){
        return [
            "id"=>$category['id'],
            "bloggerID"=>$category['bloggerID'],
            // "userID"=>$category['userID'],
            "name"=>$category['name'],
            "visible"=>$category['visible'],
            "is_publish"=>$category['is_publish']
        ];
    }


    public function update_data(){
$blog_category = load("blog_category");


$first_update_time = 0;
$iter = 0;
$total = 0;
// 更新文集
while(true){    
    $categories = $blog_category->getAll("*", ["limit"=>[$iter*200,200]]);
    if(count($categories)==0){
        break;
    }
    $total += count($categories);
    $this->add_new_categories($categories);
    echo("$total categories updated\n");
    $iter++;
}
    }
}