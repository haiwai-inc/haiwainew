<?php
/*
 * page 分为： 
 * Portal, 模板在/template/default/index.html
 * Alias(
 *    folder 模板在/folder/Tpl/default.html,
 *    space 模板在/space/Tpl/abc.html,
 *    page模板在/template/default/subnet.html
 * ),
 * Project( 
 *    book/abc/; 
 *    topic/abc/; 
 *    activity/abc/;
 *    subject/abc/;
 *    partner/abc/;
 * ) 
 * 模板在/book/Tpl/pages/abc.html
 */

/**
 * page常规的数据单元操作：
 * 
 * 图文组［单个/多个］（图片(视频)/文字链）
 * 文章列表［单个/多个］（录入）
 * 文章列表［单个］（推荐）
 * 评测图 ，建立，录入对比数据
 * 对比单元，录入对比内容，定义对比单元
 * 数据单元 （外调的程序 survey:123）
 * 自定义执行文件（指定调用 php执行文件  /page/Lib/extra/default/abc.php）
 * HTML代码; 直接输出
 * 
 * Lib/plugins 中的类负责各相关模块在页面显示读取数据，而数据的写由各模块分开独立完成，即集中读取，分别写入
 */
 

/**
 * page单元生成效率问题
 * 
 * ＃＃＃ 1 ＃＃＃
 * 每一个类型的单元的数据输出步骤：1，一次查出全部类别，2，一次查出所有类别下标识为指定范围的数据
 * 使用php计算并组合输出的数据结构
 * 
 * 效率问题的关键在于“录入数据时有效范围的确定及处理“
 * 
 * ＃＃＃ 2 ＃＃＃
 * page由多单元组成，由于各单元条件不同，会出现同表数据多次重复查询的情况
 * 解决方法：
 * 使用分步执行的方式，第一次循环执行全部page单元不执行直接查询，只是记录各单元需要的查询条件
 * 在全部条件确立后，集中执行，缓存所需数据
 * 第二次执行单元循环时，从缓存中集中处理数据。
 * 对于并行查询使用此方法，对于串行查询视个例情况处理。
 * 
 * 
 * 数据库分库级别： 数据源(page, topic)  =>  分库主键  ===  分表主键
 */