<?php
/**
 * 博客编辑面板，用户必须于登录状态，否则返回403
 * @author weiqiwang
 */
class dashboard extends Api{

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

	/**
	 * 返回单个博客文章
	 * @param integer $blogid| Blog article ID.
	 */
	function getArticle($blogid){
		
		return [];
	}
	
	/**
	 * 新建博客文章
	 * @param integer $bloger| bloger user ID.
	 * @param object $data| data array.
	 */
	function add($bloger,$data){
		$this->userAuthz($bloger);
		
		return [];
	}

	/**
	 * 新建博客自动保存
	 * @param integer $bloger| bloger user ID.
	 * @param object $data| data array.
	 */
	function autosave($bloger,$data){
		$this->userAuthz($bloger);
		
		return [];
	}

    /**
	 * 修改博客文章
	 * @param integer $blogid| Blog article ID.
	 * @param object $data| data array.
	 */
	function update($blogid,$article){
		$this->userAuthz(0);
		$this->common->updateAll(['activity_report'=>$activity_report],['student_id'=>$studentID]);
		if($activity_report==0){
			$obj=load('student_activity');
			$obj->remove(["student_id"=>$studentID]);
		}
		return  [];
	}

	
	/**
	 * 删除博客文章
	 * @param integer $blogid| Blog article ID.
	 */
	function delete($blogid){
		$this->userAuthz(0);
		
		return $common;
	}

}