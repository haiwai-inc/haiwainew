<?php
class search_article_noindex extends Search
{

    protected $dictName = "article";
    protected $index    = "hwarticle_noindex";

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
				  "postID": {
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
				  },
				  "tags":{
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

    public function insert_doc($article)
    {
        $article_formatted = [
			"postID"  => $article['postID'],
			"title"   => $article['title'],
			"msgbody" => $article['msgbody_origin'],
			"pic"     => $article['pic'],
			"buzz"    => $article['buzz'],
			"tags"    => $article['tags'],
        ];
        return $this->add($article_formatted, $article['postID']);
	}
	
	public function get_formatted_doc($article){
		return [
			"postID"  => $article['postID'],
			"title"   => $article['title'],
			"msgbody" => $article['msgbody_origin'],
			"pic"     => $article['pic'],
			"buzz"    => $article['buzz'],
			"tags"    => $article['tags'],
        ];
	}

    public function update_buzz($postID, $userID)
    {
        $article = $this->get($postID);
        if (!empty($article['_source'])) {
            $buzz_list = $article['_source']['buzz'];
            if (!in_array($userID, $buzz_list)) {
                $buzz_list[] = $userID;
            } else {
                $index     = array_search($userID, $buzz_list);
                $buzz_list = array_splice($buzz_list, $index, 1);
            }
            $this->update(['buzz' => $buzz_list], $postID);
            return 1;
        }
        return 0;
	}
	
	public function get_by_postIDs($postIDs, $full_msg = false){
		$posts = $this->get($postIDs);
		$posts = json_decode(json_encode($posts),true);

		if(is_array($postIDs)){
			$posts_body = [];
			foreach($posts['docs'] as $doc){
				if(!empty($doc['found'])){
					$doc_body = $doc['_source'];
					$posts_body[] = $doc_body;
				}
			}

			if(empty($full_msg)){
				$article_index_obj = load("article_indexing");
				$posts_body = $article_index_obj -> format_string($posts_body);
				$posts_body = $article_index_obj -> format_pic($posts_body);
			}
			return $posts_body;
		}
		return $posts;
	}

	public function get_postID_map($postIDs, $full_msg = false){
		$rs = [];
		$posts = $this->get_by_postIDs($postIDs, $full_msg);
		foreach($posts as $post){
			$rs[$post['postID']] = $post;
		}
		return $rs;
	}

	public function get_postInfo($rs,$hashID='postID', $full_msg=false){
	    if(empty($rs)){
	        return $rs;
	    }
	    
        foreach($rs as $v){
            if(empty($v)){
                continue;
            }
            $tmp_rs_id[]=$v[$hashID];
            if(!empty($v['postID'])){
                $tmp_rs_postID[]=$v['postID'];
            }
		}
		if(empty($tmp_rs_id)){
		    return $rs;
		}
		
		//如果登录
		if(!empty($_SESSION['id'])){
		    if(!empty($tmp_rs_postID)){
		        //加入书签
		        $obj_account_bookmark=load("account_bookmark");
		        $rs_account_bookmark=$obj_account_bookmark->getAll("*",['userID'=>$_SESSION['id'],'OR'=>["postID"=>$tmp_rs_postID]]);
		        if(!empty($rs_account_bookmark)){
		            foreach($rs_account_bookmark as $v){
		                $hash_account_bookmark[$v['postID']]=$v;
		            }
		        }
		    }
		}
		
		$hash_posts = $this->get_postID_map($tmp_rs_id, $full_msg);
        foreach($rs as $k=>$v){
			if(empty($hash_posts[$v[$hashID]])){
				unset($rs[$k]);
				continue;
			}
            $rs[$k]["postInfo_{$hashID}"]=$hash_posts[$v[$hashID]];
            
            //是否加入书签
            $rs[$k]["postInfo_{$hashID}"]['is_bookmark']=empty($hash_account_bookmark[$v['postID']])?0:1;
            
            //是否点赞
            $rs[$k]["postInfo_{$hashID}"]['is_buzz']=(!empty($_SESSION['id']) && in_array($_SESSION['id'],$hash_posts[$v[$hashID]]['buzz']))?1:0;
        }
	    return $rs;
	}

    /**
     * 批量添加新文章
     * @param $articles | List of article record
     */
    public function add_new_articles($articles)
    {

        try {
			$count       = 0;
			$total = 0;
			$data_string = "";
			$postID_map = [];
			foreach ($articles as $article) {
				$postIDs[] = $article['postID'];
			}
			$result = $this->get_by_postIDs($postIDs);
			foreach($result as $post){
				$postID_map[$post['postID']] = true;
			}
            foreach ($articles as $article) {
                $article_formatted = [
                    "postID"  => $article['postID'],
                    "title"   => $article['title'],
                    "msgbody" => $article['msgbody_origin'],
                    "pic"     => $article['pic'],
                    "buzz"    => $article['buzz'],
                    "tags"    => $article['tags'],
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
                    $count       = 0;
                }
            }

            if (!empty($count)) {
                $this->addBulk($data_string);
            }
            return $total;
        } catch (Exception $e) {
            debug::d($e);
            return 0;
        }
    }

    public function fetch_and_insert($postID)
    {

        if(empty($postID)){
            return;
        }
        
        if(!is_array($postID)){
            $postID = [$postID];
		}

		$obj_article_indexing=load("article_indexing");
		$rs_article_indexing=$obj_article_indexing->getAll("*",['OR'=>['postID'=>$postID]]);
		
		//补全帖子所有分表信息
		if(!empty($rs_article_indexing)){
		    foreach($rs_article_indexing as $k=>$v){
		        $rs_article_indexing=$obj_article_indexing->get_basic_articleinfo($rs_article_indexing,$k,$v);
		    }
		}
		$rs_article_indexing=$obj_article_indexing->format_string($rs_article_indexing,['msgbody','title'],0);
		
		//导入ES
		$this->add_new_articles($rs_article_indexing);
		return true;
    }
}
















