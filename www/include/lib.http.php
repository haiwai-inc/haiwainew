<?php
/*
 * FileName:class_http.php
 * Created on 2007-11-5
 * By weiqi<weiqi228@gmail.com>
 */

class http {
	static function getWebContent($url, $method = 'get', $header = '') {
		include_once DOCUROOT.'/include/plugins/HttpClient.php';
		$conf = http::getUrl($url);
		
		$obj=new HttpClient($conf['host']);
		if(!empty($header)) $obj->referer = $header;
		
		$query=empty($conf['query'])?false:$conf['query'];
		
		if($method=='post'){
			$obj->post($conf['path'], $query );
		}else{
			$obj->get($conf['path'], $query);
		}
		
		return $obj->getContent();
	}
	
	/**
	 * $header = array(
	 * 		'refer'=>'http://www.google.com/',
	 * 		'UserAgent'=>'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', //Google爬虫
	 *
	 * 		'proxy'=>'127.0.0.1:8888',
	 * 		'proxySocks5'=>true,
	 * 		'proxyauth'=>'user:password',
	 *
	 * 		'cookie'=>array('phpsessionid'=>'CB3FEB3AC72AD61A80BFED91D3FD96CA','www-20480'=>'MHFBNLFDFAAA'),
	 * 		'encoding'=>'GB2312'
	 * );
	 *
	 * 本地代理设置方法：ssh -D 8123 -f -C -q -N root@192.168.0.5
	 */
	static function sendget($url, $header=null){
		$ch = curl_init($url);//使用curl
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		//代理设置
		if(!empty($header['proxy'])) curl_setopt($ch, CURLOPT_PROXY, $header['proxy']);
		if(!empty($header['proxy'])&&isset($header['proxySocks5'])) curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		if(!empty($header['proxyauth'])) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $header['proxyauth']);
	
		//设置浏览器
		$UserAgent = isset($header['UserAgent'])?$header['UserAgent']:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36";
		curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2 );
		
		//设置Refer
		if(!empty($header['refer']))
			curl_setopt($ch, CURLOPT_REFERER, $header['refer']);
	
			//传递cookie
			if(!empty($header['cookie'])){
				$cookie=array();
				foreach($header['cookie'] as $key=>$val) $cookie[]="{$key}={$val}";
				curl_setopt($ch, CURLOPT_COOKIE, implode("; ", $cookie));
			}
	
			//编码
			$encoding = empty($header['encoding'])?'UTF-8':$header['encoding'];
			curl_setopt($ch, CURLOPT_ENCODING, $encoding);
	
			$result = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
	
			if(empty($result)) return false;
	
			if(empty($info['redirect_url'])){
				//返回目标页面内容
				return $result;
			}else{
				//使用重定向地址再次请求
				return http::sendget($info['redirect_url'], $header);
			}
	}
	
	static function sendpost($data, $url, $cookievalue=null){
	    //curl 提交的数据
	    $postfields = array();
	    foreach($data as $key=>$val) $postfields[$key] = rawurlencode($val);
	    
	    $ch = curl_init();
	    
	    //传递cookie
	    if(!empty($cookievalue)){
	        $cookie=array();
	        foreach($cookievalue as $key=>$val) $cookie[]="{$key}={$val}";
	        curl_setopt($ch, CURLOPT_COOKIE, implode("; ", $cookie));
	    }
	    
	    curl_setopt($ch, CURLOPT_URL, $url );
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ( $ch, CURLOPT_ENCODING, 'UTF-8');
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2 );
	    $result = curl_exec($ch);
	    $info = curl_getinfo($ch);
	    
	    curl_close($ch);
	    
	    if(empty($info['redirect_url'])){
	        //返回目标页面内容
	        return $result;
	    }else{
	        //返回重定向地址
	        return $info['redirect_url'];
	    }
	}

	/**
	 * 取得客户端ip地址
	 * @return string $ip
	 */
	static function getIP() {
		if( isset($_SERVER['HTTP_CLIENT_IP']) )
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) )
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		elseif ( isset($_SERVER['HTTP_X_FORWARDED']) )
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		
		elseif ( isset($_SERVER['HTTP_FORWARDED_FOR']) )
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		
		elseif ( isset($_SERVER['HTTP_FORWARDED']) )
			$ip = $_SERVER['HTTP_FORWARDED'];
		
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		
		//针对特殊情况进行处理 [HTTP_X_FORWARDED_FOR] => 67.180.137.112, 104.198.222.228
		if(strstr($ip, ',')){
			$iparr = explode(',', $ip);
			$ip = $iparr[0];
		}
		
		return $ip;
	}
	
	/**
	 * 返回城市信息数据
	 * 
	 * Array
		(
		    [continent_code] => NA
		    [country_code] => US
		    [country_code3] => USA
		    [country_name] => United States
		    [region] => CA
		    [city] => Fremont
		    [postal_code] => 94539
		    [latitude] => 37.51549911499
		    [longitude] => -121.8962020874
		    [dma_code] => 807
		    [area_code] => 510
		)

	 * @param bool $debug
	 */
	static function getGeoInfo( $ip='',$debug=false ){
		$info=array();
		
		if(!function_exists("geoip_record_by_name")){
			echo "<h2 style='color:red;'>Not function `geoip_record_by_name`!<h2><br/>";
		}else{
			$ip=empty($ip)?http::getIP():$ip;
			$info = @geoip_record_by_name($ip);
			if($debug) debug::d($info);	
		}
		
		return $info;
	}

	/**
	 * 处理Url
	 *
	 * @param string $url
	 * @return array 合法的URL数组
	 */
	static function getUrl($url){
		$urls = parse_url( $url );
		if( empty( $urls['host'] ) ){
			$urls['host'] = $_SERVER["SERVER_NAME"];
		}
		if( empty( $urls['scheme'] ) ){
			$urls['scheme'] = 'http';
		}
		if( empty( $urls['port'] )){
			$urls['port'] = $_SERVER['SERVER_PORT'];
		}
		if( empty( $urls['path'] )){
			$urls['path'] = $_SERVER['PHP_SELF'];
		}
		return $urls;
	}
	
	static function readUrl(){
		return "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	// escape for URL 
	static function escapeForURL ($input) {
	   return str_replace("&", "&amp;", $input);
	}
	
	static function meta($charset='utf-8'){
		echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />';
	}
	
	/**
	 * 根据用户IP,利用dig去查询相应的PTR记录
	 * @param string $ip
	 * @param int $num
	 * @return string $domain
	 */
	static function getPTR($ip,$num=3){
		$output = shell_exec("/usr/bin/dig -x {$ip}");
		$arr=explode("\n",$output);//分行存入数组
		
		$ptrstr="";
		$pos=false;//读取ptr记录的开关
		
		foreach($arr as $line){
			if($pos){
				$ptrstr=$line;
				break;
			}
			//检查到answer，启动读取开关
			if(strstr($line,';; ANSWER SECTION:')) $pos=true;
		}
		
		//命令行返回的ptr记录值
		//$ptrstr="82.60.208.203.in-addr.arpa. 84927 IN	PTR	crawl-203-208-60-82.googlebot.com."
		if(empty($ptrstr)) return false;
		
		//域名字符串
		//$ptrStrArr[2]="crawl-203-208-60-82.googlebot.com."
		$ptrStrArr=explode("\t", $ptrstr);
		if(empty($ptrStrArr[2]))  return false;
		
		//小于3的域名忽略不计
		$ptrDomainArr=explode('.', $ptrStrArr[3]);
		$n=count($ptrDomainArr);		
		if($n<3) return false;
		
		//返回类似 googlebot.com
		$count=$n-$num;
		$i=0;
		foreach($ptrDomainArr as $k=>$v){
			if($i<$count||empty($v)) unset($ptrDomainArr[$k]);
			$i++;
		}
		
		$domain=implode(".",$ptrDomainArr);
		return $domain;
	}
	
	static function getPathInfoExt($link=null){
		if(!empty($_GET['NginxHttpExt'])) return $_GET['NginxHttpExt'];
		if(!empty($_GET['NginxhttpsExt'])) return $_GET['NginxhttpsExt'];
		if(empty($link)) $link=$_SERVER['REQUEST_URI'];
		
		$info=pathinfo($link);
		if(empty($info['extension'])) return ;
		$ext=$info['extension'];
		$len=strpos($ext,'?')?strpos($ext,'?'):strlen($ext);
		$ext=substr($ext,0,$len);
		$ext=strtolower($ext);
		
		return $ext;
	}
	
	static function isIOS(){
		$IOS=array('iPhone','iPad','iPod');
		
		foreach($IOS as $device){
			if(strstr($_SERVER['HTTP_USER_AGENT'],$device)) return true;
		}
		
		return false;
	} 
	
	static function isMobile() {
		static $isMobile;
		if(!empty($_COOKIE['noWap'])) return false;
		
		if(!isset($isMobile)){
			$obj = new mobile();
			$isMobile = $obj->isMobile($_SERVER['HTTP_USER_AGENT']);
		}
		return $isMobile;
	}
	
	static function isTablet() {
		static $isTablet;
		if(!empty($_COOKIE['noWap'])) return false;
		
		if(!isset($isTablet)){
			$obj = new mobile();
			$isTablet = $obj->isTablet($_SERVER['HTTP_USER_AGENT']);
		}
		
		return $isTablet;
	}
	
	static function isWap(){
		if(!empty($_COOKIE['noWap'])) return false; //强制不使用移动版
		if(self::isTablet()) return false; //tablet只使用网页版
		
		if(substr(strtolower($_SERVER['HTTP_HOST']),0,2)=='m.') return true;
		if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone')) return true;
		if(!empty($_GET['mobile'])) return true;
		
		
		if(!empty($_SERVER['HTTP_REFERER'])){
			$conf = parse_url($_SERVER['HTTP_REFERER']);
			if(substr(strtolower($conf['host']),0,2)=='m.') return true;
		}
		
		return false;
	} 
}

?>