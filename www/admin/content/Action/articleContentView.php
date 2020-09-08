<?php
include DOCUROOT.'/cms/Action/_baseContent.php';
include DOCUROOT.'/cms/Action/baseAction/_contentView.php';

class articleContentView extends contentView{
	function __construct(){
		parent::__construct();
		$this->assign("links", conf("career.links"));
	}
}