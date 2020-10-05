<?php
class blog_search extends Search{

    protected $dictName = "blog";
    
    function __construct()
	{   $blogSet='
		
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
				  "postid": {
					"type": "integer"
				  },
				  "content": {
					"analyzer": "ik_smart",
					"type": "text"
				  },
				  "article_blogname": {
					"index": false,
					"type": "text"
				  },
				  "title": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
				  },
				  "visible": {
					"type": "integer"
				  },
				  "doc": {
					"properties": {
					  "visible": {
						"type": "long"
					  }
					}
				  },
				  "dateline": {
					"type": "date",
					"format": "yyyy-MM-dd HH:mm:ss"
				  },
				  "article_userid": {
					"type": "integer"
				  },
				  "article_blogid": {
					"type": "integer"
				  },
				  "article_username": {
					"index": false,
					"type": "text"
				  }
				}
			  }
        }';
    }
}
?>