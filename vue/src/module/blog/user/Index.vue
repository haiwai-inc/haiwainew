<template>
    <div class="container">
      <div>
        <main-menu type="-1"></main-menu>
      </div>
      <div class="row">
       <div class="col-sm-8 col-12">
            <index-header></index-header>

            <div class="profile-header mt-2">
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
        <div class="col-sm-4 d-none d-sm-block">
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
          <div class="collection-list mt-3">
            <collection-list v-bind:data="collectionList" title="文集"></collection-list>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import MainMenu from '../pages/components/Main/MainMenu.vue';
import ArticleListItem from '../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../pages/components/Main/UserIndexSort.vue';
import CollectionList from '../pages/components/Main/CollectionList.vue';
import IndexHeader from './IndexHeader';

import blog from '../blog.service';

export default {
  name: 'blog-user-index',
  components: {
    MainMenu,
    ArticleListItem,
    CollectionList,
    IndexHeader
  },
  created () {
    this.loadArticle(this.currentTabId);
    blog.category_list(this.userID).then(res=>{
        this.collectionList=res.data;
        console.log(res);
    })
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
                // console.log(arr,this.lastID,this.noMore);
            }
      }
  },
  data() {
    return {
        userID:this.$route.params.id,
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
        collectionList : [],
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
</style>
