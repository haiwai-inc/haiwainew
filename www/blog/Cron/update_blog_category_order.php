<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class CategorySorter{

    public function __construct($usernames = [])
    {
        $this->usernames = empty($usernames) ? [] : $usernames;
        $this->wxcBloggerObj = load("blog_legacy_blogger_haiwai");
        $this->wxcCategoryObj = load("blog_legacy_blogcat_members");
        $this->hwUserObj = load("account_user_auth");
        $this->hwCategoryObj = load("blog_category");
        $this->hwBloggerObj = load("blog_blogger");
    }

    public function reorderCategory(){  
        $wxcBloggers = [];
        if(!empty($this->usernames)) {
            $wxcBloggers =$this->wxcBloggerObj->getAll("*", ["OR"=>["username" => $this->usernames]]);
        }
        else {
            $wxcBloggers = $this->wxcBloggerObj->getAll("*",['id<,'=>602]);
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
        $haiwaiBlogger = $this->hwBloggerObj -> getOne("*", ["userID" => $haiwaiUser['id']]);

        // Get all category of the blogger on haiwai
        $hwCategories = $this->hwCategoryObj->getAll("*", ["bloggerID" => $haiwaiBlogger["id"], 'name,<>'=>"我的文章",'order'=>["sort"=>"ASC"]]);

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
            $this->hwCategoryObj->Update(["sort" => $hwCategories[$i]['id']], ["id"=>$catNameIDMap[$category['category']]]);
            $updates[] = ["category"=>$category['category'], "id"=>$catNameIDMap[$category['category']], "sort" => $hwCategories[$i]['id']];
            $visited[$category['category']] = true;
            $i ++;
        }

        // Update sort for categories not in wxc
        foreach($hwCategories as $category){
            if(!empty($visited[$category['name']])) continue;
            $this->hwCategoryObj->Update(["sort" => $hwCategories[$i]['id']], ["id"=>$category['id']]);
            $updates[] = ["category"=>$category['name'], "sort" => $hwCategories[$i]['id']];
            $visited[$category['category']] = true;
            $i ++;
        }
    }
}

$sorter = new CategorySorter();
$sorter-> reorderCategory();

