<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="2"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-8 col-12">
          <main-category-bar 
          v-bind:activeId="currentTagId" 
          v-on:getTagId="gotTagId" 
          v-bind:data="maincategory"></main-category-bar>
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
          <div class="hot-bloger" v-if="bloggerList.length>0">
            <bloger-list v-bind:data="bloggerList" title="热门博主"></bloger-list>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from './components/Main/MainMenu';
import MainCategoryBar from './components/Main/MainCategoryBar';
import ArticleListItem from './components/Main/ArticleListItem';
import BlogerList from './components/Main/BlogerList';
import blog from '../blog.service';

export default {
  name: 'index-hot',
  bodyClass: 'index-page',
  components: {
    MainMenu,
    MainCategoryBar,
    ArticleListItem,
    BlogerList,
  },
  created () {
    blog.hot_tag().then(res=>{
      if(res.data.status){
        this.maincategory=res.data.data.data;
      }
      // console.log(this.maincategory);
    }).catch(err=>{
      console.log(err);
    }),
    blog.recommand_blogger().then(res=>{
      if(res.data.status){
        this.res_bloggerList=res.data.data.data;
        this.bloggerList=this.res_bloggerList;
        this.loading.blogger=false;
      }
      console.log(this.bloggerList);
    })
  },
  methods:{
    gotTagId(id){
      this.currentTagId = id;
      this.lastID.article = 0;
      this.articlelists = [];
      this.loadArticle();
    },
    loadArticle(){
      this.loading.article=true;
      blog.hot_article_list(this.currentTagId,this.lastID.article).then(res=>{
        if(res.data.status){
          let arr = res.data.data;
          this.noMore = arr.length<30 ? true : false;
          this.lastID.article = arr.length===30 ? arr[arr.length-1].postID : this.lastID.article;
          this.articlelists = this.articlelists.concat(arr) ;
          this.loading.article=false;
          console.log(arr,this.lastID.article,this.noMore);
        }
      })
    },
  },
  data() {
    return {
      currentTagId:0,
      noMore:false,
      lastID:{article:0,blogger:0},
      loading:{article:true,blogger:true},
      maincategory:[
        {id:'0',name:'全部',visible:1},
      ],
      res_bloggerList:[],
      bloggerList : [],
      articlelists: [],
    };
  },
};
</script>
<style></style>
