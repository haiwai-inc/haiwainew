<template>
    <div class="container">
      <div>
        <main-menu type="-1"></main-menu>
      </div>
      <div class="row">
       <div class="col-sm-3 d-none d-sm-block">
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
          <div class="collection-list mt-1">
            <collection-list v-if="loading.userinfo" v-bind:data="collectionList" :userdata="userInfo" title="博文目录"></collection-list>
          </div>
        </div>
       <div class="col-sm-9 col-12">   
            <div class="profile-header mt-2">
             <!-- header start -->  
              <div class="user-avatar d-flex p-3 mb-3" style="background-color:#f6f6f6;border-radius:5px">
                 <div class="flex-grow-1">
                   <h4 class="blog-user-index-name">{{currentCat.name}}</h4>
                   <span class="blog-user-index-des">{{currentCat.count_article}}篇文章</span>
                   <!-- <span class="blog-user-index-des ml-4">阅读.10853(假数据)</span> -->
                 </div>
                 <div>
                  <button type="button" class="btn btn-icon btn-round btn-neutral" title="分享">
                    <span style=" fill:#39B8EB;" v-html="icons.share"></span>
                 </button>
                 </div>
              </div>
             <!-- header end -->
            </div>
            <div
            v-infinite-scroll="loadArticle"
            infinite-scroll-disabled="disabled"
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
import MainMenu from '../pages/components/Main/MainMenu.vue';
import ArticleListItem from '../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../pages/components/Main/UserIndexSort.vue';
import CollectionList from '../pages/components/Main/CollectionList.vue';
import icons from "@/components/Icons/Icons";

import blog from '../blog.service';

export default {
  name: 'blog-user-index-category',
  components: {
    MainMenu,
    ArticleListItem,
    CollectionList,
  },
  computed:{
    disabled () {
      return this.loading.article || this.noMore
    }
  },
  mounted() {
    // this.initEditor();
    var source = new EventSource("/sse.php", { withCredentials: true });
    source.onopen = function (event) {
      console.log(event);
    };
    source.onclose = function (event){
      console.log(event);
    }
    source.onerror = function (event) {
      console.log(event);
    // handle error event
    };
    source.onmessage = function (message){
      console.log(message)
    };
  },
  created () {
    this.getBloggerInfo();
    blog.category_list(this.userID).then(res=>{
      this.collectionList=res.data;
      console.log(res.data);
      this.loadArticle();
    })
  },
  watch:{
    '$route.params.catid':function(val){
      console.log(val)
      this.loadArticle()
    }
  },
  methods:{
      changeTab(id){
        this.currentTabId = id;
        this.lastID.article = 0;
        this.articlelists = [];
        this.loadArticle(this.currentTabId);
      },
      loadArticle(){
        this.loading.article=true;
        blog.pubcat_article_list(this.catID,this.lastID.article).then(res=>{
          this.getList(res);
        })
        this.collectionList.forEach(item=>{
          if(item.id==this.catID){
            this.currentCat=item
          }
        })
      },
      getList(res){
        if(res.status){
          let arr = res.data;
          this.noMore = arr.length<30 ? true : false;
          this.lastID.article = arr.length<30 ? this.lastID.article : arr[arr.length-1].postID ;
          this.articlelists = this.articlelists.concat(arr) ;
          this.loading.article=false;
          // console.log(arr,this.lastID,this.noMore);
        }
      },
      getBloggerInfo(){
        blog.blogger_info(this.userID).then(res=>{
          console.log(res)
          if(res.status){
            this.userInfo = res.data;
            this.loading.userinfo=true;
          }
        })
      }
  },
  data() {
    return {
        userID:this.$route.params.userid,
        catID:this.$route.params.catid,
        currentCat:{},
        noMore:false,
        userInfo:{},
        lastID:{article:0,wenji:0},
        loading:{article:true,wenji:true,userinfo:false},
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
        icons:icons
    };
  },
};
</script>
<style>
.profile-header .nav-link{
    color:#657786;
    font-size:18px 
}
.profile-header .nav-link.active {
    color: #1D1D1D;
    border-bottom: 2px solid #39B8EB;
    font-weight: 600;
    font-size:18px
}
.avatarbox {
        cursor: pointer;
}
.collection-list-item .name:hover {
        color: #235592;
        text-decoration: none;
}

.blog-user-index .blog-user-index-des {
        font-size: 0.85rem;
        color: gray;
}
</style>
