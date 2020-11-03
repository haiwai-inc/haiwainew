<?php
class user extends Api
{
    /**
     * Search by name
     *
     * @param string $keyword
     * @param string $type
     * @param boolean $with_article
     * @response 搜索结果
     *
     * @response 用户未登录，返回错误信息
     */
    public function name($keyword, $type = "all", $with_article = false)
    {
        $rs       = [];
        if($type == "all" || $type == "user"){
            $user_obj = load("account_user");
            $users = $user_obj->search_by_name($keyword);
            $rs['users'] = $users;
        }

        if($type == "all" || $type == "blogger"){
            $blogger_obj = load("blog_blogger");
            $bloggers = $blogger_obj->search_by_name($keyword, $with_article);
            $rs['bloggers'] = $bloggers;
        }

        return $rs;
    }
}
