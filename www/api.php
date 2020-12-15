<?php
include 'inc.comm.php';

/**
 * @author sida
 * [
 *  'data'=>[],
 *  'error'=>[
 *      ['name'=>'name1','msg'=>'msg1'],
 *      ['name'=>'name1','msg'=>'msg1']
 *  ],
 *  'status'=>true
 *  ]
 */
if(!empty($_GET['docs'])){
    echo "<h2>所有模组接口</h2>";
    $dirs=array_filter(glob('*'), 'is_dir');
    if(!empty($dirs)){
        $frame_pool=['admin','cache','cache_dist','upload','data','include','vendor'];
        foreach($dirs as $k=>$v){
            if(!in_array($v,$frame_pool)){
                echo "<h3>{$v}</h3>";
                $files = array_diff(scandir("{$v}/api"), array('.', '..'));
                if(!empty($files)){
                    foreach($files as $vv){
                        $classname=str_replace(".php","",$vv);
                        echo "&nbsp&nbsp&nbsp&nbsp<a href='/api/v1/docs/{$v}/{$classname}/'>/api/v1/{$v}/{$classname}/</a><br>";
                    }
                }
            }
        }
    }
    echo "<h2>所有数据返回结构</h2>";
    $format=[
        'data'=>[],
        'error'=>[
            ['name'=>'name1','msg'=>'msg1'],
            ['name'=>'name1','msg'=>'msg1']
        ],
        'status'=>true
    ];
    debug::D($format);
    exit;
}

//检查及初始化参数
if( empty($_GET['app']) || empty($_GET['class']) || empty($_GET['func']) ) {
	$obj = new Api();
	$obj->notfound();
}

$app = conf('appname',$_GET['app']);
$class = $_GET['class'];
$func = $_GET['func'];

//定义app
define( 'AppName', $app );

//加载api文件
$filename = DOCUROOT."/{$app}/api/{$class}.php";
if( file_exists($filename) ) require_once($filename);

//执行API
$obj=new $class();
$obj->run($func);

//test1