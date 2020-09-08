<?php
include DOCUROOT.'/admin/page/Action/_base.php';
include DOCUROOT.'/admin/page/Action/baseAction/_domain.php';

final class domain extends domainAction{
	function __construct(){
		parent::__construct();
		if($_GET["act"]=='index') $this->assign("incmenu",conf('admin.page.incmenu.domain'));
	}
}