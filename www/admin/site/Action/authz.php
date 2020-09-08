<?php
require DOCUROOT.'/admin/site/Action/_base.php';
require DOCUROOT.'/admin/page/Action/baseAction/_authz.php';

final class authz extends authzAction{
	function __construct(){
		parent::__construct();
		if($_GET["act"]=='index') $this->assign("incmenu",conf('admin.page.incmenu.authz'));
	}
}