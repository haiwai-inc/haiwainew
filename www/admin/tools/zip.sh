#! /bin/sh
# $1 要压缩的目录所在位置
# $2 压缩后的完整文件名
# $3 要压缩的目录名称


cd $1
/usr/bin/zip -r $2 $3