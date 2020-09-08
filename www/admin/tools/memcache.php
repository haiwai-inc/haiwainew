<?php
$ip=empty($_GET['ip'])?"127.0.0.1":$_GET['ip'];
$port = empty($_GET['port'])?"11211":$_GET['port'];

$out=shell_exec("echo stats | nc {$ip} {$port}");
$arr=explode("\n", $out);

$value=array();
foreach($arr as $val){
	if(empty($val)) continue;
	if(strstr($val,"END"))continue;
	
	$val=str_replace("STAT ", "", $val);
	$tmp = explode(" ", $val);
	$value[$tmp[0]]=$tmp[1];
}

//格式化存储单位
function setupSize($lenght) {
	$units = array (
		'B',
		'KB',
		'MB',
		'GB',
		'TB',
		'PB',
		'EB',
		'ZB',
		'YB'
	);
	foreach ($units as $unit) {
		if ($lenght > 1024)
		$lenght = round($lenght / 1024, 1);
		else
		break;
	}
	if (intval($lenght) == 0) {
		return ("0 Bytes");
	}
	return $lenght . ' ' . $unit;
}

//格式化时间单位
function setupTime($timesec){
	$d= round($timesec/86400, 0, PHP_ROUND_HALF_DOWN);
	$timesec= $timesec%86400;
	
	$h= round($timesec/3600, 0, PHP_ROUND_HALF_DOWN);
	$timesec= $timesec%3600;
	
	$m= round($timesec/60, 0, PHP_ROUND_HALF_DOWN);
	$timesec= $timesec%60;
	
	$str="";
	if(!empty($d))$str.=$d."天";
	if(!empty($h))$str.=$h."小时";
	if(!empty($m))$str.=$m."分";
	$str.=$timesec."秒";
	
	return $str;
}


$value["uptime"]=setupTime($value["uptime"]);
$value["time"]=date("Y-m-d H:i:s",$value["time"]);
$value["hash_bytes"]=setupSize($value["hash_bytes"]);

$value["bytes_read"]=setupSize($value["bytes_read"]);
$value["bytes_written"]=setupSize($value["bytes_written"]);

$value["limit_storage"]=setupSize($value["limit_maxbytes"]);
$value["use_storage"]=setupSize($value["bytes"]);
unset($value["bytes"]);
unset($value["limit_maxbytes"]);


//输出计算结果
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo "<pre>";
print_r($value);
echo "</pre>";