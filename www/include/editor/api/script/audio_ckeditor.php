<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<style>
	body{margin:0;padding:0;overflow:hidden;}
	p{margin:0;padding:0;}
	#nothing{width:298px;height:22px;line-height:22px;text-align:left;padding:0;font-size:12px;}
</style> 
</head>
<body>

<?php 
$url = empty($_GET['url'])? "" : trim($_GET['url']);
$msg = empty($_GET['msg'])?"请浏览您要上传的音频文件，系统目前仅支持mp3格式":$_GET['msg'];
$autostart = empty($_GET['autostart'])?"yes":$_GET['autostart'];
$autoplay = "autoplay";
$autoplay_b = "true";
if(strtolower($autostart) == 'no'){
	$autoplay = "";
	$autoplay_b = "false";
}
if(!empty($url)){?>
<div>
<audio controls <?php echo $autoplay ?> >
	<source src="<?php echo $url ?>" type="audio/mpeg">
	<source src="<?php echo $url ?>" type="audio/ogg">
  	<source src="<?php echo $url ?>" type="audio/wav">  

 	<object style="width:100%; height:60px" >
		<param name="controller" value="true" />
    		<param name="FileName" value="<?php echo $url ?>" />
    		<param name="autoplay" value="<?php echo $autoplay_b ?>" />
    		
    		<object style="width:100%; height:60px"  type="audio/mpeg" data="<?php echo $url ?>">
        		<param name="controller" value="true" />
        		<param name="autoplay" value="<?php echo $qutoplay_b ?>" />
        		<a href="<?php echo $url ?>">Your browser is not able to embed this media here so click this link to play the file in an external application </a>
    		</object>
	</object>   

</audio>
</div>
<br>
<?php }else{ ?>
<div id="nothing"><?php echo $msg;?></div>	
<?php }?>
</body>
</html>
