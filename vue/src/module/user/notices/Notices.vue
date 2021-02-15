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
        <span v-if="activeId === 0">
          <notice-comment></notice-comment>
        </span>
        <span v-if="activeId === 1">
          <notice-qqh></notice-qqh>
          
        </span>
        <span v-if="activeId === 2">
          <notice-follow></notice-follow>
        </span>
        <span v-if="activeId === 3">
          <h6 class="pb-3 font-weight-normal" @click="getlike">我收到的喜欢</h6>
          <article-list-item
            v-for="item in articlelists"
            v-bind:key="item.postID"
            v-bind:data="item"
            type="0"
          >
          </article-list-item>
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from "../../blog/pages/components/Main/MainMenu.vue";
import LeftNavItem from "../../blog/pages/components/Main/LeftNavItem";
import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem.vue";
import NoticeComment from "./NoticeComment";
import NoticeFollow from "./NoticeFollow";
import NoticeQqh from "./NoticeQqh";
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
    this.getlike()
  },
  data() {
    return {
      activeId: 0,
      hideArrow:true,
      authorInfor: {
        avatarUrl: "/img/julie.jpg",
        isHot: true,
        authorHomepage: "",
        name: "用户名",
        firstLetter: "用",
        description: "简介简介简介简介",
        isFollowed: true,
      },
      data: [
        {
          id: 0,
          title: "我收到的评论",
          noticeList: [],
          unread: 2,
        },
        {
          id: 1,
          title: "我的悄悄话",
          noticeList: [],
          unread: 0,
        },
        {
          id: 2,
          title: "我的粉丝",
          noticeList: [],
          unread: 3,
        },
        {
          id: 3,
          title: "我收到的喜欢",
          noticeList: [],
          unread: 6,
        },
      ],
      articlelists: [
        {
          articleID: "345678",
          articleUrl: "",
          title: "这里是标题....",
          description:
            "这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
          author: "这里是作者",
          authorID: "123456789",
          isHot: true,
          read: "3456",
          commont: "12",
          likes: "23",
          image: "/img/bg3.jpg",
        },
        {
          articleID: "34567",
          articleUrl: "",
          title: "这里是标题....",
          description:
            "这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
          author: "这里是作者",
          authorID: "123456",
          isHot: false,
          read: "3456",
          commont: "12",
          likes: "23",
          image: "/img/bg4.jpg",
        },
        {
          articleID: "3456",
          articleUrl: "",
          title: "这里是标题....",
          description:
            "这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
          author: "这里是作者",
          authorID: "12345",
          isHot: true,
          read: "3456",
          commont: "12",
          likes: "23",
          image: "/img/bg1.jpg",
        },
        {
          articleID: "345",
          articleUrl: "",
          title: "这里是标题....",
          description:
            "这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
          author: "这里是作者",
          authorID: "123456789",
          isHot: false,
          read: "3456",
          commont: "12",
          likes: "23",
          image: "",
        },
        {
          articleID: "3456789",
          articleUrl: "",
          title: "这里是标题....",
          description:
            "这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
          author: "这里是作者",
          authorID: "123456789",
          isHot: true,
          read: "3456",
          commont: "12",
          likes: "23",
          image: "",
        },
      ],
    };
  },
  components: {
    LeftNavItem,
    MainMenu,
    ArticleListItem,
//     DropDown,
    NoticeComment,
    NoticeFollow,
    NoticeQqh,
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
    async getlike(){
      let v = await this.$store.state.user.my_buzz_article_list(0);
      if(v.status){
        this.articlelists = v.data
      }
      console.log(this.articlelists)
    }
  },
};
</script>
<style>

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
