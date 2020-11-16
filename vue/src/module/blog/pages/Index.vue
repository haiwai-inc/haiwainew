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
          <div class="hot-bloger">
            <bloger-list v-bind:data="authorList" title="热门博主"></bloger-list>
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
  },
  data() {
    return {
      authorList : [
    {
      avatarUrl:'/img/avatar.jpg',
      isHot:true,
      name:'English Name',
      firstLetter:'用',
      description:'简介简介简介简介 English desciption',
      isFollowed:true,
    },{
      avatarUrl:'/img/eva.jpg',
      isHot:true,
      name:'天煞地煞',
      firstLetter:'天',
      description:'简介简介简介简介哈哈哈简介简介简介简',
      isFollowed:false,
    },{
      avatarUrl:'/img/julie.jpg',
      isHot:true,
      name:'用户名',
      firstLetter:'用',
      description:'简介简介简介简介',
      isFollowed:false,
    },
  ],
      articlelists: [],
    };
  },
};
</script>
<style></style>
