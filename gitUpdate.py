# -*- coding: utf-8 -*-
# 使用python自动同步代码

# 安装模块
# pip install --user schedule pymemcache
# pip install phpserialize

# 后台挂起的启动命令, -u 表示持续输出缓冲区的内容
# nohup python3 -u haiwainew.py>/pub/www/haiwainew.com/www/cache/log.txt &

# 退出，找到job id，然后直接kill
# ps aux|grep haiwainew.py 
# kill [job id]

# 依赖包读写
from pymemcache.client.base import Client
import schedule
import time
import os
import subprocess
from subprocess import check_output
from phpserialize import serialize, unserialize

# 修改服务器参数========
SYSTEM_VERSION= "beta.haiwainew.com"
LOCAL_IP= "10.240.0.3"
SERVER_ROOT = "/pub/www/haiwainew.com/"
MEM_HOST = 'sourceNode'
MEM_KEY = SYSTEM_VERSION+'_git_'+LOCAL_IP
TIME_GAP = 3
MEM_LOCK_SIGN = SYSTEM_VERSION+'_git_'+LOCAL_IP+'_lock'
LOG = SERVER_ROOT+"www/cache/log.txt"

def sync_code():
    val = get_mem()

    if not val:
        pass
    else:
        #获取cache字符串
        val = convert(unserialize(val))

        #服务器正在同步中
        if not check_lock():
            return

        #上锁
        lock()

        #解除同步cache
        del_mem()

        #同步时间
        current_time = time.strftime("%Y-%m-%d %H:%M:%S")
        out = "Git checkout at "+current_time+"..."

        #同步git
        out+= update_git()

        #同步dist
        if val['dist']:
            out+= update_dist()

        #输出同步信息
        if out:
            f = open(LOG, "w")
            f.write(out)
            f.close()
        
        #解锁
        unlock()


def update_git():
    os.chdir(SERVER_ROOT)
    out = subprocess.check_output(["git","pull"]).decode('utf-8')
    return out

def update_dist():
    #初始dist路径
    server_dist_path = SERVER_ROOT+"www/cache/vue/"
    build_dist_path = SERVER_ROOT+"vue/dist/"

    #同步插件
    os.chdir(SERVER_ROOT+"vue")
    out = ""
    out += subprocess.check_output(['npm', 'install']).decode('utf-8')

    #生成编译包
    out += subprocess.check_output(['npm', 'run', 'build']).decode('utf-8')

    #成功后复制编译包
    if os.path.exists(build_dist_path):
        out += subprocess.check_output(['rsync','-a','--delete',build_dist_path,server_dist_path]).decode('utf-8')

    return out

def get_mem():
    mem_obj = init_mem()
    out = mem_obj.get(MEM_KEY)
    return out

def set_mem():
    mem_obj = init_mem()
    mem_obj.set(MEM_KEY, "some value here")

def del_mem():
    mem_obj = init_mem()
    mem_obj.delete(MEM_KEY)

def init_mem():
    return Client((MEM_HOST, 11211))

def check_lock():
    mem_obj = init_mem()
    out = mem_obj.get(MEM_LOCK_SIGN)
    if out:
        return False

    return True

def lock():
    mem_obj = init_mem()
    mem_obj.set(MEM_LOCK_SIGN, 1)

def unlock():
    mem_obj = init_mem()
    mem_obj.delete(MEM_LOCK_SIGN)

def convert(data):
    if isinstance(data, bytes):  return data.decode('ascii')
    if isinstance(data, dict):   return dict(map(convert, data.items()))
    if isinstance(data, tuple):  return map(convert, data)
    return data

#执行同步
sync_code()

schedule.every(TIME_GAP).seconds.do(sync_code)

while True:
    schedule.run_pending()
    time.sleep(1)


















































































