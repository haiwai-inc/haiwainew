<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;
class search_user_login{
    function curl($url, $data=null, $method="GET", $bulk=null){
		$baseUri = "{$url}";
		$ci = curl_init();
		
		$urlinfo = parse_url($url);
		if(isset($urlinfo['scheme'])){
			$scheme = strtolower($urlinfo['scheme']);
			if($scheme=='https'){
				curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
			}
		}
		
		curl_setopt( $ci, CURLOPT_URL, $baseUri );
		curl_setopt( $ci, CURLOPT_TIMEOUT, 200 );
		curl_setopt( $ci, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ci, CURLOPT_FORBID_REUSE, 0 );
		curl_setopt( $ci, CURLOPT_CUSTOMREQUEST, $method );
		if(!empty($data)) {
				// $data_json = json_encode($data);
				curl_setopt( $ci, CURLOPT_POSTFIELDS, $data);
				curl_setopt( $ci, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded') );
		}
		// if($this->showquery) {
		// 	if(empty($bulk))
		// 		debug::d(json_encode($data));
		// 	else
		// 		debug::d($data);
		// }
        $json =  curl_exec($ci);
		return  json_decode($json, true);
    }
    
    public function check_user($code){
        $data = [
            
                "grant_type"=>"authorization_code",
                "code"=>$code,
                "redirect_uri"=>LINE_URL,
                "client_id"=> LINE_CLIENT_ID,
                "client_secret"=> LINE_SECRET,
                "scope"=>"profile openid email"
            
        ];

        $data = http_build_query($data, "", "&");

        $tokenInfo = $this->curl("https://api.line.me/oauth2/v2.1/token",$data, "POST");
        if(!empty($tokenInfo['error'])){
            return 0;
        }

        $data = ["id_token"=>$tokenInfo['id_token'],"client_id"=> LINE_CLIENT_ID];
        debug::d($data);
        $data = http_build_query($data, "", "&");
        $userInfo = $this->curl('https://api.line.me/oauth2/v2.1/verify' ,$data, "POST");
        return $userInfo;
    }

    public function decrypt_token($token){
        $keys = $this->curl("https://appleid.apple.com/auth/keys");
        $decoded = JWT::decode($token, JWK::parseKeySet($keys), array('RS256'));
        return $decoded->email;
    }
}