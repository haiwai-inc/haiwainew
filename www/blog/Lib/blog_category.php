<?php
class blog_category extends Model{
	protected $tableName="category";
	protected $dbinfo=array("config"=>"blog","type"=>"MySQL");

	function category(){
		
	}

	/**
	 * Search by category name
	 * @param string $keyword
	 * @return array $categories
	 */
	public function search_by_name($keyword){
		$categories = $this->getList("*", ["visible"=>1, "name,like"=>"%".$keyword."%"]);
		if(empty($categories)) return $categories;
		// return $this->fetch_userinfo($categories);
		$user_obj = load("account_user");
		$blogger_obj = load("blog_blogger");
		$categories = $user_obj->get_basic_userinfo($categories, "userID");
		$categories = $blogger_obj->get_basic_bloggerinfo($categories, "bloggerID");
		return $categories;
	}


	/**
	 * Sync category order of haiwai based on their wxc counter part
	 * @param array $usernames
	 */
	public function sync_wxc_category($usernames){
		$this->usernames = empty($usernames) ? [] : $usernames;
        $this->wxcBloggerObj = load("blog_legacy_blogger_haiwai");
        $this->wxcCategoryObj = load("blog_legacy_blogcat_members");
        $this->hwUserObj = load("account_user_auth");
        $this->hwBloggerObj = load("blog_blogger");
		$this->reorderCategory();
	}

	public function reorderCategory(){  
        $wxcBloggers = [];
        if(!empty($this->usernames)) {
            $wxcBloggers =$this->wxcBloggerObj->getAll("*", ["OR"=>["username" => $this->usernames]]);
        }
        else {
            $wxcBloggers = $this->wxcBloggerObj->getAll("*");
        }


        foreach($wxcBloggers as $blogger){
            $this->reorderBloggerCategory($blogger);
        }
    }

    private function reorderBloggerCategory($blogger){
        // Get all category of the blogger on WXC
        $wxcCategories = $this->wxcCategoryObj->getAll("*", ["userid" => $blogger['userid'], 'order'=>["sortorder"=>"ASC"]]);

        // Get information about the user in haiwai

        $haiwaiUser= $this->hwUserObj->getOne("*", ["login_data" => $blogger["username"]]);
        if(empty($haiwaiUser)) return;
	$hwBloggers = $this->hwBloggerObj->getOne("*",['userID'=>$haiwaiUser['userID']]);

        // Get all category of the blogger on haiwai
        $hwCategories = $this->getAll("*", ["bloggerID" => $hwBloggers["id"], 'name,!='=>'我的文章','order'=>['sort'=>'ASC']]);

        // Reorder haiwai categories according to WXC
        $this->reorderHaiwai($wxcCategories, $hwCategories);
    }

    private function reorderHaiwai($wxcCategories, $hwCategories){
        $catNameIDMap = [];
        foreach($hwCategories as $category){
            $catNameIDMap[$category['name']] = $category['id'];
        }

        // Upate sort for categories in wxc
        $updates = [];
        $visited = [];
        $i = 0;
        foreach($wxcCategories as $category){
            if(empty($catNameIDMap[$category['category']]) || !empty($visited[$category['category']])) continue;
            $this->Update(["sort" => $hwCategories[$i]['sort']], ["id"=>$catNameIDMap[$category['category']]]);
            $updates[] = ["category"=>$category['category'], "id"=>$catNameIDMap[$category['category']], "sort" => $hwCategories[$i]['id']];
            $visited[$category['category']] = true;
            $i ++;
        }

        // Update sort for categories not in wxc
        foreach($hwCategories as $category){
            if(!empty($visited[$category['name']])) continue;
            $this->Update(["sort" => $hwCategories[$i]['sort']], ["id"=>$category['id']]);
            $updates[] = ["category"=>$category['name'], "sort" => $hwCategories[$i]['id']];
            $visited[$category['category']] = true;
            $i ++;
        }
    }
}


