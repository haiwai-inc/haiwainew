<?php
include_once DOCUROOT."/admin/page/Lib/page_page.php";
class site_page extends page_page{
	protected $tableName = 'page';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
}