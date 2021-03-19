<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="0"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-4 d-none d-sm-block">
          <div class="followed-blogger">
            <ul style="margin-bottom:0">
              <li :class="{active:selectID==-1}" @click="selectItem(-1)"><icon-blogger-bg style="height:42;width:42;fill:#39B8EB"></icon-blogger-bg><span class="pl-2">海外名博</span></li>
            </ul>
            <ul v-if="authorList.length>0">
              
              <li :class="{active:selectID==0}" @click="selectItem(0)"><icon-article-bg style="height:42;width:42;fill:#39B8EB"></icon-article-bg><span class="pl-2">全部更新文章</span></li>
              <li 
              v-for="(item,index) in authorList" 
              :key="index" 
              class="d-flex align-items-center"
              :class="{active:selectID==item.followingID}"
               @click="selectItem(item.followingID)">
                <avatar :data="item.userinfo_followingID" :imgHeight="42"></avatar>
                <span class="pl-2">{{item.userinfo_followingID.username}}</span>
                <span class="ml-auto">123</span>
              </li>
            </ul>
            
          </div>
        </div>
        <div class="col-sm-8 col-12">
          <div v-if="articlelists.length>0">
            <div v-if="selectID==0">
              <article-list-item 
                v-for="item in articlelists"
                v-bind:key="item.articleID"
                v-bind:data="item"
                type="0">
              </article-list-item>
            </div>
            <div v-if="selectID>0">
              <index-header :userID="selectID"></index-header>
              <article-list-item 
                v-for="item in articlelists"
                v-bind:key="item.articleID"
                v-bind:data="item"
                type="0">
              </article-list-item>
            </div>
          </div>
          <div v-if="selectID==-1">
            <div class="text-center my-5" v-if="authorList.length==0"> 您还没有关注任何人，看看我们给您推荐的博主吧！</div>
            <bloger-list-item v-for="(item,index) in hotBlobbers" :key="index" :data="item" @opendialog="opendialog()"></bloger-list-item>
          </div>
        </div>
      </div>
    </div>
    <login-dialog ref="dialog"></login-dialog>
  </div>
</template>
<script>
import MainMenu from './components/Main/MainMenu';
import ArticleListItem from './components/Main/ArticleListItem';
import Avatar from  './components/Main/Avatar';
import { IconArticleBg } from '@/components/Icons';
import { IconBloggerBg } from '@/components/Icons';
import blog from '../blog.service';
import IndexHeader from '../user/IndexHeader'
import BlogerListItem from './components/Main/BlogerListItem';
import LoginDialog from '../../user/login/LoginDialog';

export default {
  name: 'index-follows',
  components: {
    MainMenu,
    ArticleListItem,
    Avatar,
    IconArticleBg,
    IconBloggerBg,
    IndexHeader,
    BlogerListItem,
    LoginDialog
  },
  watch:{
    '$store.state.user.userinfo':function(){
      console.log(this.user.userinfo.id);
      if(this.user.userinfo.id)this.getFollowing();
      this.getBloggers();
    }
  },
  created:function(){
    this.getBloggers();
    this.getFollowing();
  },
  methods:{
    selectItem(idx){
      this.selectID=idx;
      this.getArticleList();
    },
    getFollowing(){
      this.user.my_followering_list(0).then(res=>{
        if(res.status){
          this.authorList = res.data;
          this.selectID = this.authorList.length>0?0:-1;
          this.getArticleList();
        }
      });
    },
    async getArticleList(){
      let arr = await this.user.following_article_list(this.selectID);
      this.articlelists = arr.data;
      console.log(this.articlelists)
    },
    async getBloggers(){
      let arr = await blog.hot_blogger();
      this.hotBlobbers = arr.data.data
      console.log(this.hotBlobbers)
    },
    opendialog(){
      this.$refs.dialog.isLogin()
    }
  },
  data() {
    return {
      user:this.$store.state.user,
      selectID:0,
      authorList : [],
      articlelists: [],
      hotBlobbers:[]
    };
  },
};
</script>
<style>

.followed-blogger ul {
  list-style: none;
  padding-left: 0;
}
.followed-blogger li {
  padding:10px;
  position: relative;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
}
.followed-blogger li.active, .followed-blogger li:hover{
  background-color: aliceblue;
}
</style>
