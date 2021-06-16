<template>
    <div class="container">
      <div>
        <main-menu type="-1"></main-menu>
      </div>
      <!-- 如果没有blog -->
      <div v-if="$route.params.id==0">
          <regist-blog></regist-blog>
      </div>
      <div class="row" v-if="$route.params.id!=0">
      <index-header v-if="userinfo.id>0" :info="userinfo" ref="header"></index-header>
      <div class="d-lg-none col text-right mb-3">
          <el-button 
          v-if="$store.state.user.userinfo.userID==$route.params.id" 
          round
          @click="$router.push('/blog/my/')">
            <i class="el-icon-notebook-2"></i> {{$t('message').userindex.menu_btn_manage}}
        </el-button>
      </div>
       <div class="col-lg-3 d-none d-lg-block" v-show="bloggerID!=0">
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
          <div class="collection-list mt-3" v-if="collectionList.length>0">
            <collection-list v-bind:data="collectionList" :userdata="false" :title="$t('message').userindex.menu_title" @showbubble="showbubble"></collection-list>
          </div>
        <blog-help></blog-help>
        </div>
       <div class="col-lg-9 col-12" v-show="bloggerID!=0">
            <div class="profile-header mt-2 mb-3 border-bottom">
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
import BlogHelp from '../pages/components/Main/BlogHelp.vue';
import MainMenu from '../pages/components/Main/MainMenu.vue';
import ArticleListItem from '../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../pages/components/Main/UserIndexSort.vue';
import CollectionList from '../pages/components/Main/CollectionList.vue';
import IndexHeader from './IndexHeader';
import RegistBlog from '../../user/login/RegistBlog';

import blog from '../blog.service';

export default {
  name: 'blog-user-index',
  components: {
    MainMenu,
    ArticleListItem,
    CollectionList,
    IndexHeader,
    RegistBlog,
    BlogHelp,
  },
  watch:{
    "$route.params.id":function(val){
        this.userID = val;
        this.init_data();
    }
  },
  created () {
    // this.loadArticle();
    this.init_data();
  },
  computed:{
    disabled () {
      return this.bloggerID==0 || this.noMore || this.loading.article
    }
  },
  methods:{
    init_data(){
        blog.get_user_info(this.userID).then(res=>{
            this.userinfo = res.data;console.log(this.userinfo);
            this.bloggerID = res.data.bloggerID?res.data.bloggerID:0;
            this.articlelists = [];
            this.collectionList = [];
            if(this.bloggerID!=0){
                this.loadArticle();
                blog.category_list(this.bloggerID).then(res=>{
                    if(res.status){
                        this.collectionList=res.data;
                    }else{
                        this.$message.error(res.error);
                    }
                })
            }
        });
        this.user_login_wxc_to_haiwai(this.token);
    },
    changeTab(id){
        this.currentTabId = id;
        this.lastID.article = 0;
        this.articlelists = [];
        this.loadArticle();
      },
      loadArticle(){
          this.loading.article = true
        if(this.currentTabId==0){
            blog.article_list_recent(this.bloggerID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
        if(this.currentTabId==1){
            blog.article_list_hot(this.bloggerID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
        if(this.currentTabId==2){
            blog.article_list_comment(this.bloggerID,this.lastID.article).then(res=>{
                this.getList(res);
            });
        };
      },
      getList(res){
            if(res.status){
                let arr = res.data;
                this.noMore = arr.length<30 ? true : false;
                this.lastID.article = arr.length<30 ? this.lastID.article : arr[arr.length-1].postID ;
                this.articlelists = this.articlelists.concat(arr) ;
                this.loading.article=false;
                console.log(arr,this.lastID,this.noMore);
            }
      },
      user_login_wxc_to_haiwai(token){
            let user =this.$store.state.user;
          if(this.token && !user.userinfo.id){
            user.user_login_wxc_to_haiwai(token).then(res=>{
                user.getUserInfo(this.$route.params.id).then(res=>{
                    console.log(res)
                })
            })
          }
      },
      showbubble(){
          this.$refs['header'].showBubble()
      }
  },
  data() {
    return {
        userID:this.$route.params.id,
        bloggerID:0,
        userinfo:{},
        token:this.$route.query.token,
        currentTabId:0,
        noMore:false,
        lastID:{article:0,wenji:0},
        loading:{article:true,wenji:true},
        tabs:[
            {
                id:0,
                text:this.$t('message').userindex.article_tab0,
            },{
                id:1,
                text:this.$t('message').userindex.article_tab1,
            },{
                id:2,
                text:this.$t('message').userindex.article_tab2,
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
