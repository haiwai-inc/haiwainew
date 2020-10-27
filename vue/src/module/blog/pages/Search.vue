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
          v-on:whichActive="whichActive"
        ></left-nav-item>
        <div class="d-flex justify-content-between mt-1 p-3"><span class="text-muted">最近搜索</span><span>清空</span></div>
        <div class="mt-3 px-3">孙悟空的文集</div>
        <div class="mt-2 px-3">川普</div>
      </div>
      <div class="col-sm-8 col-12">
        <span v-if="activeId === 0">
          <article-list-item
            v-for="item in articlelists"
            v-bind:key="item.articleID"
            v-bind:data="item"
            type="0"
          >
          </article-list-item>
        </span>
        <span v-if="activeId === 1">
          用户
        </span>
        <span v-if="activeId === 2">
          专题
        </span>
        <span v-if="activeId === 3">
          文集
          
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from "./components/Main/MainMenu.vue";
import LeftNavItem from "./components/Main/LeftNavItem";
import ArticleListItem from "./components/Main/ArticleListItem.vue";
// import Avatar from "./components/Main/Avatar";
// import NoticeComment from "./NoticeComment";
// import NoticeFollow from "./NoticeFollow";
// import { LeftArrow, IconMore3v } from "@/components/Icons";
// import {DropDown} from "@/components"
export default {
  name: "search",
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
          title: "文章",
          noticeList: [],
          unread: 2,
        },
        {
          id: 1,
          title: "用户",
          noticeList: [],
          unread: 0,
        },
        {
          id: 2,
          title: "专题",
          noticeList: [],
          unread: 3,
        },
        {
          id: 3,
          title: "文集",
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
    // Avatar,
//     DropDown,
    // NoticeComment,
    // NoticeFollow,
    // LeftArrow,
    // IconMore3v,
  },
  computed: {},
  methods: {
    whichActive(id) {
      this.activeId = id;
      console.log(this);
    },
  },
};
</script>
<style>
/*悄悄话列表页*/
.qiaoqiao-list .menu {
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 700;
}
.qiaoqiao-list .dropdown-toggle::after {
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
  margin: 20px 10px 20px 5px;
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
  position: absolute;
  top: 4px;
  left: 0;
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
  background-color: #e7f1fc;
  border-color: #bad0e9;
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

.qiaoqiao-view .message-show {
  padding-top: 46px;
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
  background-color: #e7f1fc;
  border-color: #3ab7eb1a;
  border-radius: 4px;
}
qiao-view .message-show .message-l div:before {
  left: -9px;
  border-left: 9px solid transparent;
  border-top: 16px solid #bad0e9;
}
.qiaoqiao-view .message-show .message-list {
  margin: 0;
  padding: 10px 0 110px;
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

.qiaoqiao-view .message-show .time {
  margin-top: 2px;
  font-size: 12px;
  color: #9a9a9a;
}

.qiaoqiao-view .message-show .message-r div .content {
  float: right;
  min-height: 39px;
  background-color: #eee;
  border-color: #d9d9d9;
  border-radius: 4px;
}
.qiaoqiao-view .message-show .message-r .avatar {
  float: right;
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

