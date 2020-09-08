<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>编辑器2015版测试</title>
<style type="text/css">
body {
	font-size: 12px;
	padding: 10px;
	width: 830px;
	margin: 0 auto;
}

h2 {
	margin: 4px;
}

a:link, a:visited, a:hover, a:active {
	font-size: 14px;
	line-height: 32px;
}

a:link {
	color: #00F;
	text-decoration: none;
}

a:visited {
	color: #00F;
}

a:hover {
	color: #F47B20;
	text-decoration: underline;
}

a:active {
	color: #F00;
}

#submit, #cancel {
	height: 28px;
	margin: 15px 0;
	padding: 0 25px;
	font-size: 14px;
}
</style>
<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script>
</head>
<body>
<?php 
	//显示提交的数据
	if(!empty($_POST['title'])){
		echo "<h2>{$_POST['title']}</h2> [<a href=\"./demo.php\" style=\"font-size:14px;\">修改</a>] <hr>";
	echo $_POST['content'];
} else {
?>
<form action="./demo.php" method="post" name="form1" id="form1">
		<p>标题：</p>
		<p>
			<input type="text" id="title" name="title" style="width: 100%; font-size: 14px; line-height: 26px; height: 26px;" value="" />
		</p>
		<p>内容：</p>
		<textarea name="content" id="content" rows="10" cols="80"></textarea>
		<script>
		
		CKEDITOR.config.toolbar = [
			{name:'bbs',items:['Bold', 'Italic', 'Underline','Strike','PasteText', 'PasteFromWord','JustifyLeft', 'JustifyCenter', 'JustifyRight',
			                   'Image','Link', 'Unlink','Smiley',
			                   'FontSize','TextColor', 'BGColor','NumberedList', 'BulletedList', 'RemoveFormat', 'Preview','Maximize']}
			
          	           	
		              ];
		CKEDITOR.config.height = 500; 
		CKEDITOR.config.language ='zh';
		
        CKEDITOR.replace( 'content' );
            </script>

		<input type="submit" id="submit" value="保 存">
	</form>
<?php 
	}
?>      <!-- '/',
			{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
          	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
          	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
          	{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
          	'/',
          	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
          	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
          	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
          	{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
          	'/',
          	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
          	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
          	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
          	{ name: 'others', items: [ '-' ] },
          	{ name: 'about', items: [ 'About' ] }-->
</body>
</html>