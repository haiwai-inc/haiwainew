<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="0"></main-menu>
      </div>
      <div class="row">
        <div class="d-block d-sm-none w-100 p-3 d-flex" v-if="authorList.length>0">
          <!-- <el-select v-if="authorList.length>0" v-model="selectID" placeholder="请选择" @change="changeID(selectID)">
            <el-option :key="-1" :label="'请选择'" :value='-1'></el-option>
            <el-option :key='0' :label="'全部更新文章'" :value='0'>
            </el-option>
            <el-option
            v-for="(item,index) in authorList"
            :key="item.id"
            :label="item.userinfo_followingID.username"
            :value="index+1">
            <span style="float: left;color:black">{{ item.userinfo_followingID.username }} <span class="text-muted">的文章</span></span>
            <div class="noticealert mr-auto" v-if="item.follower_update < item.following_update"></div>
            </el-option>
          </el-select> -->
          <div class="flex-grow-1 mobile-avatar" style="overflow-x:auto;overflow-y:hidden">
            <ul style="white-space:nowrap;display:block;overflow:auto;padding-inline-start:0px">
              <li v-for="item in authorList" 
              style="list-style:none;display:inline-block;margin-left:10px;padding-top:6px;vertical-align:top"
              :key="item.id" @click="$router.push('/blog/user/'+item.bloggerID)">
                <!-- <avatar :data="item.userinfo_followingID" :imgHeight="42"></avatar> -->
                <el-avatar v-if="item.userinfo_followingID.avatar" :src="item.userinfo_followingID.avatar" :size="50"></el-avatar>
                <el-avatar v-if="!item.userinfo_followingID.avatar" :size="50">{{item.userinfo_followingID.first_letter}}</el-avatar>
                 <icon-V class="text-primary lable" v-if="item.userinfo_followingID.is_hot_blogger"></icon-V>
              </li>
            </ul>
          </div>
          <el-button v-if="authorList.length>0" circle @click="changeID(-1)" class="ml-3"><i class="el-icon-plus"></i><br><span style="font-size:8px">添加关注</span></el-button>
        </div>
        <div class="col-sm-3 d-none d-sm-block">
          <div class="followed-blogger">
            <ul style="margin-bottom:0">
              <li :class="{active:selectItem.followingID==-1}" @click="selected({followingID:-1})"><icon-blogger-bg style="height:42;width:42;fill:#39B8EB"></icon-blogger-bg><span class="pl-2">添加关注</span></li>
            </ul>
            <ul v-if="authorList.length>0">
              
              <li :class="{active:selectItem.followingID==0}" @click="selected({followingID:0})"><icon-article-bg style="height:42;width:42;fill:#39B8EB"></icon-article-bg><span class="pl-2">全部更新文章</span></li>
              <li 
              v-for="(item,index) in authorList" 
              :key="index" 
              class="d-flex align-items-center"
              :class="{active:selectItem.followingID==item.followingID}"
               @click="selected(item)">
                <avatar :data="item.userinfo_followingID" :imgHeight="42"></avatar>
                 <icon-V class="text-primary lable" v-if="item.userinfo_followingID.is_hot_blogger"></icon-V>
                <span class="pl-2 ">{{item.userinfo_followingID.username}}</span>
                <div class="noticealert mr-auto" v-if="item.follower_update < item.following_update"></div>
              </li>
            </ul>
            
          </div>
        </div>
        <div class="col-sm-9 col-12">
          <div v-show="selectItem.followingID!=-1 && articlelists.length>0">
            <index-header v-show="selectItem.followingID>0" :bloggerID="selectItem.bloggerID"></index-header>
            <div class="scrollbox"
              v-infinite-scroll="getArticleList"
              infinite-scroll-disabled="disabled"
              infinite-scroll-distance="50">
              <article-list-item 
                v-for="item in articlelists"
                v-bind:key="item.articleID"
                v-bind:data="item"
                type="0">
              </article-list-item>
            </div>
           
            <div class="text-center py-5" v-show="loading.article"><!-- loader -->
              <i class="now-ui-icons loader_refresh spin"></i>
            </div>
            <p class="text-center py-4" v-show="noMore">没有更多了</p>
          </div>
          <div v-show="selectItem.followingID==-1">
            <div class="text-center my-5" v-show="authorList.length==0"> 您还没有关注任何人，看看我们给您推荐的博主吧！</div>
            <bloger-list-item v-for="(item,index) in hotBlobbers" :key="index" :data="item"></bloger-list-item>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
    IconV
} from '@/components/Icons';
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
    IconV
  },
  watch:{
    // '$store.state.user.userinfo':function(){
    //   console.log(this.user.userinfo.id);
    //   if(this.user.userinfo.id)this.getFollowing();
    //   this.getBloggers();
    // }
  },
  created:function(){
    this.getBloggers();
    this.getFollowing();
  },
  computed:{
    disabled () {
      return this.loading.article || this.noMore
    }
  },
  methods:{
    changeID(id){
      if(id>0){
        this.selectItem = this.authorList[id-1]
      }else{
        this.selectItem = {followingID:id};
        this.selectID = id;
      }
    },
    selected(item){console.log(item);
      this.selectItem=item;
      this.lastID.article = 0;
      this.articlelists = [];
      this.getArticleList();
    },
    getFollowing(){
      this.user.my_followering_list(0).then(res=>{
        if(res.status){
          this.authorList = res.data;console.log(this.authorList)
          this.selectItem.followingID = this.authorList.length>0?0:-1;
          this.getArticleList();
        }
      });
    },
    async getArticleList(){
      this.loading.article=true;
      let res = await this.user.following_article_list(this.selectItem.followingID,this.lastID.article);
      let arr = res.data;
      if (res.status){
        this.noMore = arr.length<30 ? true : false;
        this.lastID.article = arr.length===30 ? arr[arr.length-1].postID : this.lastID.article;
        this.articlelists = this.articlelists.concat(arr) ;
      }
      this.loading.article=false;
      console.log(this.articlelists);
    },
    async getBloggers(){
      let arr = await blog.hot_blogger();
      this.hotBlobbers = arr.data;
    },
  },
  data() {
    return {
      user:this.$store.state.user,
      selectItem:{followingID:-1},
      authorList : [],
      articlelists: [],
      hotBlobbers:[],
      loading:{article:true,blogger:true},
      lastID:{article:0,blogger:0},
      noMore:false,
      selectID:0
    };
  },
};
</script>
<style>
.noticealert{ 
  position: absolute;
  right: 16px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #FF3636;
}
.followed-blogger ul {
  list-style: none;
  padding-left: 0;
}
.followed-blogger li {
  padding:10px;
  position: relative;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  font-size: 17px;
  color: #425466
}
.followed-blogger svg.text-primary.lable {
  position: absolute;
  margin-left: 25px;
  margin-top: -46px;
  transform: rotate(34deg)
}
.mobile-avatar{
  margin: 0;
  height:60px;
  box-shadow: -8px 0px 5px -5px #ececec inset;
}
.mobile-avatar svg {
  /* position: absolute; */
  margin-left: -18px;
  margin-top: -86px;
  transform: rotate(34deg)
}
.followed-blogger li.active, .followed-blogger li:hover{
  background-color: aliceblue;
}

</style>
