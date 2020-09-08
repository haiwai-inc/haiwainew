<?php 
/**
开发文档程序

首页，page,文章，介绍页面

template,page,article,content,admin_document

需要独立重建的单元，
搜索分词，视频播放器，网页编辑器

需要完善的单元
svn，mail
*/

/**
 * 项目规划 
 * 
 * mini: 中小企业小站 cms
 * web: 综合大站 
 * erp: crm  管理核心
 * 
 */

/**
 * 开发进程：
 * 开发文档程序，站点管理程序，文章发布，专题及首页，注册及权限
 * 博客，论坛，空间（消息等）
 * 系统功能程序，监控，优化，搜索等
 * 
 * 核心：用服务赚钱，广告服务，IT服务
 * 
 * 赢利模式：
 * 广告（banner，eDM, 视频,关联定位广告，联盟广告[google adsense]），
 * 价格比较，产品推荐（amazon等）
 * 网站，社区(信息发布平台)，ERP等IT服务，
 * 实物销售
 * 其他，（活动招募，市场调查，试用推广，专题赞助，栏目冠名）
 * 100d,1000d 10000d
 * 
 * 一级分类：
 * 运营（商业计划，产品定义，页面效果，赢利模式），
 * 部署（基本系统安装，子系统apache,ftp,mail,视频，数据库，搜索）
 * 开发（框架，bbs）
 * 使用 (空间，博客，视频，图书)
 * ……
 * 
 */

/**
 * 内容分类: 首页，(资讯：新闻，文章)，百科，专题，读书，视频，购物，商圈，社区（博客，圈子，论坛）
 * 目标人群，40岁以上
 * 文章：
 * =======================
 * 吃喝 * 玩乐 * 情趣 * 养生
 * 
 * 新闻 
 * **文摘，散文，故事，笑话，连载 ……
 * 休生养性，
 * 生活窍门，
 * 四海漫游，
 * 花鸟鱼虫，
 * 琴棋书画，
 * 古玩收藏，
 * 摄影钓鱼，
 * 各色美食
 * 
 * 
 * 
 * 收入来源：
 * 
 * 第一阶段：广告及大企业赞助
 * 大众产品，医疗，设备，药品，养老，旅游，活动相关（琴棋书画）
 * 
 * 第二阶段：中小企业托管及广告服务
 * 医疗院所，设备厂商，药品厂家，养老机构，社会组织，媒体单位
 * 
 * 第三阶段：个人用户的电子商务
 * 保健品，养生用品，玩物，礼品，各类商品
 * 
 * 
 * 
 */

/**
 * 数据库设计
 * main: label, page, document
 * article: article, blog, topic, book
 * dict: dict
 * bbs: bbs, wall
 * user: message, group, userinfo, score
 * logs: ad,statistics,behavior(footprint),log_score 
 * item: partner(vendor),video,shop
 * other: survey,newsletter,project,activity
 */

/**
 * 有下级关联内容的页面，activity,bbs,book,blog,topic,survey,partner……
 */

/**
 * A、首页（home,searchlist[article,dict,topic,book,……]），资讯（home,subnet,subject,more,article,），百科(home,sort,more,view)，
 * B、专题(home,list,topic,more(pic,txt),article,picture)，读书(同前)，
 * C、视频 (standard)
 * D、购物，开发板价格优惠及比较等(standard)
 * E、商圈，厂商专区，商家(standard)
 * A、社区（博客，圈子，论坛）(standard)
 * F、myspace,group,message……
 * G、后台功能
 */
?>