<template>
  <div>
    <!-- 悄悄话列表 -->
    <div class="qiaoqiao-list"  v-if="!showView">
      <h6 class="pb-2">
        全部悄悄话
      </h6>
     
      <ul>
        <li v-for="(item,index) in qqhList.data" :key="index">
          <div class="pull-right dropdown">
            <drop-down
            class="nav-item dropdown"
            :haiwaiIcon="iconmore3v"
            haiwaiClass="haiwaiicon"
            style="padding:0;"
            >
              <a 
              class="dropdown-item" 
              href="#">删除会话</a>
              <a class="dropdown-item" href="#">加入黑名单</a>
              <a class="dropdown-item" href="#">举报用户</a>
            </drop-down>
            
          </div>
          <div class="d-flex" @click="showQqhView(item.id)">
            <avatar :data="authorInfor" :imgHeight="48"></avatar>
            <div class="pl-2">
              <span class="name">{{item.basic_userinfo_userID.id==userID?item.basic_userinfo_touserID.username:item.basic_userinfo_userID.username}}</span>
              <span class="wrap">接口缺少最后一条的msbody</span>
            </div>

          </div>
        </li>
      </ul>
    </div>
    <!-- 悄悄话详情 -->
    <div class="qiaoqiao-view" v-if="showView">
      <div class="row no-gutters">
        <div class="col-4 pt-2">
          <a href="#" 
          class="back-to-list active" 
          @click="showView=false"
            ><left-arrow ></left-arrow>
            返回悄悄话列表
          </a>
        </div>
        <div class="col-4 pt-2 text-center">
          <b>
            与
            <a href="#" target="_blank">呜啦啦</a>
            的对话
          </b>
        </div>
        <div class="col-4 d-flex justify-content-end">
          <drop-down
          class="nav-item dropdown"
          :haiwaiIcon="iconmore3v"
          haiwaiClass="haiwaiicon"
          style="padding:0;"
          >
            <a class="dropdown-item" href="#">加入黑名单</a>
            <a class="dropdown-item" href="#">举报用户</a>
          </drop-down>
        </div>
      </div>
      <div class="message-show">
        <ul class="message-list">
          <li 
          v-for="(item,index) in qqhView.data" 
          :key="index"
          :class="{'message-l':item.userID!==userID,'message-r':item.userID===userID,}" >
            <a href="#" class="avatar"
              ><img class="rounded-circle" src="/img/julie.jpg"
            /></a>
            <div><span class="content">{{item.msgbody}}</span></div>
            <span class="time">{{item.dateline | formatDate}}</span>
          </li>
          
        </ul>
      </div>
      <div class="write-message mb-4">
        <el-input
        type="textarea"
        :rows="2"
        placeholder="请输入内容"
        v-model="msgbody">
        </el-input>
        <!-- 输入框 -->
        <div class="text-right">
          <n-button 
          type="primary"
          round 
          simple
          @click.native="sendQqh(touserID)"
          >
            发送
          </n-button>
        </div>
        
      </div>
    </div>
  </div>
</template>
<script>
import { Button,DropDown,} from '@/components';
import { Input } from 'element-ui';
import { LeftArrow } from "@/components/Icons";
import Avatar from "../../blog/pages/components/Main/Avatar";
import HaiwaiIcons from '@/components/Icons/Icons';
import {formatDate} from '@/directives/formatDate.js';
export default {
  name: 'notice-qqh',
  components: {
    [Button.name]: Button,
    [Input.name]: Input,
    DropDown,
    LeftArrow, 
    Avatar,
  },
  data(){
      return{
        userID:1,
        touserID:2,
        msgID:0,
        iconmore3v:HaiwaiIcons.iconmore3v,
        showView:false,
        qqhList:{},
        qqhView:{},
        msgbody:'',
        authorInfor: {
          avatarUrl: "/img/julie.jpg",
          isHot: true,
          authorHomepage: "",
          name: "用户名",
          firstLetter: "用",
          description: "简介简介简介简介",
          isFollowed: true,
        },
      }
  },
  created: function () {
    this.qqh_list();
  },
  methods:{
    async qqh_list() {
      let user = this.$store.state.user;
      let res = await user.qqh_list();
      this.qqhList=res.data;
      console.log(this.qqhList);
    },
    async qqh_view(id) {
      let user = this.$store.state.user;
      let res = await user.qqh_view(id);
      this.qqhView=res.data;
      this.qqhView.data=res.data.data.reverse();
      console.log(res);
    },
    showQqhView(id){
      this.msgID=id;
      this.qqh_view(id);
      this.showView=true;
    },
    sendQqh(id){
      this.send(this.userID,id,this.msgbody);
    },
    async send(userID,touserID,msgbody) {
      let user = this.$store.state.user;
      let res = await user.sendQqh(userID,touserID,msgbody);
      this.showQqhView(this.msgID);
      this.msgbody='';
      console.log(res);
    }
  },
  filters: {
    formatDate(time) {
        var date = new Date(time);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  }
}
</script>
<style>
/*悄悄话列表页*/
.qiaoqiao-list .dropdown-toggle::after,.qiaoqiao-view .dropdown-toggle::after {
    display:none;
}
.qiaoqiao-list .wrap {
  display: block;
  color:#999;
  padding:8px 0;
  /* padding: 20px 20px 20px 0;
  min-height: 88px; */
}
.qiaoqiao-list ul {
  list-style: none;
  padding-left: 0;
}
.qiaoqiao-list li {
  padding:10px 0;
  position: relative;
  border-top: 1px solid #f0f0f0;
}
.qiaoqiao-list .avatar,
.qiaoqiao-view .avatar {
  float: left;
  /* margin: 20px 10px 20px 5px; */
  width: 48px;
  height: 48px;
}
.qiaoqiao-list .avatar img,
.qiaoqiao-view .avatar img {
  width: 100%;
  height: 100%;
  border: 1px solid #ddd;
}
.qiaoqiao-list .name {
  /* position: absolute;
  top: 25px; */
  display: block;
  padding-top:14px;
  color: #14171a;
  font-weight: 700;
}

.qiaoqiao-list p {
  margin: 28px 0 0;
  font-size: 1rem;
  color: #999;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.qiaoqiao-list .pull-right {
  margin: 20px 20px 0 0;
  font-size: 13px;
  float: right !important;
}
.qiaoqiao-list .pull-right div {
  display: inline;
}
.qiaoqiao-list .pull-right .time {
  font-size: small;
  color: #9a9a9a;
  font-weight: 500;
  margin-right: 10px;
}
/*悄悄话详情页*/
.qiaoqiao-view .chat-top {
  position: fixed;
  width: 726px;
  z-index: 1;
  min-height: 35px;
  margin-bottom: 20px;
  padding-bottom: 10px;
  text-align: center;
  background-color: #fff;
  border-bottom: 1px solid #f0f0f0;
}
.chat-top a {
  color: #333;
}
.qiaoqiao-view .back-to-list {
  font-size: 14px;
  color: #969696;
}

.qiaoqiao-view .chat-top .ic-show {
  position: absolute;
  top: 0;
  right: 15px;
}
.qiaoqiao-view .message-l div .content {
  min-height: 39px;
  background-color: #eee;
  border-color: #d9d9d9;
  border-radius: 0 4px 4px 4px;
  position: relative;
  padding: 8px 12px;
  font-size: 14px;
  border: 1px solid;
  word-break: break-word !important;
  line-height: 1.5;
  display: table-cell;
}
.qiaoqiao-view .push-top b {
  display: inline-block;
  padding: 0 140px 0 160px;
  font-size: 14px;
}

.qiaoqiao-view .message-show li div {
  position: relative;
  display: block;
  margin: 4px 56px 0;
  min-height: 39px;
}
.qiaoqiao-view .message-show li div .content {
  position: relative;
  padding: 8px 12px;
  font-size: 14px;
  border: 1px solid;
  word-break: break-word !important;
  word-break: break-all;
  line-height: 1.5;
  display: table-cell;
}
.qiaoqiao-view .message-show .message-l div .content {
  min-height: 39px;
  background-color: #eee;
  border-color: #d9d9d9;
  border-radius: 4px;
}
.qiaoqiao-view .message-show .message-l div:before {
  left: -9px;
  border-left: 9px solid transparent;
  border-top: 16px solid #bad0e9;
}
.qiaoqiao-view .message-show .message-list {
  margin: 0;
  padding: 10px 0 0 0;
}
.qiaoqiao-view .message-show li {
  margin-bottom: 15px;
  overflow: hidden;
}
.qiaoqiao-view .message-show .avatar {
  float: left;
  width: 40px;
  height: 40px;
}
.qiaoqiao-view .message-show .message-r .time {
  float: right;
  margin-right: 56px;
}
.qiaoqiao-view .message-show .message-l .time {
  margin-left: 56px;
}
.qiaoqiao-view .message-show .time {
  margin-top: 2px;
  font-size: 12px;
  color: #9a9a9a;
}

.qiaoqiao-view .message-show .message-r div .content {
  float: right;
  min-height: 39px;
  background-color: #e7f1fc;
  border-color: #3ab6eb41;
  border-radius: 4px;
}
.qiaoqiao-view .message-show .message-r .avatar {
  float: right;
}

.haiwaiicon svg{
  width:20px;
  height:20px;
  fill:#14171A;
}
.dropdown li{
  border: 0;
}
</style>