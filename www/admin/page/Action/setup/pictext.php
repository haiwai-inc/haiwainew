<?php
include DOCUROOT.'/admin/page/Action/_base.php';
include DOCUROOT.'/admin/page/Action/baseAction/_pictext.php';

class pictext extends pictextAction{
	protected $editorCate='pagePic';//用于编辑器内文件上传时，记录上传文件所用的数据库标识
	
	function __construct() {
		parent :: __construct();
		$_GET['itemid']=empty($_GET['itemid'])?0:$_GET['itemid'];
		if( $_GET['act']=='index') $this->assign("incmenu",conf('admin.page.incmenu.setup_pictext'));
	}
}