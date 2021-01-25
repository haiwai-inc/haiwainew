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
            'app_id' => '793976421331830',
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
}
