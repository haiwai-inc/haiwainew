<template>
  <div>
    <div class="container">
      <div>
        <main-menu type="0"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-4 d-none d-sm-block">
          <div class="followed-blogger">
            <ul>
              <li :class="{active:selectID==-1}" @click="selectItem(-1)"><icon-article-bg style="height:42;width:42;fill:#39B8EB"></icon-article-bg><span class="pl-2">全部更新文章</span></li>
              <li 
              v-for="(item,index) in authorList" 
              :key="index" 
              class="d-flex align-items-center"
              :class="{active:selectID==index}"
               @click="selectItem(index)">
                <avatar :data="item" :imgHeight="42"></avatar>
                <span class="pl-2">{{item.name}}</span>
                <span class="ml-auto">123</span>
              </li>
            </ul>
            
          </div>
        </div>
        <div class="col-sm-8 col-12">
          <div v-if="selectID==-1">
            <article-list-item 
              v-for="item in articlelists"
              v-bind:key="item.articleID"
              v-bind:data="item"
              type="0">
            </article-list-item>
          </div>
          <div v-if="selectID!=-1">
            <index-header userID="2"></index-header>
            <article-list-item 
              v-for="item in articlelists"
              v-bind:key="item.articleID"
              v-bind:data="item"
              type="0">
            </article-list-item>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from './components/Main/MainMenu';
import ArticleListItem from './components/Main/ArticleListItem';
import Avatar from  './components/Main/Avatar';
import { IconArticleBg } from '@/components/Icons';
import IndexHeader from '../user/IndexHeader'
// import BlogerList from './components/Main/BlogerList';

export default {
  name: 'index-follows',
  components: {
    MainMenu,
    ArticleListItem,
    Avatar,
    IconArticleBg,
    IndexHeader,
  },
  methods:{
    selectItem(idx){
      this.selectID=idx
    }
  },
  data() {
    return {
      selectID:-1,
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
      authorHomepage:'',
      isHot:true,
      name:'English Name',
      firstLetter:'用',
      description:'简介简介简介简介 English desciption',
      isFollowed:true,
    },{
      avatarUrl:'/img/eva.jpg',
      authorHomepage:'',
      isHot:false,
      name:'天煞地煞',
      firstLetter:'天',
      description:'简介简介简介简介哈哈哈简介简介简介简',
      isFollowed:false,
    },{
      avatarUrl:'/img/julie.jpg',
      authorHomepage:'',
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
<style>

.followed-blogger ul {
  list-style: none;
  padding-left: 0;
}
.followed-blogger li {
  padding:10px;
  position: relative;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
}
.followed-blogger li.active, .followed-blogger li:hover{
  background-color: aliceblue;
}
</style>
