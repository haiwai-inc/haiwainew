<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="1"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-8 col-12">
          <div class="text-center" v-if="articlelists.length==0"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
          </div>
          <article-list-item 
            v-for="item in articlelists"
            v-bind:key="item.articleID"
            v-bind:data="item"
            type="0">
          </article-list-item>
          
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
import MainMenu from './components/Main/MainMenu.vue';
import ArticleListItem from './components/Main/ArticleListItem.vue';
import BlogerList from './components/Main/BlogerList.vue';

import blog from '../blog.service'

export default {
  name: 'blog-index',
  components: {
    MainMenu,
    ArticleListItem,
    BlogerList,
  },
  methods:{
    test(){
      // console.log('hello index vue');
      console.log(blog.recommend_article());
      // blog.message();
    },
  },
  created () {
    blog.recommend_article().then(res=>{
      if(res.data.status){
        this.articlelists=res.data.data;
      }
      console.log(res);
    })
    blog.recommand_blogger().then(res=>{
      if(res.data.status){
        this.res_bloggerList=res.data.data.data;
        this.bloggerList=this.res_bloggerList;
      }
      console.log(this.bloggerList);
    })
  },
  data() {
    return {
      res_bloggerList:[],
      bloggerList : [],
      articlelists: [],
    };
  },
};
</script>
<style></style>
