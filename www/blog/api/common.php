<?php
/**
 * 博客内容调用接口
 */
class common extends Api{

	private $indexObj;
	private $categoryObj;
	private $postObj;
	
	function __construct(){
		parent::__construct();

		$this->indexObj = load('blog_indexing');
		$this->categoryObj= load('blog_category');
		$this->postObj=load('blog_post');
    }
    
	/**
	 *返回博客文章列表
	 *
	 * @param integer $bloger| bloger user id.
	 *
	 * @response obj
	 * @response {name,...}
	 *
	 * @response Error Response
	 * @response 0
	 */
	function getArticleList($bloger){
		
		
		return [];
    }
}