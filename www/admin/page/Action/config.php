<?php
include DOCUROOT.'/admin/page/Action/_base.php';
include DOCUROOT.'/admin/page/Action/baseAction/_config.php';

final class config extends configAction{
	function __construct(){
		parent::__construct();
		if($_GET["act"]=='update' && $this->AppPrefix=='page') {
			$this->assign( 'labelReadOnly', true );
		}

		//对于有实体目录的项目首页，输出可用模板
		if($this->pageinfo['categorise']=='Folder') {
			if(file_exists(DOCUROOT.'/'.$this->pageinfo['url'].'/Config/page_tpl_list.php')){
				$this->assign('tpllist',conf($this->pageinfo['url'].'.page_tpl_list'));
			}
		}
	}
}