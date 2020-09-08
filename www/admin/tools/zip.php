<?php
# $path 要压缩的目录所在位置
# $filezip 压缩后的完整文件名
# $id 要压缩的目录名称
$zipname=$title.'.zip';//压缩文件名
$filezip=$path."/".$id."/".$zipname;
shell_exec("/pub/zip.sh $path $filezip $id");