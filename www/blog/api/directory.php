<?php
/**
 * 文集编辑接口，用户必须于登录状态，否则返回403
 */
class directory extends Api{

	public $space = true;

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
		$this->userAuthz($bloger);
		
		return [];
    }
}