<?php
require DOCUROOT.'/admin/site/Action/_base.php';
require DOCUROOT.'/admin/page/Action/baseAction/_config.php';

final class config extends configAction{
	function __construct(){
		parent::__construct();
		if($_GET["act"]=='update') {
			$this->assign( 'tpllist', include (DOCUROOT.'/'.AppName.'/Config/siteList.php') );
			$this->assign( 'typelist', conf('admin.site.siteType') );
		}
	}
	
}