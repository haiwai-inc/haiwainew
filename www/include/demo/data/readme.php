<?php
/**
 * data 目录中的数据都是由运行中的程序动态产生的结果，由各站点程序实时共享，需做文件同步设置
 * 
 * application 各应用程序运行时的中间结果，加载nfs
 * sign 用于标识强制更新的缓存文件，加载nfs
 * logs php记录的日志文件，加载nfs
 * index 存放用于生成搜索索引的xml中间数据文件
 * 
 * cache 映射 /cache／目录的结构，
 * 		 由于cache目录是可以随时删除的，此处设置仅是为说明cache下的各级结构及作内容说明
 */
?>