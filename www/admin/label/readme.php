<?php
/**
 * 调用全局标签 conf('label.document','develop.php.framework');
 * 调用本站标签 label('label.key.to.something');
 * 
 * 核心函数中仅设置一个lang函数,语言调用方式：
 * lang($key=''label.key.to.something'',$default='默认值',$langbase='blyz',$type='name');
 * lang调用label输出标签内容，label默认会调用当前站点的设置，如站点标签没有设置内容，会自动调用全局标签内容
 * 
 * Factory中不设置默认输出全部语言，改由factory传参设定
 * 
 * 
 * TODO 
 * 增加调用标识的修改限制
 * 增加js联运菜单的调用支持,可以指定调用标签的起始位置
 * 增加js单级菜单的调用支持
 */