<?php
class user extends Api
{

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
}
