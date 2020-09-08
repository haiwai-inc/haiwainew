<?php
$item = empty($_GET['item'])?"groups":$_GET['item'];
$config = include "./confirm/design-{$item}.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> 文学城<?php echo $config['title'];?>设计效果验收清单<?php echo date("m/d/Y")?></title>
<style>
	body{font-size:14px;}
    tr{height:28px;}
    td{text-align:center;}
    .l{text-align:left;}
    .breakpage{page-break-before:auto;page-break-after:always;}
</style>
</head>
<body>
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>设计效果验收清单 - 1</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong>, 错误打 <strong>X</strong>, 不适用项目打 <strong>—</strong></h3>
    <hr>
    <h2>设计稿页面：</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l" width="100">项目</td>
          <td>页面布局</td>
          <td>表现形式</td>
          <td>栏目标题</td>
          <td>图片尺寸</td>
          <td>字体大小</td>
          <td>字体颜色</td>
          <td>链接效果</td>
          <td>内容间距</td>
        </tr>
        <?php foreach($config['func'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php }?>
    </table>
    <br class="breakpage">
    
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>设计效果验收清单 - 2</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong>, 错误打 <strong>X</strong>, 不适用项目打 <strong>—</strong></h3>
    <hr>
    <h2>公共调用部分：</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l">内容</td>
          <td width="80">是否正确</td>
        </tr>
        <?php foreach($config['other'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td></td>
        </tr>
        <?php }?>
    </table>
     <br>
     <br>
     <br>
     <h2>测试人：</h2>
     <h2>测试时间：</h2>
     <br>
     <br>
</body>
</html>