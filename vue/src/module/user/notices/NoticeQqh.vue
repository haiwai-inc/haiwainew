<template>
  <div>
    <!-- 悄悄话列表 -->
    <div class="qiaoqiao-list"  v-if="!showView">
      <h6 class="pb-2 font-weight-normal">
        我的悄悄话
      </h6>
     
      <ul>
        <li v-for="(item,index) in qqhList" :key="index" class="d-flex justify-content-between">
          <div class="d-flex align-items-center" @click="showQqhView(index)">
            
            <img class="rounded-circle" style="width:48px;height:48px"
             :src="item.userID!==loginUser.id?item.userinfo_userID.avatar:item.userinfo_touserID.avatar"
             v-if="item.userID!==loginUser.id?item.userinfo_userID.avatar!='':item.userinfo_touserID.avatar!=''"
              />
              <div 
              v-if="item.userID!==loginUser.id?item.userinfo_userID.avatar=='':item.userinfo_touserID.avatar==''" 
              class="first_letter">{{item.userID===loginUser.id?item.userinfo_userID.first_letter.toUpperCase():item.userinfo_touserID.first_letter.toUpperCase()}}</div>
            <div class="pl-2">
              <span class="name">{{item.userinfo_userID.id!==loginUser.id?item.userinfo_userID.username:item.userinfo_touserID.username}}</span>
              <span class="wrap">{{item.last_messageinfo.msgbody}}</span>
            </div>

          </div>
          <div class="pull-right dropdown">
            <drop-down
            class="nav-item dropdown"
            :haiwaiIcon="iconmore3v"
            haiwaiClass="haiwaiicon"
            style="padding:0;"
            >
              <el-popconfirm 
                placement="top-end"
                confirm-button-text='删除'
                cancel-button-text='取消'
                title="确定删除这组悄悄话吗？"
                :hide-icon="true"
                @confirm="qqh_delete(item)"
              >
                <a href="javascript:void(0)" slot="reference" class="dropdown-item" style="color:gray;">删除会话</a>
              </el-popconfirm>
              
              <a class="dropdown-item"  href="javascript:void(0)" @click="blockUser(item.userinfo_userID.id!==loginUser.id?item.userinfo_userID.id:item.userinfo_touserID.id)">加入黑名单</a>

              <el-popover 
              placement="bottom-start"
              width="375" 
              :ref="'report0-'+item.postID"
              trigger="click">
                <div>我要举报 <b>{{item.userinfo_userID.id!==loginUser.id?item.userinfo_userID.username:item.userinfo_touserID.username}}</b><span v-if="report_status" class="text-success ml-3">举报成功</span></div>
                <textarea  type="textarea" v-model="reportmsgbody" rows="3" class="w-100 my-2 p-2" placeholder="写下您的举报原因..." @keyup="checkstatus(1)" maxlength="400"></textarea>
                <n-button 
                type="primary"
                round 
                simple
                :disabled="replybtndisable"
                @click="report('0',item)"
                  >举报</n-button>
                <a class="dropdown-item" href="javascript:void(0)" slot="reference" style="color:gray"><span>举报</span></a>
              </el-popover>
            </drop-down>
            
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
            <a href="#" @click="$router.push('/blog/user/'+touser.id)">{{touser.username}}</a>
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
            <a class="dropdown-item"  href="javascript:void(0)" @click="blockUser(touser.id)">加入黑名单</a>
          </drop-down>
        </div>
      </div>
      <div class="message-show">
        <ul class="message-list">
          <li 
          v-for="(item,index) in qqhView.data" 
          :key="index"
          :class="{'message-l':item.userID!==loginUser.id,'message-r':item.userID===loginUser.id,}" >
            <a href="#" class="avatar">
              <img class="rounded-circle" 
              :src="item.userID===loginUser.id?loginUser.avatar:touser.avatar"
              v-if="item.userID===loginUser.id?loginUser.avatar!='':touser.avatar!=''"
              />
              <div v-if="item.userID===loginUser.id?loginUser.avatar=='':touser.avatar==''" class="first_letter">{{item.userID===loginUser.id?loginUser.first_letter.toUpperCase():touser.first_letter.toUpperCase()}}</div>
            </a>
            <div><span class="content">{{item.msgbody}}</span></div>
            <span class="time">{{item.dateline*1000 | formatDate}}</span>
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
          @click.native="sendQqh(touser.id)"
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
// import Avatar from "../../blog/pages/components/Main/Avatar";
import HaiwaiIcons from '@/components/Icons/Icons';
import {formatDate} from '@/directives/formatDate.js';
export default {
  name: 'notice-qqh',
  components: {
    [Button.name]: Button,
    [Input.name]: Input,
    DropDown,
    LeftArrow, 
    // Avatar,
  },
  data(){
      return{
        loginUser:{},
        touser:{},
        msgID:0,
        iconmore3v:HaiwaiIcons.iconmore3v,
        showView:false,
        qqhList:{},
        qqhView:{},
        msgbody:'',
        user:this.$store.state.user,
        report_status:false,
        replybtndisable:false,
        reportmsgbody:''
      }
  },
  created: function () {
    this.getUserInfo();
    this.qqh_list();
  },
  methods:{
    async getUserInfo(){//获取登录用户信息
      // let user = this.$store.state.user;
      let res = await this.user.getUserStatus();
      this.loginUser = res.data;
      console.log(this.loginUser);
    },

    async qqh_list() {
      // let user = this.$store.state.user;
      let res = await this.user.qqh_list();
      this.qqhList=res.data;console.log(this.qqhList)
      this.qqhList.sort((a,b)=>{//按照最后信息时间倒序排列悄悄话列表
        let aTime = a.last_messageinfo.dateline;
        let bTime = b.last_messageinfo.dateline;
        return bTime - aTime
      });
    },

    async qqh_view(idx) {
      let list = this.qqhList;
      if(list[idx].userinfo_userID.id===this.loginUser.id){
        this.touser=list[idx].userinfo_touserID;
        this.loginUser=list[idx].userinfo_userID;
      }else{
        this.touser=list[idx].userinfo_userID;
        this.loginUser=list[idx].userinfo_touserID;
      }
      let user = this.$store.state.user;
      let res = await user.qqh_view(list[idx].id);
      this.qqhView=res;console.log(res.data)
      this.qqhView.data=res.data.reverse();//对话倒序排列
    },

    showQqhView(idx){
      this.msgID=idx;
      this.qqh_view(idx);
      this.showView=true;
    },

    sendQqh(id){
      this.send(this.loginUser.id,id,this.msgbody);
    },

    async send(userID,touserID,msgbody) {
      // let user = this.$store.state.user;
      let res = await this.user.sendQqh(userID,touserID,msgbody);
      this.showQqhView(this.msgID);
      this.msgbody='';
      console.log(res);
    },
    // 删除悄悄话
    qqh_delete(item){
      this.$store.state.user.qqh_delete(item.id).then(res=>{
        if(res.status){
          this.qqh_list();
        }
      })
    },
    //拉黑
    blockUser(id){
      this.$store.state.user.blacklist_add(id).then(res=>{
        console.log(res)
        if(res.status){
          this.$message({message:'已加黑名单',type:'success'})
        }
      })
    },
    //举报
    report(n,data){
      let pop = 'report'+n+'-'+data.postID;
      let id = data.userinfo_userID.id!==this.loginUser.id?data.userinfo_userID.id:data.userinfo_touserID.id;
      this.$store.state.user.report_add(id,this.reportmsgbody).then(res=>{
        console.log(res.status);
        this.report_status = true;
        setTimeout(()=>{
          this.report_status = false;
          this.$refs[`${pop}`].doClose();
        },2000)
      })
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
  padding:8px 20px 0 0;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
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
.qiaoqiao-list .avatar img{
  float: left;
  /* margin: 20px 10px 20px 5px; */
  width: 48px;
  height: 48px;
}

.qiaoqiao-view .avatar img {
  width: 100%;
  height: 100%;
  border: 1px solid #ddd;
}
.qiaoqiao-list .name {
  display: block;
  padding-top:10px;
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
.qiaoqiao-view .first_letter,.qiaoqiao-list .first_letter{
  color:#14171a;
  border-radius: 50%;
  height:48px;
  width: 48px;
  margin: 0 !important;
  text-align: center;
  line-height: 48px;
  display: block;
  background-color: aliceblue;
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