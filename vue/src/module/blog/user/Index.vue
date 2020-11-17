<template>
    <div class="container">
      <div>
        <main-menu type="-1"></main-menu>
      </div>
      <div class="row">
       <div class="col-sm-8 col-12">
            <index-header :userID="3"></index-header>
            <article-list-item 
            v-for="item in articlelists"
            v-bind:key="item.articleID"
            v-bind:data="item"
            type="0">
            </article-list-item>
        </div>
        <div class="col-sm-4 d-none d-sm-block">
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
          <div class="collection-list mt-3">
            <collection-list v-bind:data="collectionList" title="文集"></collection-list>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import MainMenu from '../pages/components/Main/MainMenu.vue';
import ArticleListItem from '../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../pages/components/Main/UserIndexSort.vue';
import CollectionList from '../pages/components/Main/CollectionList.vue';
import IndexHeader from './IndexHeader'

export default {
  name: 'blog-user-index',
  components: {
    MainMenu,
    ArticleListItem,
    //UserIndexSort,
    CollectionList,
    IndexHeader
  },
  data() {
    return {
        sortList:[
            {
                icon:'0',
                text:'最新博文',
                src:'0'
            },{
                icon:'1',
                text:'最热博文',
                src:'1'
            },{
                icon:'2',
                text:'新评博文',
                src:'2'
            }
        ],
        collectionList : [
            {
                id:12,
                title:'飞鸟集',
                articleList:[
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
                    }
                ],
                read:12345,
            },{
                id:34,
                title:'飞鸟集',
                articleList:[],
                read:12345,
            },{
                id:34,
                title:'飞鸟集',
                articleList:[],
                read:12345,
            },{
                id:34,
                title:'飞鸟集',
                articleList:[],
                read:12345,
            },{
                id:34,
                title:'飞鸟集',
                articleList:[],
                read:12345,
            },
        ],
        authorInfo : {
            avatarUrl:'/img/avatar.jpg',
            isHot:true,
            name:'English Name',
            firstLetter:'用',
            description:'简介简介简介简介 English desciption',
            isFollowed:false,
            read:12345,
            backgroundImg:'/img/bg11.jpg'
        },
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
