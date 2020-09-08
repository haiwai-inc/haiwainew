<?php 
include '../../../../inc.comm.php';
func_initSession();

$path=empty($_GET['path'])?'/images/none.jpg':$_GET['path']."?".time();

//预览图片宽高及选框比例
$wp=$_GET['wp'];
$hp=$_GET['hp'];
$ratio=round($wp/$hp,1);

//备选图片输出的宽高
$wo=$_GET['wo'];
$ho=$_GET['ho'];

//编辑区域的宽高
$editorW = ($wo<450)?450:$wo;
$editorH = ($ho<450)?450:$ho+60;

//坐标信息
$setselect=empty($_GET['coords'])?'0,0,200,200':$_GET['coords'];

//提交后的返回链接
$url="./pic.php?wp={$wp}&hp={$hp}&coords={$setselect}";
?>
<html>
	<head>

		<script src="/js/Jcrop/js/jquery.min.js"></script>
		<script src="/js/Jcrop/js/jquery.Jcrop.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="/js/Jcrop/css/jquery.Jcrop.css" type="text/css" />
		<style>
			body{margin:5px;padding:0px;background-color:#FFF;}
			input{font-size:12px;}
			.gry9{line-height:28px;font-size:9pt;color:gray;}
		</style>
		<script language="Javascript">
			var times=0;
			
			function $$(sid){
				return document.getElementById(sid);
			}
			
			// Our simple event handler, called from onChange and onSelect
			// event handlers, as per the Jcrop invocation above
			function showPreview(coords)
			{
				if (parseInt(coords.w) > 0)
				{
					var rx = <?php echo $wp;?> / coords.w;
					var ry = <?php echo $hp;?> / coords.h;

					var w=$$('w').value;
					var h=$$('h').value
					
					jQuery('#preview').css({
						width: Math.round(rx * w ) + 'px',
						height: Math.round(ry * h ) + 'px',
						marginLeft: '-' + Math.round(rx * coords.x) + 'px',
						marginTop: '-' + Math.round(ry * coords.y) + 'px'
					});
				}

				sendval('pic_x',coords.x);
				sendval('pic_y',coords.y);
				sendval('pic_x2',coords.x2);
				sendval('pic_y2',coords.y2);

				//初始化时不设置编辑状态,初始化操作会调用3次showPreview
				times++;
				if( times > 3 ) sendval('pic_editor_status','1');
			}

			function sendval(sid,val){
				parent.document.getElementById(sid).value = val;
			}

			function checkimg(url,callback){
                var img = new Image();
               	img.src=url
				
                var appname = navigator.appName.toLowerCase();
                if (appname.indexOf("netscape") == -1) {
                    //ie
                    img.onreadystatechange = function(){
                        if (img.readyState == "complete") {
                            callback(img);
                        }
                    };
                }
                else {
                    //firefox
                    img.onload = function(){
                        if (img.complete == true) {
                            callback(img);
                        }
                    }
                }
            }

            function checkCropbox(img){
            	
				if(parent.document.getElementById('pic_source_file').value!='<?php echo $path;?>'){
					sendval('pic_source_file','<?php echo $path;?>');
					sendval('pic_editor_status','1');
				}
				var w=$$('w').value;
				var h=$$('h').value
				sendval('pic_source_width',w);
				sendval('pic_source_height',h);

				jQuery('#cropbox').Jcrop({
					onChange: showPreview,
					onSelect: showPreview,
					aspectRatio: <?php echo $ratio;?>, //选中区域宽高比为1，即选中区域为正方形
					setSelect:[<?php echo $setselect?>] //初始化选中区域 
				});
            }
		</script>

	</head>
	<body>
	<form action="./upload.php" method="post" name="uploadForm" id="uploadForm" style="margin:0px;padding:0px;" enctype="multipart/form-data">
        <div style="width:<?php echo $wp;?>px;height:<?php echo $hp;?>px;overflow:hidden;float:left;">
			<?php if ($path=='/images/none.jpg'){?><table id="picEditorNone" width="<?php echo $wp;?>" height="<?php echo $hp;?>" border="0" cellpadding="0" cellspacing="0" style="margin:4px;" class="box nonepic">
				<tr>
		            <td align="center" valign="middle" style="background:url(/images/admin/x.gif);font-size:14px;font-weight:bold;color:#a52a2a;">
		                暂  无
		                <br>
		                图  片
		            </td>
		        </tr>
		    </table>
		    <?php }else{ ?>
			<img src="<?php echo $path;?>" id="preview" width="<?php echo $wo;?>" height="<?php echo $ho;?>"/>
			<?php }?>
		</div>
		<div style="width:<?php echo $editorW;?>px;float:right;margin:0;text-align:left;line-height:28px;">
			<input name="userPicFile" id="userPicFile" type="file"/>
			
			<!-- 从父页面获得目标文件的相关信息 -->
			<input name="path" type="hidden" id="path" value="">
			<input name="filename" type="hidden" id="filename" value="">
			<input name="width" type="hidden" id="width" value="">
			<input name="height" type="hidden" id="height" value="">
			
			<input name="submit" type="submit" value=" 上传图片 "/>
			<input name="saveimg" type="button" value=" 保存 " onclick="this.value=' 正在保存... ';parent.piceditor.save('<?php echo $path;?>');">
			<input name="saveimg" type="button" value=" 撤消 " onclick="parent.piceditor.reset();">
			<br>
            <input id="editorSelect" type="checkbox" value="Y" checked="checked"/>
            <span class="gry9">是否使用编辑器，如您上传的是gif动画，选中此功能后会失去动画效果</span>
			<br>
			<div id="userPicEditor">
				<img src="<?php echo $path;?>" id="cropbox" width="<?php echo $wo;?>" height="<?php echo $ho;?>" />
			</div>
			
			<input name="w" type="hidden" value="<?php echo $wo;?>" id="w"/>
       		<input name="h" type="hidden" value="<?php echo $ho;?>" id="h"/>
			<script language="Javascript">
				checkimg('<?php echo $path;?>',checkCropbox);
				$$('path').value = parent.document.getElementById('path').value;
				$$('filename').value = parent.document.getElementById('filename').value;
				$$('width').value = parent.document.getElementById('width').value;
				$$('height').value = parent.document.getElementById('height').value;

				parent.document.getElementById('picIfrEditor').height = <?php echo $editorH;?>;
			</script>
			
			<input type="hidden" value="<?php echo $url;?>" name='reurl' id="reurl"/>
       </div>
    </form>
	</body>
</html>
<?php 
if (isset($_SESSION['uploadmsg'])){
	echo '<script language="Javascript">alert("'.$_SESSION['uploadmsg'].'");</script>';
	unset($_SESSION['uploadmsg']);
}
?>