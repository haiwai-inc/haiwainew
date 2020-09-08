<?php
return array(
	'id' => 'sys',
    'name' => '系统内置',
    'description' => '系统内置配置信息',
    'status' => 'Y',
    'define' => array(
        '-1'=>array(
		    'id' => -1,
		    'name' => "真实姓名",
		    'notes' => '填写自己的真实姓名，用户核对用户的真实信息',
		    'type' => 'varchar',
		    'defaultValue' => '',
		    /*'categorise' => 'sys',
		    'status' => 'Y',
		    'privacy' => 'Y',
		    'typename' => '字符',
		    'typelist' => '',
		    'checklist' => '',*/
		),
		'-2'=>array(
		    'id' => -2,
		    'name' => "签名",
		    'notes' => '',
		    'type' => 'text',
		    'defaultValue' => '',
		    'categorise' => 'sys',
		),
		'-3'=>array(
		    'id' => -3,
		    'name' => "性别",
		    'notes' => '',
		    'type' => 'select',
		    'defaultValue' => '',
		),
		'-4'=>array(
		    'id' => -4,
		    'name' => "来自地区",
		    'notes' => '',
		    'type' => 'multiSelect',
		    'defaultValue' => '',
		),
		'-5'=>array(
		    'id' => -5,
		    'name' => "居住地区邮编",
		    'notes' => '',
		    'type' => 'char',
		    'defaultValue' => '',
		),
	),
);