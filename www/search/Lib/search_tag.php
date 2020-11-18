<?php
class search_tag extends Search{
    protected $index    = "hwtag";

    public function __construct()
    {
        parent::__construct();
        $this->tagSet = '{
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
        debug::d($this->indexset(json_decode($this->tagSet)));
    }

    public function get_by_tagIDs($tagIDs)
    {
        $tags = $this->get($tagIDs);
        $tags = json_decode(json_encode($tags), true);
        if (is_array($tagIDs)) {
            $tags_body = [];
            foreach ($tags['docs'] as $doc) {
                if (!empty($doc['found'])) {
                    $doc_body     = $doc['_source'];
                    $tags_body[] = $doc_body;
                }
            }
            return $tags_body;
        }
        return $tags;
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

    public function add_new_tags($tags){
        try{
            $tag_ids = [];
            foreach($tags as $tag){
                $tag_ids[] = $tag['id'];
            }
            $result = $this->get_by_tagIDs($tag_ids);
            $tagID_map = [];
            foreach($result as $post){
				$tagID_map[$post['id']] = true;
            }
            $count = 0;
            $total = 0;
            $data_string = "";
            foreach($tags as $tag){
                $tag_formatted = $this->get_formatted_tag($tag);
                $count++;
                $total++;
                
				if(!empty($tagID_map[$tag['id']])){
					$data_string = $data_string.json_encode(['update' => ["_id"=>$tag['id']]]) . "\n";
					$data_string = $data_string.json_encode(array('doc'=>$tag_formatted))."\n";
				}
				else{
					$data_string = $data_string.json_encode(['index' => ["_id"=>$tag['id']]]) . "\n";
					$data_string = $data_string.json_encode($tag_formatted)."\n";
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

    public function get_formatted_tag($tag){
        return [
            "id"=>$tag['id'],
            "name"=>$tag['name'],
            "visible"=>$tag['visible']
        ];
    }
}