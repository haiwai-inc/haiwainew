<template>
<div class="container">
      <div class="d-none d-sm-block">
        <main-menu type="-1"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-4 left-top-nav">
            <left-nav-item 
            v-for="(item,index) in data" 
            :key='index' 
            :data='item'
            :activeId='activeId'
            v-on:whichActive="whichActive"></left-nav-item>
        </div>
        <div class="col-sm-8 col-12">
          <span
            v-if="activeId===0"
          >
            <notice-comment></notice-comment>
          </span>
          <span
            v-if="activeId===1"
          >
          <!-- 把悄悄话写在这个span里面 -->
          </span>
          <span
            v-if="activeId===2"
          >关注</span>
          <span
            v-if="activeId===3">
            <h4>全部喜欢</h4>
            <article-list-item 
            v-for="item in articlelists"
            v-bind:key="item.articleID"
            v-bind:data="item"
            type="0">
            </article-list-item>
          </span>
            
        </div>
      </div>
    </div>
  
</template>
<script>
import MainMenu from '../../blog/pages/components/Main/MainMenu.vue';
import LeftNavItem from '../../blog/pages/components/Main/LeftNavItem';
import ArticleListItem from '../../blog/pages/components/Main/ArticleListItem.vue';
import NoticeComment from './NoticeComment';
export default {
  name: 'notices',
  data(){
    return{
      activeId:0,
      data:[
        {
          id:0,
          title:'评论',
          noticeList:[],
          unread:2,
        },{
          id:1,
          title:'悄悄话',
          noticeList:[],
          unread:0,
        },{
          id:2,
          title:'关注',
          noticeList:[],
          unread:3,
        },{
          id:3,
          title:'喜欢',
          noticeList:[],
          unread:6,
        },
      ],
      articlelists: [
            {
            articleID:'345678',
            articleUrl:'',
            title:'这里是标题....',
            description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
            author:'这里是作者',
            authorID:'123456789',
            isHot:true,
            read:'3456',
            commont:'12',
            likes:'23',
            image:'/img/bg3.jpg',
            },{
            articleID:'34567',
            articleUrl:'',
            title:'这里是标题....',
            description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
            author:'这里是作者',
            authorID:'123456',
            isHot:false,
            read:'3456',
            commont:'12',
            likes:'23',
            image:'/img/bg4.jpg',
            },{
            articleID:'3456',
            articleUrl:'',
            title:'这里是标题....',
            description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
            author:'这里是作者',
            authorID:'12345',
            isHot:true,
            read:'3456',
            commont:'12',
            likes:'23',
            image:'/img/bg1.jpg',
            },{
            articleID:'345',
            articleUrl:'',
            title:'这里是标题....',
            description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
            author:'这里是作者',
            authorID:'123456789',
            isHot:false,
            read:'3456',
            commont:'12',
            likes:'23',
            image:'',
            },{
            articleID:'3456789',
            articleUrl:'',
            title:'这里是标题....',
            description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
            author:'这里是作者',
            authorID:'123456789',
            isHot:true,
            read:'3456',
            commont:'12',
            likes:'23',
            image:'',
            }
        ],
    }
  },
  components: {
    LeftNavItem,
    MainMenu,
    ArticleListItem,
    NoticeComment
  },
  computed:{
    
  },
  methods:{
    whichActive(id){
      this.activeId=id;
      console.log(this);
    }
  }
};
</script>
<style>
@media (max-width: 575.98px){
  .left-top-nav .name svg, .left-top-nav .descrip{
    display: none;
  }
  .left-top-nav{
    display: flex;
    justify-content: space-between;
  }
}
</style>
