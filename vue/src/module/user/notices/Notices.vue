<template>
  <div class="container">
    <div class="d-none d-sm-block">
      <main-menu type="-1"></main-menu>
    </div>
    <div class="row">
      <div class="col-sm-4 left-top-nav">
        <left-nav-item
          v-for="(item, index) in data"
          :key="index"
          :data="item"
          :activeId="activeId"
          v-on:which-active="whichActive"
        ></left-nav-item>
      </div>
      <div class="col-sm-8 col-12">
        <div v-if="activeId === 0">
          <div class="d-flex align-items-center noticeItem" v-for="(item,index) in allNoticeList" :key="index" @click="gotoDetail(item)">
            <avatar :data="item.userinfo_from_userID" :imgHeight="48"></avatar>
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
          <!-- <h6 class="pb-3 font-weight-normal" @click="getlike">我收到的喜欢</h6>
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
import Avatar from "../../blog/pages/components/Main/Avatar";
import NoticeLike from './NoticeLike.vue';
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
 */
export default {
  name: "notices",
  mounted:function(){
    // this.getUnreadCount();
    this.showAllNotice()
  },
  data() {
    return {
      activeId: 0,
      allNoticeList:[],
      data: [
        {
          id: 0,
          title: "全部消息",
          noticeList: [],
          unread: 2,
        },{
          id: 1,
          title: "我收到的评论",
          noticeList: [],
          unread: 2,
        },{
          id: 2,
          title: "我的悄悄话",
          noticeList: [],
          unread: 0,
        },{
          id: 3,
          title: "我的粉丝",
          noticeList: [],
          unread: 3,
        },{
          id: 4,
          title: "我收到的喜欢",
          noticeList: [],
          unread: 6,
        },
      ],
    };
  },
  components: {
    LeftNavItem,
    MainMenu,
    // ArticleListItem,
    Avatar,
    NoticeComment,
    NoticeFollow,
    NoticeQqh,
    NoticeLike,
  },
  computed: {},
  methods: {
    async getUnreadCount(){
      let user = this.$store.state.user;
      let res=await user.notification_unread_count();
      console.log(res);
    },
    whichActive(id) {
      this.activeId = id;
      console.log(this);
    },
    
    async showAllNotice(){
      let v = await this.$store.state.user.notification_list(0);
      if(v.status){
        this.allNoticeList = v.data;
        console.log(this.allNoticeList)
      }
    },
    gotoDetail(e){
      if(e.type=="follower"){
        this.$router.push('blog/user/'+e.from_userID)
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
@media (max-width: 575.98px) {
  .left-top-nav .name svg,
  .left-top-nav .descrip {
    display: none;
  }
  .left-top-nav {
    display: flex;
    justify-content: space-between;
  }
}
</style>
