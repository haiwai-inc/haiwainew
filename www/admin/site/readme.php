<?php
/**
 * 原page程序调整为4个部分
 * 
 * 站点管理 site
 * 专题图书及单元程序，独立按page机制运行
 * 网站及各栏目首页，按单页page机制运行
 * 静态页面程序按，page及pagegroup形式运行
 *

 * 站点下各级页面的模板调用规则
 * 首页，
 * 模板选择自定义后，调用 /template/站点标识／index.html 作为首页模板
 * 模板选择其它值后，调用 /tmeplate/选定模板值/index.html 作为首页模板
 * 
 * 栏目页面
 * 模板选择自定义后，调用 /项目目录/Tpl/view/当前项目标识/index.html 作为项目首页模板
 * 模板选择其它值后，调用 /项目目录/Tpl/view/选定模板值/index.html 作为项目首页模板
 * 
 * 页面
 * 调用 /template/站点标识／页面标识.html 作为页面模板
 * 
 * 
 * 站点相关功能的设置
 * ==============================================================
 * 有子级page的页面设置：
 * 子级的page及相关附属内容全部调用各自独立的数据库
 * 
 * 有分类 /book/admin/catelist.php?pid=1  扩展 page/Action/baseAction/_catelist.php
 * 无分类 /book/admin/manager.php?pid=1 扩展 page/Action/baseAction/_manager.php
 * 
 * 针对类别及全部内容可以建立权限控制
 * ==============================================================
 * 文章系统逻辑：
 * 介绍性，
 * 直连到 /article/admin/content.php?pid=1&appid=32
 * 
 * 内容性,
 * 链接到分类管理 /article/admin/index.php?pid=1&appid=33
 * 快捷管理全部文章 /article/admin/article.php?pid=1&appid=33
 * 
 * 针对类别及全部内容可以建立权限控制
 */
?>