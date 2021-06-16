<template>
  <div class="container">
    <div class="d-none d-sm-block">
      <main-menu type="-1"></main-menu>
    </div>
    <div class="row">
      <div class="col-sm-3 left-top-nav">
        <left-nav-item
          v-for="(item, index) in tabdata"
          :key="index"
          :data="item"
          :activeId="activeId"
          v-on:which-active="whichActive"
        ></left-nav-item>
        <!-- <div class="d-flex justify-content-between mt-1 p-3"><span class="text-muted">最近搜索</span><span>清空</span></div>
        <div class="mt-3 px-3">孙悟空的文集</div>
        <div class="mt-2 px-3">川普</div> -->
      </div>
      <div class="col-sm-9 col-12">
        <!-- 文章 -->
        <div v-if="activeId === 0">
          <span v-if="search.article.data.length==0">在搜索框中输入一些内容，你会发现更多精彩内容。</span>
          <div v-show="search.article.data.length>0">
            <article-list-item
              v-for="(item,index) in search.article.data"
              v-bind:key="index"
              v-bind:data="item"
              type="0"
            >
            </article-list-item>
            <p class="text-center py-4" v-if="!noMore.article && !loading.article">
                <el-button type="primary" plain @click="infiniteGet" round>加载更多搜索结果</el-button>
            </p>
            <p class="text-center py-4" v-if="noMore.article">没有更多了</p>
          </div>
          <div class="text-center py-5" v-if="loading.article"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
          </div>
        </div>
        <!-- 用户 -->
        <div v-if="activeId === 1">
          <span v-if="search.blogger.data.length==0">在搜索框中输入一些内容，你会发现更多精彩内容。</span>
          <div v-if="search.blogger.data.length>0">
            <bloger-list-item 
            v-for="(item,index) in search.blogger.data" 
            v-bind:key="index" 
            :data="item" :usertype="'search'"></bloger-list-item>
            <p class="text-center py-4" v-if="!noMore.blogger && !loading.blogger">
                <el-button type="primary" plain @click="infiniteGet" round>加载更多搜索结果</el-button>
            </p>
            <p class="text-center py-4" v-if="noMore.blogger">没有更多了</p>
          </div>
          <div class="text-center py-5" v-if="loading.blogger"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
          </div>
        </div>
        <!-- 标签 -->
        <div v-if="activeId === 2">
          <div class="mb-4" v-if="search.tag.data.length!==0">
            <b>相关标签</b><div class="w-100"></div>
            <el-button class="search_tag"
            size="mini" 
            round 
            v-for="(item,index) in search.tag.data" 
            :key="index"
            :id="item.id"
            @click="tagChange(item.id)">{{item.name}}</el-button>
          </div>
          <span v-if="search.tag_articles.data.length==0">在搜索框中输入一些内容，你会发现更多精彩内容。</span>
          <div v-if="search.tag_articles.data.length>0"
          v-infinite-scroll="infiniteGet"
          infinite-scroll-disabled="disabled"
          infinite-scroll-distance="50">
            <article-list-item
              v-for="item in search.tag_articles.data"
              v-bind:key="item.postID"
              v-bind:data="item"
              type="0"
            >
            </article-list-item>
          </div>
          <div class="text-center py-5" v-if="loading.tag_articles"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
          </div>
          <p class="text-center py-4" v-if="noMore.tag_articles">没有更多了</p>
        </div>
        <!-- 文集 -->
        <div v-if="activeId === 3">
          <span v-if="search.categories.data.length==0">在搜索框中输入一些内容，你会发现更多精彩内容。</span>
          <div v-if="search.categories.data.length>0"
          v-infinite-scroll="infiniteGet"
          infinite-scroll-disabled="disabled"
          infinite-scroll-distance="50">
            <div style="padding:10px 0;border-bottom:#eee 1px solid" v-for="(item,index) in search.categories.data" :key="index">
              <div style="font-weight:700;font-size:1.3rem;padding-bottom:5px" v-html="item.name"></div>
              <div class="d-flex"><avatar :data="item.userinfo_userID" :imgHeight="18" class="mr-2"></avatar>
              <span style="margin-top:2px">{{item.userinfo_userID.username}}</span></div>
              <span style="color:gray;font-size:0.85rem">{{item.bloggerinfo_bloggerID.count_article}} 篇文章，博客访问：{{item.bloggerinfo_bloggerID.count_read}}</span> 
            </div>
          </div>

          <div class="text-center py-5" v-if="loading.categories"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
          </div>
          <p class="text-center py-4" v-if="noMore.categories">没有更多了</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from "../../blog/pages/components/Main/MainMenu.vue";
import LeftNavItem from "../../blog/pages/components/Main/LeftNavItem";
import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem.vue";
import BlogerListItem from "../../blog/pages/components/Main/BlogerListItem.vue"
import Avatar from "../../blog/pages/components/Main/Avatar";
import { Button, } from 'element-ui';
import icons from "@/components/Icons/Icons";

export default {
  name: "search",
  data() {
    return {
      search:this.$store.state.search,
      activeId: 0,
      hideArrow:true,
      keyword:'',
      tagres:[],
      tags:[],
      lastScore:{article:0,blogger:0,tag_articles:0,categories:0},
      loading:{article:false,blogger:false,tag_articles:false,categories:false},
      noMore:{article:false,blogger:false,tag_articles:false,categories:false},
      tabdata: [
        {
          id: 0,
          icon:icons.article,
          title: "文章",
          noticeList: [],
          unread: "",
        },
        {
          id: 1,
          icon:icons.blog,
          title: "博客",
          noticeList: [],
          unread: "",
        },
        {
          id: 2,
          icon:icons.tag,
          title: "标签",
          noticeList: [],
          unread: "",
        },
        {
          id: 3,
          icon:icons.collection,
          title: "目录",
          noticeList: [],
          unread: "",
        },
      ],
    };
  },
  components: {
    LeftNavItem,
    MainMenu,
    ArticleListItem,
    BlogerListItem,
    Avatar,
    [Button.name]:Button,
//     DropDown,
    // NoticeComment,
    // NoticeFollow,
    // LeftArrow,
    // IconMore3v,
  },
  computed:{
    disabled () {
      let status = this.activeId==0 ? this.loading.article || this.noMore.article:
      this.activeId==1 ? this.loading.blogger || this.noMore.blogger:
      this.activeId==2 ? this.loading.tag_articles || this.noMore.tag_articles:
      this.loading.categories || this.noMore.categories;
      console.log(status);
      return status;
    }
  },
  methods: {
    whichActive(id) {
      this.activeId = id;
      this.$router.push({path:'/search/',query:{keyword:this.$route.query.keyword,tab:id}});
    },
    tagChange(id){
      let idx = this.tags.indexOf(id);
      let obj = document.getElementById(id);
      if( idx === -1 ){
        this.tags.push(id);
        obj.classList.add("activeTag");
      }else{
        this.tags.splice(idx,1);
        obj.classList.remove("activeTag");
      }
      this.search.tag_articles.data = [];
      this.get_tags_articles(this.tags.length==0?this.tagres:this.tags,0);
    },
    async doSearch(k){
      this.search.article.data=this.search.blogger.data=this.search.tag_articles.data=this.search.categories.data=[];
      this.get_articles(k,0);
      this.get_bloggers(k,0,'all',0);
      this.get_tags(k);
      this.get_categories(k,0);
    },
    nomoreStatus(rl){
      return rl<30?true:false
    },
    infiniteGet(){
      this.loading.article = true;
      if (this.activeId==0) {
        this.get_articles(this.keyword,this.lastScore.article);
      } 
      if (this.activeId==1){
        this.get_bloggers(this.keyword,this.lastScore.blogger,'all',0);
      }
      if(this.activeId==2){
        this.get_all_tag_articles()
      }
      if(this.activeId==3){
        this.get_categories(this.keyword,this.lastScore.categories);
      }
    },
    async get_articles(k,lastScore){
      // this.loading.article = true;
      // if (lastScore==0){
      //   this.search.article = await this.search.search_articles(k,lastScore);
      //   let rl = this.search.article.data;
      //   this.lastScore.article = rl.length<30?rl[rl.length-1]._score:this.lastScore.article;
      //   this.noMore.article = this.nomoreStatus(rl.length);
      // }else{
        let r = await this.search.search_articles(k,lastScore);
        let arr = r.data;
        // let data = this.search.article.data;
        this.lastScore.article = arr.length<30?this.lastScore.article:arr[arr.length-1]._score;
        this.noMore.article = this.nomoreStatus(arr.length);
        this.search.article.data = this.search.article.data.concat(arr);
      // }
      this.loading.article = false;
    },
    async get_bloggers(k,lastScore,type,w){
      // this.loading.blogger = true;
      // if(lastScore==0){
      //   this.search.blogger = await this.search.search_bloggers(k,lastScore,type,w);
      //   let rl = this.search.blogger.data;
      //   this.lastScore.blogger = rl.length>0?rl[rl.length-1]._score:0;
      //   this.noMore.blogger = this.nomoreStatus(rl.length);
      // }else{
        let r = await this.search.search_bloggers(k,lastScore,type,w);
        let arr = r.data;
        this.lastScore.blogger = arr.length<30?this.lastScore.blogger:arr[arr.length-1]._score;
        this.noMore.blogger = this.nomoreStatus(arr.length);
        this.search.blogger.data = this.search.blogger.data.concat(arr);
      // }
      this.loading.blogger = false;
    },
    async get_tags(k){
      this.search.tag = await this.search.get_tags(k);
      this.get_all_tag_articles();
    },
    async get_all_tag_articles(){
      this.search.tag.data.forEach(t=>{
        this.tagres.push(t.id)
      });
      this.get_tags_articles(this.tagres,this.lastScore.tag_articles);
    },
    async get_tags_articles(tags,lastScore){
      // if (lastScore==0){
      //   this.search.tag_articles = await this.search.search_tag_articles(tags,lastScore);
      //   let rl = this.search.tag_articles.data;
      //   this.lastScore.tag_articles = rl.length>0?rl[rl.length-1]._score:0;
      //   this.noMore.tag_articles = this.nomoreStatus(rl.length);
      // }else{
        let r = await this.search.search_tag_articles(tags,lastScore);
        let arr = r.data;
        this.search.tag_articles.data = this.search.tag_articles.data.concat(arr);
        this.lastScore.tag_articles = arr.length<30?this.lastScore.tag_articles:arr[arr.length-1]._score;
        this.noMore.tag_articles = this.nomoreStatus(arr.length);
      // }
      console.log(tags,lastScore);
    },
    async get_categories(k,lastScore){
      // this.search.categories = await this.search.search_categories(k,lastScore);
      // if(lastScore==0){
      //   this.search.categories = await this.search.search_categories(k,lastScore);
      //   let rl = this.search.categories.data;
      //   this.lastScore.categories = rl.length>0?rl[rl.length-1]._score:0;
      //   this.noMore.categories = this.nomoreStatus(rl.length);
      // }else{
        let r = await this.search.search_categories(k,lastScore);
        let arr = r.data;
        this.search.categories.data = this.search.blogger.data.concat(arr);
        this.lastScore.categories = arr.length<30?this.lastScore.categories:arr[arr.length-1]._score;
        this.noMore.categories = this.nomoreStatus(arr.length);
      // }
      this.loading.categories = false;
    },
  },
  created() {
    this.keyword = this.$route.query.keyword;
    this.activeId = Number(this.$route.query.tab);
    this.doSearch(this.keyword);
  },
  watch:{
    $route(){
      this.keyword = this.$route.query.keyword;
      this.activeId = Number(this.$route.query.tab);
      this.doSearch(this.keyword);
    }
  }
};
</script>
<style>
.activeTag{
  border:1px #39B8EB solid !important;
  color: #39B8EB !important;
  background-color: #39b9eb15 !important;
}
.search_tag{
  margin: 5px 10px 5px 0 !important;
  outline:none !important;
}
.search_tag.el-button:focus{
  background: #FFF;
    border: 1px solid #DCDFE6;
    color: #606266;
}
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

