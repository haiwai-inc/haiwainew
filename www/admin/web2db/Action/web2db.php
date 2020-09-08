<?php
class web2db extends Action{

	var $obj;
	
	private $basehref;
	
	function __construct(){
		parent::__construct();
		if(!$this->isAdmin()) {
		    if(!func_checkEditor())
		      go("/");
		}
		$this->obj = load('web2db_config');
	}

	function ACT_index(){
		$rs=$this->obj->getAll(array('id','title'),array('id,!='=>0,'order'=>array('id'=>'ASC')));
		$this->assign('rs',$rs);
		
		//微信抓取
		$obj_fetchinfo=load("web2db_fetchinfo");
		$rs_fetchinfo=$obj_fetchinfo->getAll("SELECT sourcename FROM fetchinfo GROUP BY `sourcename`");
		$this->assign('rs_fetchinfo',$rs_fetchinfo);
	}

	function ACT_add(){
	}

	function ACT_update(){
		$this->tpl='web2db/add.html';
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->assign('rs',$rs);
	}

	function ACT_del(){
		if(!empty($_GET['id']))$this->obj->Remove(array('id'=>$_GET['id']));
		go('./index.php');
	}

	function ACT_post(){
		if(empty($_POST['eid'])){
			$id=$this->obj->Insert($_POST);
		}else{
			$id=intval($_POST['eid']);
			$this->obj->Update($_POST,array('id'=>$id));
		}

		go($_POST['reurl']);
	}

	function ACT_list(){
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->assign('rs',$rs);
		
		$list = $this->getHtmlList($rs);
		$this->assign('list',$list);
		
		//输出分页
		if(!empty($rs['pattern-url'])){
			$currentpage = empty($_GET['page'])?1:$_GET['page'];
			$this->assign('currentpage',$currentpage);
			$this->assign('page',array(1,2,3,4,5,6,7,8,9,10));
		}
	}
	
	//微信列表页
	function ACT_wechatlist()
	{
		$obj_fetchinfo=load("web2db_fetchinfo");
		$sourcename=empty($_GET['sourcename'])?"":$_GET['sourcename'];
		
		$rs_fetchinfo=$obj_fetchinfo->getList('*',array('sourcename'=>$sourcename,'order'=>array('datatime'=>'DESC')));
		
		foreach($rs_fetchinfo as $k=>$v)
			$rs_fetchinfo[$k]['url']=rawurlencode($v['url']);
			
		$this->assign('rs_fetchinfo',$rs_fetchinfo);
		$this->assign('sourcename',$sourcename);
	}
	
	//微信抓取页
	function ACT_wechatcontent(){
		$this->tpl = DOCUROOT."/admin/web2db/Tpl/web2db/content.html";
		$id=empty($_GET['id'])?"":$_GET['id'];
		$url = empty($_GET['url'])?'':rawurldecode($_GET['url']);
		$url=str_replace('&3rd=MzA3MDU4NTYzMw==','',$url);
		
		$obj_fetchinfo=load("web2db_fetchinfo");
		$rs_fetchinfo=$obj_fetchinfo->getOne("*",array('uuid'=>$id));
		
		//模拟用户
		$this->assign('poster',array('微信'));
	
		//目标频道
		$this->assign('channels',$this->contentChannel());
		$this->assign('staticBbs',$this->staticBbs());
		$this->assign('dynamicBbs',$this->dynamicBbs());
	
		$content = $this->getHtmlContent( $url, 'id="js_content">(.*)<script type="text/javascript">' );
		$content=str_replace('data-src','src',$content);
		//$content = $this->contentFilter( $content, '<!--bodybegin-->(.*)<!--bodyend-->' );
	
		$this->assign('url',$url);
		$this->assign( 'title', $rs_fetchinfo['title'] );
		$this->assign('content', $content );
	}
	
	private function getHtmlList($rs){
		$url = $this->getPageUrl($rs['url'],$rs['pattern-url']);
		$this->assign('url',$url);
		
		//抓取的内容
		$content = $this->getHtmlContent( $url, $rs['pattern-list']);
		$content = strings::tidy($content);
		
		if(empty($content)) return;
		
		//遍历节点得到所有有用链接
		$html = htmlDomNode::str_get_html( $content );
		
		//判断是否设置base
		if(!empty($rs['pattern-baseurl'])) $this->basehref = $rs['pattern-baseurl'];
		
		//搜索全部新闻来源
		$tag=explode('(.*)',trim($rs['pattern-source']));
		
		//搜索新闻读数
		$result = array();
		$pattern_count=explode('(.*)',trim($rs['pattern-count']));
		
		foreach($html->find($rs['pattern-taglist']) as $k=>$e) 
		{
			$tmp = $this->getHtmlLink($e, $rs['url'], $rs['id'],$tag,$pattern_count);
			if(empty($tmp)){
			    continue;
			}
			
			if(!empty($rs['pattern-listmatch'])){
				if(!strstr($tmp['link'],$rs['pattern-listmatch'])){
				    continue; //列表链接必须满足匹配规则
				}
			}
			
			$result[]=$tmp;
		}
		
		return $result;
	}
	
	private function getPageUrl( $url, $pattern ){
		if(empty($_GET['page'])) return $url;
		if(empty($pattern)) return $url;
		if( !strstr($pattern,'(.*)') ) return $url;
		
		$conf = explode('(.*)', $pattern);
		$n = strings::findMe($url, $conf[0], $conf[1]);
		
		$search = $conf[0] .$n. $conf[1];
		$replace = $conf[0] .$_GET['page']. $conf[1];
		
		$newURL = str_replace($search, $replace, $url);
		return $newURL;
	}
	
	private function getHtmlLink($e, $url, $id, $tag, $pattern_count){
	    
		static $urlarr;
		if(empty($urlarr))	$urlarr = http::getUrl($url);
		
		//得到链接的起始地址
		static $baseurl;
		if(empty($baseurl))
		{
			if(empty($this->basehref))
			{
				$baseurl = "http://".$urlarr['host'] ;
				if(!empty($urlarr['path'])) $baseurl .= dirname($urlarr['path']).'/';
			}
			else
				$baseurl = $this->basehref;
		}
		
		$title = $e->innertext;
		$title = trim(strip_tags($title));
		if(empty($title)) return;
		
		//标题
		$tmp=$e->find('a');
		if(empty($tmp)) return;
		
		//被可亲特殊字符处理
		$check_comment=strstr($title,'条评论');
		if($check_comment && $id==4)
		{
			$link=$tmp[1]->href;
			$title=$tmp[1]->innertext;
		}
		else
		{
			$link=$tmp[0]->href;
			$title=$tmp[0]->innertext;
		}
		
		if(empty($link))
			return;
			
		$title = trim(strip_tags($title));
		
		//来源
		$source='';
		if(!empty($tag[0]))
			$source=strip_tags(trim(strings::findme($e, $tag[0], $tag[1]))); 
		
		//读数
		$count='';
		if(!empty($pattern_count[0])){
		    $count='('.strip_tags(trim(strings::findme($e, $pattern_count[0], $pattern_count[1]))).")"; 
		}
		
		$tmplink = strtolower($link);
		if( substr( $tmplink, 0, 7 ) != 'http://' && substr( $tmplink, 0, 8 ) != 'https://') {
			if(substr($link,0,1)=='/'){
				$link = "https://".$urlarr['host'].$link;
			}else{
				$link = $baseurl . $link;
			}
		}
		
		$link = './index.php?act=content&id='.$id.'&url='.rawurlencode($link).'&source='.$source; 
		return array('title'=>$title, 'link'=>$link, 'source'=>$source, 'count'=>$count);
	}
	
	function ACT_content(){
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		
		//模拟用户
		$this->assign('poster',$this->contentPoster($rs['poster']));
		
		//目标频道
		$this->assign('channels',$this->contentChannel());
		$this->assign('staticBbs',$this->staticBbs());
		$this->assign('dynamicBbs',$this->dynamicBbs());
		
		$url = empty($_GET['url'])?'':rawurldecode($_GET['url']);
		$this->assign('url',$url);

		$title = $this->getHtmlContent( $url, $rs['pattern-title']);
		$content = $this->getHtmlContent( $url, $rs['pattern-content'] );
		$content = $this->contentFilter( $content, $rs['pattern-filter'] );
		
		$this->assign( 'title', $title );
		$this->assign('content', $content );
	}
	
	private function contentPoster($posterStr){
		$poster = array();
		if(!empty($posterStr)){
			$posterStr=str_replace("\n", ',', $posterStr);
			$posterStr=str_replace('，', ',', $posterStr);
			$posterStr=str_replace('；', ',', $posterStr);
			$posterStr=str_replace(' ', ',', $posterStr);
			$posterStr=str_replace(';', ',', $posterStr);
			$posterStr=str_replace(' ', ',', $posterStr);
			$poster = explode(',', $posterStr);
			
			foreach($poster as $key=>$val){
				$val=trim($val);
				if(empty($val)) unset($poster[$key]);
			}
		}
		
		return $poster;
	}
	
	//新闻 娱乐 生活
	private function contentChannel(){
		return conf('admin.web2db.channels');
	}
	
	//静态论坛
	private function staticBbs(){
		return conf('bbs.allbbs');
	}
	
	//动态论坛
	private function dynamicBbs(){
		$obj_channels=load('bbs_channels');
		$rs=$obj_channels->getAll("*",array('isdynamic'=>'yes','order'=>array('fid'=>'DESC')));
		
		return $rs;
	}
	
	
	//对抓取后的内容进行过滤
	private function contentFilter($content,$conf){
		if( empty($conf) ) return $content;
		
		$conf = explode("\n", $conf);
		foreach($conf as $val) $content = str_replace($val, '', $content);
		
		return $content;
	}
	
	//写入贴子
	function ACT_save(){
		$obj_add=load("bbs_add");
		$obj_add->web2db();
	}
	
	
	private function getHtmlContent( $url, $conf ){
		if(empty($url)) return;
		if( !strstr($conf,'(.*)') ) return;
		
		//抓取原始网页
		$content = $this->fetchHtmlContent( $url );
		
		//获得原页面编码
		$charset = $this->getHtmlCharset($content);
		if(!strstr($charset,'utf')) {
			$charset = 'gbk';
			$content = mb_convert_encoding($content, 'utf8',$charset);
		}
		
		//获得基本地址信息
		if(strstr($content, '<base href="')){
			$this->basehref=strings::findMe($content, '<base href="', '"');
		}
		
		//抓取目标区域
		$conf = explode("(.*)", $conf);
		$content = strings::findMe($content, $conf[0], $conf[1]);
		
		//整理html结构
		$content = strings::tidy($content);
		
		return $content;
	}
	
	private function getHtmlCharset($html){
		$list = strings::findMeAll($html, '<meta', '>');
		
		foreach($list as $val){
			if(!strstr($val,'charset'))continue;
			
			$val = str_replace("'", '"', $val);
			$charset = strings::findMe($val, 'charset=', '"');
			$charset = trim($charset);
			$charset = strtolower($charset);
			
			return $charset;
		}
		
		return 'gbk';
	}
	
	private function fetchHtmlContent($url,$cachetime=300){
		$id= 'fetch_' . md5($url);
		
		$config = site_memcache::getConf('cache');
		$memObj = func_initMemcached( $config[0]['host'], $config[0]['port']);
		
		$html = $memObj->get($id);
		if(empty($html)) {
		    //$html = http::getWebContent( $url, 'GET', $url );
			$html = $this->getUrlContent($url);
			$memObj->set($id, $html, false, $cachetime); //对抓取的内容设置5分钟的缓存
		}
		
		return $html;
	}
	
	
	private function getUrlContent($url) {
	    $parts = parse_url($url);
	    $host = $parts['host'];
	    $ch = curl_init();
	    $header = array('GET /1575051 HTTP/1.1',
	        "Host: {$host}",
	        'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	        'Accept-Language:en-US,en;q=0.8',
	        'Cache-Control:max-age=0',
	        'Connection:keep-alive',
	        'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
	    );
	    
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	    
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	
}