<?php
/**
 * 设置或取消繁体转换
 */
include '../_inc.php';

$url = empty($_SERVER["HTTP_REFERER"])?"/":$_SERVER["HTTP_REFERER"];
$_GET['translate'] = isset($_GET['translate'])?intval($_GET['translate']):0;

function getRedirectURL($subdomain,$url){
	//生产环境
	$domainName = $subdomain;
	
	//测试环境
	if(strstr($_SERVER["HTTP_HOST"],"beta.")){
		if($domainName=='www') $domainName = 'beta';
		if($domainName=='zh') $domainName = 'zhbeta';
	}
	
	if(empty($_SERVER["HTTP_REFERER"])){
		$url = "http://{$domainName}.wenxuecity.com/";
	}else{
		$urlinfo = parse_url($url);
		
		$domian_tag=str_replace(".wenxuecity.com","",$urlinfo['host']);
		
		if(in_array($domian_tag,array('bbs','finance','immigration','edu','home','health','cooking','style','travel','video','blog','groups')))
		{
		    if(strstr($urlinfo['path'],"/bbs"))
		    {
		        $url = "{$urlinfo['scheme']}://{$domainName}.wenxuecity.com{$urlinfo['path']}";
		    }
		    else
		    {
		        $url = "{$urlinfo['scheme']}://{$domainName}.wenxuecity.com/{$domian_tag}{$urlinfo['path']}";
		    }
		}
		else
		{
		    $url = "{$urlinfo['scheme']}://{$domainName}.wenxuecity.com{$urlinfo['path']}";
		}
		
		if(!empty($urlinfo['query'])) $url .= "?".$urlinfo['query'];
	}
	
	return $url;
}

$url = empty($_GET['translate'])?getRedirectURL("www",$url):getRedirectURL("zh",$url);
go($url);

















