<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="3"></main-menu>
      </div>
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
        <div class="col-sm-4 d-none d-sm-block">
          <div class="hot-bloger" v-if="bloggerList.length>0">
            <bloger-list v-bind:data="bloggerList" :title="$t('message').blog.index_title_hot"></bloger-list>
          </div>
          <!-- help -->
            <div class="box my-3 pl-3 bolger-box sticky-top">
              <span v-html="icons.helpcenter"></span>
              <span class="ml-3 mb-3 h6">帮助中心</span>
              <div class=row>
                <div class="col-6 mt-3 text-secondary">如何上传图片</div>
                <div class="col-6 mt-3  text-secondary">怎样发视频</div>
                <div class="col-12 mt-3 text-secondary">如何贴音乐</div>
              </div>
            </div>
          <!-- help -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import icons from "@/components/Icons/Icons";
import MainMenu from './components/Main/MainMenu.vue';
import ArticleListItem from './components/Main/ArticleListItem.vue';
import BlogerList from './components/Main/BlogerList.vue';
import blog from '../blog.service';

export default {
  name: 'blog-index',
  components: {
    MainMenu,
    ArticleListItem,
    BlogerList,
  },
  computed:{
    disabled () {
      return this.loading.article || this.noMore
    }
  },
  methods:{
    loadArticle(){
      this.loading.article=true;
      blog.article_list_recent(0,this.lastID.article,0).then(res=>{//需要新接口
        if(res.status){
          let arr = res.data;
          this.noMore = arr.length<30 ? true : false;
          this.lastID.article = arr.length===30 ? arr[arr.length-1].postID : this.lastID.article;
          this.articlelists = this.articlelists.concat(arr) ;
          this.loading.article=false;
          console.log(arr,this.lastID,this.noMore);
        }
      })
    },
  },
  created () {
    blog.hot_blogger().then(res=>{
      if(res.status){
        this.res_bloggerList=res.data;
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
           icons:icons,
    };
  },
};
</script>
<style></style>
