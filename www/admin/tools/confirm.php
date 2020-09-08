<?php
$item = empty($_GET['item'])?"groups":$_GET['item'];
$config = include "./confirm/{$item}.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> 文学城<?php echo $config['title'];?>项目验收清单<?php echo date("m/d/Y")?></title>
<style>
	body{font-size:14px;}
    tr{height:28px;}
    td{text-align:center;}
    .l{text-align:left;}
    .breakpage{page-break-before:auto;page-break-after:always;}
</style>
</head>
<body>
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>项目验收清单 - 1</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong> 错误打 <strong>X</strong></h3>
    <hr>
    <h2>不同身份登录,以下功能是否逻辑正确</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l">内容</td>
          <td width="85">普通用户</td>
          <td width="85"><?php echo $config['admin'];?></td>
          <td width="85">超级管理员</td>
        </tr>
        <?php foreach($config['func'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td width="85"></td>
          <td width="85"></td>
          <td width="85"></td>
        </tr>
        <?php }?>
    </table>
    <br class="breakpage">
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>项目验收清单 - 2</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong> 错误打 <strong>X</strong></h3>
    <hr>
    <h2>页面文字及显示布局</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l">内容</td>
          <td width="60">显示文字</td>
          <td width="60">链接目标</td>
          <td width="60">项目布局</td>
          <td width="60">图片调用</td>
        </tr>
        <?php foreach($config['func'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td width="60"></td>
          <td width="60"></td>
          <td width="60"></td>
          <td width="60"></td>
        </tr>
        <?php }?>
    </table>
    <br class="breakpage">
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>项目验收清单 - 3</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong> 错误打 <strong>X</strong></h3>
    <hr>
    <h2>浏览器兼容性</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l">内容</td>
          <td width="30">IE6</td>
          <td width="30">IE8</td>
          <td width="30">IE9</td>
          <td width="30">IE10</td>
          <td width="30">IE11</td>
          <td width="45">FireFox</td>
          <td width="45">Safari</td>
          <td width="45">Chrome</td>
        </tr>
        <?php foreach($config['func'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td width="30"></td>
          <td width="30"></td>
          <td width="30"></td>
          <td width="30"></td>
          <td width="30"></td>
          <td width="45"></td>
          <td width="45"></td>
          <td width="45"></td>
        </tr>
        <?php }?>
    </table>
    <br class="breakpage">
    <h1><?php echo date("m/d/Y")?> 文学城<?php echo $config['title'];?>项目验收清单 - 4</h1>
    <h3>请在下列各项的空白标识测试结果，正确打 <strong>O</strong> 错误打 <strong>X</strong></h3>
    <hr>
    <h2>链接及数据检查</h2>
    <table width="100%" border="2" cellspacing="0" cellpadding="3">
        <tr style="background-color: #333;color:#FFF;font-weight:bold;">
          <td class="l">内容</td>
          <td width="65">是否正确</td>
        </tr>
        <?php foreach($config['other'] as $val){?>
        <tr>
          <td class="l"><?php echo $val;?></td>
          <td width="65"></td>
        </tr>
        <?php }?>
    </table>
     <br>
     <br>
     <br>
     <br>
     <br>
     <h2>测试人：</h2>
     <h2>测试时间：</h2>
</body>
</html>