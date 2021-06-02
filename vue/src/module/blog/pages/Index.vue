<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="1"></main-menu>
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
          <blog-help></blog-help>
          <right-footer></right-footer>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from './components/Main/MainMenu.vue';
import ArticleListItem from './components/Main/ArticleListItem.vue';
import BlogerList from './components/Main/BlogerList.vue';
import BlogHelp from './components/Main/BlogHelp.vue';
import blog from '../blog.service';
import icons from "@/components/Icons/Icons";
import RightFooter from '../../../layout/RightFooter.vue'

export default {
  name: 'blog-index',
  components: {
    MainMenu,
    ArticleListItem,
    BlogerList,
    RightFooter,
    BlogHelp
  },
  computed:{
    disabled () {
      return this.loading.article || this.noMore
    }
  },
  methods:{
    loadArticle(){
      this.loading.article=true;
      blog.recommend_article(this.lastID.article).then(res=>{
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
