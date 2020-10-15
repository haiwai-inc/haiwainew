<?php
class search_blog extends Search{

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
				  "blogid": {
					"type": "integer"
				  },
				  "description": {
					"analyzer": "ik_smart",
					"type": "text"
				  },
				  "name": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
				  },
				  "title": {
					"analyzer": "ik_max_word",
					"boost": 2,
					"type": "text"
                  },
                  "tags":{
                      
                  },    
				  "visible": {
					"type": "integer"
				  },
				}
			  }
        }';
    }
}
?>