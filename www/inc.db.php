<?php
return array(
    'prefix'=>'wxc',//数据库前缀
    'mysqlMasterSlave'=>false,//是否启用mysql主从分离功能
    
    'database'=>array(
        //默认的主数据库，
        'main'=>array(
            //mysql主从结构中的主库
            'master'=>'mainDB',
            
            //mysql主从结构中的从库，可以指定多个，mysqlMasterSlave设置为tru时生效
            'slave'=>array('mainDB'),
            
            //可以指定数据库，或参考下面示例自动加载前缀、自动调用键名等方式调用指定的数据库
            'db'=>'whw_main',
            
            //是否默认使用分表功能,0/1
            'multiTb'=>1,
        ),
        
        'article'=>array(
            'master'=>'mainDB',
            'slave'=>array('mainDB'),
            'db'=>'whw_article'//根据键名自动生成的数据库
        ),
        
        'blog'=>array(
            'master'=>'mainDB',
            'slave'=>array('mainDB'),
            'db'=>'whw_blog'//根据键名自动生成的数据库
        ),
        
        'account'=>array(
            'master'=>'mainDB',
            'slave'=>array('mainDB'),
            'db'=>'whw_account'//根据键名自动生成的数据库
        ),
        
        'legacy_blog'=>array(
            'master'=>'DB4',
            'slave'=>array('DB4'),
            'db'=>'wxc_blog'//根据键名自动生成的数据库
        ),
        
        'legacy_members'=>array(
            'master'=>'DB3',
            'slave'=>array('DB3'),
            'db'=>'wxc_members'//根据键名自动生成的数据库
        ),
        
    ),
    'server'=>array(
        'mainDB'=>array(
            'dbdriver'=> 'mysql',
            'server'=> '104.154.95.120',
            'user'=> 'wxc',
            'password'=> 'f7!P#x$l9Hs8',
            'charset'=> 'utf8',
            'pconnect'=> 0,
        ),
        
        'DB3'=>array(
            'dbdriver'=> 'mysql',
            'server'=> '104.197.66.148',
            'user'=> 'wxc',
            'password'=> 'f7!P#x$l9Hs8',
            'charset'=> 'utf8',
            'pconnect'=> 0,
        ),
        
        'DB4'=>array(
            'dbdriver'=> 'mysql',
            'server'=> '104.197.30.114',
            'user'=> 'wxc',
            'password'=> 'f7!P#x$l9Hs8',
            'charset'=> 'utf8',
            'pconnect'=> 0,
        ),
    ),
);
?>
