<template>
    <div class="container">
      <!-- <div>
        <main-menu type="-1"></main-menu>
      </div> -->
      <div v-if="$store.state.user.userinfo.bloggerID==0">
          <regist-blog></regist-blog>
      </div>
      <div class="row mt-4" v-if="$store.state.user.userinfo.bloggerID!=0">
        <div class="col-sm-4 d-none d-sm-block">
            <div class="d-flex justify-content-between">
                <h5>我的文集</h5><a href="#">+ 新建</a>
            </div>
            <ul>
                <li class="d-flex justify-content-between collections" v-for="(item,index) in collectionList" :key="index">
                    <span>{{item.name}} ({{item.count_article}})</span>
                    <el-dropdown trigger="click">
                        <span class="el-dropdown-link">
                            <span v-html="iconmore3v"></span>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item icon="el-icon-edit">编辑文集名</el-dropdown-item>
                            <el-dropdown-item v-if="index!=0" icon="el-icon-arrow-up">向上移动</el-dropdown-item>
                            <el-dropdown-item v-if="index<collectionList.length-1" icon="el-icon-arrow-down">向下移动</el-dropdown-item>
                            <el-dropdown-item divided icon="el-icon-delete">删除</el-dropdown-item>
                        </el-dropdown-menu>
                        </el-dropdown>
                </li>
            </ul>
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
            <!-- <div class="collection-list mt-3">
                <collection-list v-bind:data="collectionList" :userdata="false" title="文集"></collection-list>
            </div> -->
        </div>
        <div class="col-sm-8 col-12">
            <index-header :bloggerID="Number($store.state.user.userinfo.bloggerID)"></index-header>

            <div class="profile-header mt-2 mb-3">
            <ul class="nav justify-content-center">
                <li class="col nav-item text-center px-0" v-for="(item,index) in this.tabs" :key="index">
                    <a 
                    class="nav-link" 
                    :class="{active:currentTabId==item.id}" 
                    href="#"
                    @click="changeTab(item.id)">{{item.text}}</a>
                </li>
            </ul>
            </div>
            <div
            v-infinite-scroll="loadArticle"
            infinite-scroll-disabled="noMore"
            infinite-scroll-distance="50">
                <article-list-item 
                v-for="item in articlelists"
                v-bind:key="item.articleID"
                v-bind:data="item"
                type="0">
                </article-list-item>
            </div>
            <div class="text-center py-5" v-if="loading.article"><!-- loader -->
                <i class="now-ui-icons loader_refresh spin"></i>
            </div>
            <p class="text-center py-4" v-if="noMore">没有更多了</p>
        </div>
        
      </div>
    </div>
</template>
<script>
// import MainMenu from '../../pages/components/Main/MainMenu.vue';
import ArticleListItem from '../../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../../pages/components/Main/UserIndexSort.vue';
// import CollectionList from '../../pages/components/Main/CollectionList.vue';
import IndexHeader from '../IndexHeader';
import RegistBlog from '../../../user/login/RegistBlog';
import HaiwaiIcons from "@/components/Icons/Icons";

import blog from '../../blog.service';

export default {
  name: 'blog-user-index',
  components: {
    // MainMenu,
    ArticleListItem,
    // CollectionList,
    IndexHeader,
    RegistBlog,
  },
  created () {
    this.loadArticle(this.currentTabId);
    blog.category_list(this.userID).then(res=>{
        this.collectionList=res.data;
        console.log(res,this.$store.state.user.userinfo);
    })
    
    this.user_login_wxc_to_haiwai(this.token)
    
  },
  methods:{
      changeTab(id){
        this.currentTabId = id;
        this.lastID.article = 0;
        this.articlelists = [];
        this.loadArticle(this.currentTabId);
      },
      loadArticle(id){
        if(id==0){
            blog.article_list_recent(this.userID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
        if(id==1){
            blog.article_list_hot(this.userID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
        if(id==2){
            blog.article_list_comment(this.userID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
      },
      getList(res){
            if(res.status){
                let arr = res.data;
                this.noMore = arr.length<30 ? true : false;
                this.lastID.article = arr.length===30 ? arr[arr.length-1].id : this.lastID.article;
                this.articlelists = this.articlelists.concat(arr) ;
                this.loading.article=false;
                console.log(arr);
            }
      },
      user_login_wxc_to_haiwai(token){
          let user =this.$store.state.user;
          if(this.token && !user.userinfo.id){
            user.user_login_wxc_to_haiwai(token).then(res=>{
                console.log(res);
            })
          }
      }
  },
  data() {
    return {
        iconmore3v: HaiwaiIcons.iconmore3v,
        userID:this.$store.state.user.userinfo.bloggerID,
        token:this.$route.query.haiwai_token,
        currentTabId:0,
        noMore:false,
        lastID:{article:0,wenji:0},
        loading:{article:true,wenji:true},
        tabs:[
            {
                id:0,
                text:'最新博文',
            },{
                id:1,
                text:'最热博文',
            },{
                id:2,
                text:'新评博文',
            }
        ],
        authorInfo : {},
        articlelists: [],
        collectionList:[],
        collectionList0 : [
            {
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "日记",
                visible: 1,
            },{
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "文集1",
                visible: 1,
            },{
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "文集2",
                visible: 1,
            },
        ],
    };
  },
};
</script>
<style>
.profile-header .nav-link{
    color:#657786  
}
.profile-header .nav-link.active {
    color: #1D1D1D;
    border-bottom: 2px solid #39B8EB;
    font-weight: 600;
}
ul{padding: 0;}
.collections{
    padding: 12px;
    list-style-type:none;
}
</style>
