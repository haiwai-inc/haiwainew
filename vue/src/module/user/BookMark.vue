<template>
  <div class="container-md">
    我的收藏
    <article-list-item 
      v-for="item in articlelists"
      v-bind:key="item.articleID"
      v-bind:data="item"
      type="0">
    </article-list-item> 

   <!-- 用户没有收藏任何文章时候默认 -->
   <div class="text-center mt-5">
      <h5>您的文件夹是空的, 您还没有收藏任何文章</h5>
      <p>当您收藏了文章, 它就会出现在这里</p>
    </div>
   <!-- 用户收藏文章后 -->
    <div class="row">
        <div class="col-sm-8 col-12">
          <div
          v-infinite-scroll="loadArticle"
          infinite-scroll-disabled="disabled"
           infinite-scroll-distance="50">
            <article-list-item 
              v-for="item in articlelists"
              v-bind:key="item.id"
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
import ArticleListItem from '../blog/pages/components/Main/ArticleListItem.vue';

export default {
  name: 'bookmark',
  components: {

    ArticleListItem
  },
  created () {
    blog.recommand_blogger().then(res=>{
      if(res.status){
        this.res_bloggerList=res.data.data;
        this.bloggerList=this.res_bloggerList;
        this.loading.blogger=false;
      }
    });
    this.loadArticle();
  },
  data() {
    return {
      noMore:false,
      lastID:{article:0,blogger:0},
      loading:{article:true,blogger:true},
      res_bloggerList:[],
      bloggerList : [],
      articlelists: [],
    };
  },
};
</script>
<style></style>
