<template>
  <div class="container">
    <div class="d-none d-sm-block">
      <main-menu type="-1"></main-menu>
    </div>
    <div class="row">
      <div class="col-sm-3 left-top-nav d-none d-sm-block">
        <left-nav-item
          v-for="(item, index) in data"
          :key="index"
          :data="item"
          :activeId="activeId"
          v-on:which-active="whichActive"
        ></left-nav-item>
      </div>
      <div class="d-block d-sm-none p-3">
        消息类型：
        <el-select v-model="activeId" placeholder="请选择" @change="whichActive(activeId)">
            <el-option
            v-for="item in data"
            :key="item.id"
            :label="item.title"
            :value="item.id">
            <span style="float: left">{{ item.title }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">{{ item.unread?item.unread:'' }}</span>
            </el-option>
        </el-select>
      </div>
      <div class="col-sm-9 col-12">
        <div v-if="activeId === 0">
          <div class="d-flex align-items-center noticeItem" v-for="(item,index) in allNoticeList" :key="index" @click="gotoDetail(item)" id="avatar" style="cursor:pointer">
            <div v-if="!item.userinfo_from_userID.avatar" class="avatar-word" :style="{height:'48px',width:'48px',lineHeight:'48px',minWidth:'48px'}">{{item.userinfo_from_userID.first_letter}}</div>
            <img 
            :style="{height:'48px',minWidth:'48px'}"
            v-if="item.userinfo_from_userID.avatar" 
            v-bind:alt="item.userinfo_from_userID.username" 
            class="rounded-circle" 
            v-bind:src="item.userinfo_from_userID.avatar" 
            >
            <!-- <avatar :data="item.userinfo_from_userID" :imgHeight="48"></avatar> -->
            <div class="pl-2">
              <!-- <span class="name">{{item.userinfo_from_userID.username}}</span> -->
              <span class="wrap">{{item.msgbody}}</span>
            </div>

          </div>
        </div>
        <div v-if="activeId === 1">
          <notice-comment></notice-comment>
        </div>
        <div v-if="activeId === 2">
          <notice-qqh></notice-qqh>
          
        </div>
        <div v-if="activeId === 3">
          <notice-follow></notice-follow>
        </div>
        <div v-if="activeId === 4">
          <!-- <h6 class="pb-3 font-weight-normal" @click="getlike">我收到的赞</h6>
          <article-list-item
            v-for="item in articlelists"
            v-bind:key="item.postID"
            v-bind:data="item"
            type="0"
          >
          </article-list-item> -->
          <notice-like></notice-like>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from "../../blog/pages/components/Main/MainMenu.vue";
import LeftNavItem from "../../blog/pages/components/Main/LeftNavItem";
// import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem.vue";
import NoticeComment from "./NoticeComment";
import NoticeFollow from "./NoticeFollow";
import NoticeQqh from "./NoticeQqh";
// import Avatar from "../../blog/pages/components/Main/Avatar";
import NoticeLike from './NoticeLike.vue';
import icons from "@/components/Icons/Icons";
// import {DropDown} from "@/components"
/** 测试数据
  * 用户17登录
  * http://local.haiwainew.com/api/v1/account/passport/login_status/?userID=17
  * 
  * 查看所有未读消息
  * http://local.haiwainew.com/api/v1/account/user/notification_unread_count/
  * 
  * 查看未读的博客评论消息
  * http://local.haiwainew.com/api/v1/account/user/notification_list_comment/
  * 
  * 清空特定类型的消息
  * http://local.haiwainew.com/api/v1/account/user/notification_unread_clear/?type=blog_comment
  * 
 */
export default {
  name: "notices",
  data() {
    return {
      user:this.$store.state.user,
      activeId: 0,
      allNoticeList:[],
      counts:{},
      data: [
        {
          id: 0,
          icon:icons.notice,
          title: this.$t('message').blog.notice_menu_all,
          noticeList: [],
          unread: 0,
        },{
          id: 1,
          icon:icons.message,
          title: this.$t('message').blog.notice_menu_comment,
          noticeList: [],
          unread: 0,
        },{
          id: 2,
          icon:icons.mail,
          title: this.$t('message').blog.notice_menu_qqh,
          noticeList: [],
          unread: 0,
        },{
          id: 3,
          icon:icons.follower,
          title: this.$t('message').blog.notice_menu_funs,
          noticeList: [],
          unread: 0,
        },{
          id: 4,
          icon:icons.like_outline,
          title: this.$t('message').blog.notice_menu_likeme,
          noticeList: [],
          unread: 0,
        },
      ],
    };
  },
  components: {
    LeftNavItem,
    MainMenu,
    // ArticleListItem,
    // Avatar,
    NoticeComment,
    NoticeFollow,
    NoticeQqh,
    NoticeLike,
  },
  watch:{
    "$route.query.id":function(val){
      this.activeId = Number(val);
    }
  },
  mounted:function(){
    // this.getUnreadCount();
    this.allNoticeCount();
    this.showAllNotice();
  },
  created(){
    this.activeId = Number(this.$route.query.id);
  },
  beforeDestroy() {
    this.notification_unread_clear("")
  },
  methods: {
    whichActive(id) {
      this.activeId = id;
      console.log(this);
    },
    async allNoticeCount(){
      let n = await this.user.notification_unread_count();
      if(n.status){
        // "reply":1,"qqh":0,"follow":0,"buzz":0,"totall":1
        this.data[0].unread=n.data.totall;
        // this.data[1].unread=n.data.blog_comment;
        this.data[1].unread=n.data.reply;
        this.data[2].unread=n.data.qqh;
        this.data[3].unread=n.data.follow;
        this.data[4].unread=n.data.buzz;
      }
    },
    async notification_unread_clear(type){
      let n = await this.user.notification_unread_clear(type);
      this.allNoticeCount();
      console.log(n);
    },
    async showAllNotice(){
      let v = await this.user.notification_list(0);
      if(v.status){
        this.allNoticeList = v.data;
        console.log(this.allNoticeList)
      }
    },
    gotoDetail(e){
      if(e.type=="follow"){
        this.activeId=3
      }
      if(e.type=="qqh"){
        this.activeId=2
      }
      if(e.type=="reply")this.activeId=1;
      if(e.type=="buzz")this.activeId=4;
    }
  },
};
</script>
<style>
.noticeItem{
  padding: 1rem;
  border-bottom: aliceblue 1px solid;
}
#avatar .avatar-word{
    border-radius: 50%;
    background-color: aliceblue;
    text-align: center;
    font-weight: 500;
}
@media (max-width: 768px) {
  .left-top-nav .name svg{
    display: none;
  }
  .left-top-nav{
    padding:12px 0;
  }
}
</style>
