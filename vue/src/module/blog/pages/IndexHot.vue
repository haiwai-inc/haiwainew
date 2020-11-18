<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="2"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-8 col-12">
          <main-category-bar type='0' v-bind:data="maincategory"></main-category-bar>
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
import MainMenu from './components/Main/MainMenu';
import MainCategoryBar from './components/Main/MainCategoryBar';
import ArticleListItem from './components/Main/ArticleListItem';
import BlogerList from './components/Main/BlogerList';

export default {
  name: 'index-hot',
  bodyClass: 'index-page',
  components: {
    MainMenu,
    MainCategoryBar,
    ArticleListItem,
    BlogerList,
  },
  data() {
    return {
      maincategory:[
        {id:'0',name:'全部',link:'/blog/hot'},
        {id:'1',name:'原创',link:'/blog/hot'},
        {id:'2',name:'时政',link:'/blog/hot'},
        {id:'3',name:'旅游',link:'/blog/hot'},
        {id:'4',name:'美食',link:'/blog/hot'},
        {id:'5',name:'娱乐',link:'/blog/hot'},
        {id:'6',name:'家居',link:'/blog/hot'},
        {id:'7',name:'财经',link:'/blog/hot'},
        {id:'8',name:'热榜',link:'/blog/hot'},
        {id:'9',name:'推荐',link:'/blog/hot'},
        {id:'10',name:'热榜',link:'/blog/hot'},
        {id:'11',name:'推荐',link:'/blog/hot'},
        {id:'12',name:'热榜',link:'/blog/hot'}
      ],
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
      isHot:false,
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
      articlelists: [
        {
          countinfo_postID: {
              count_buzz: 0,
              count_comment: 0,
              count_read: 4,
              postID: 107103,
          },
          create_date: 1605519001,
          title:'参加周末DC的大游行和听到的最惊人消息....',
          id: 207,
          postID: 107103,
          postInfo_postID:{
              buzz:[],
              msgbody: "11/14 早晨我们就出发了，计划这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介",
              pic:"/img/bg4.jpg",
              postID: 107103,
              tages:[32,8],
              title:"参加周末DC的大游行和听到的最惊人消息"
          },
          userID:'1776',
          userinfo_userID:{
              avatar:"/data/members/08/58/1010858.jpg",
              description:"个人简介",
              first_letter:"L",
              ID:1776,
              is_follower:0,
              is_hot_blogger:0,
              status:1,
              username:"luckyyycat",
              verified:1
          }
        },
      ],
    };
  },
};
</script>
<style></style>
