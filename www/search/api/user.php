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


    /**
     * Login By Facebook
     *
     * @param string $token
     * @response 搜索结果
     *
     * @response 用户未登录，返回错误信息
     */
    public function facebookLogin($token){
        $fb = new Facebook\Facebook([
            'app_id' => FACEBOOK_CLIENT_ID,
            'app_secret' => 'ac4725b1caad7a7335dbba706667ecf5',
            'default_graph_version' => 'v2.10',
            ]);
          
          try {
            // Returns a `Facebook\Response` object
            $response = $fb->get('/me?fields=id,name,email', "$token");
          } catch(Exception $e) {
            echo 'Facebook SDK an error: ' . $e->getMessage();
            exit;
          }
        //   } catch(Facebook\Exception\SDKException $e) {
        //     echo 'Facebook SDK returned an error: ' . $e->getMessage();
        //     exit;
        //   }
          
          $user = $response->getGraphUser();
          debug::d($user->getEmail());
          debug::d($user);
          echo 'Name: ' . $user['name'];
          return $user;
    }

    /**
     * Login By Line
     *
     * @param string $token
     * @response 搜索结果
     *
     * @response 用户未登录，返回错误信息
     */
    public function lineLogin($token){
        $user_obj = load("search_user_login");
        return $user_obj->check_user($token);
    }

     /**
     * Login By Apple
     *
     * @param string $token
     * @response 搜索结果
     *
     * @response 用户未登录，返回错误信息
     */
    public function appleLogin($token){
        $user_obj = load("search_user_login");
        return $user_obj->decrypt_token($token);
    }

    /**
     * @param string $avatar
     * @post avatar
     */
    public function update_avatar($avatar){
        // debug::d($avatar);
        $pic_obj = load("article_pic");
        $url = $pic_obj -> save_picture($avatar);

        //update user profile
         

        return $url;
    }

    /**
     * @param string $background
     * @post background
     */
    public function update_blog_background($background){
        // debug::d($avatar);
        $pic_obj = load("article_pic");
        $url = $pic_obj -> save_picture($background);

        //update blog background
         

        return $url;
    }

    /**
     * @param string $type
     * @param string $file
     * @post file
     */
    public function upload_file($type, $file){
        if($type == 'pic'){
            $pic_obj = load("article_pic");
            $url = $pic_obj -> save_picture($file);
        }
        elseif($type == 'media'){
            $pic_obj = load("article_pic");
            $url = $pic_obj -> save_media($file);
        }
        return $url;
    }
}
