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
import MainMenu from '../components/Main/MainMenu';
import MainCategoryBar from '../components/Main/MainCategoryBar';
import ArticleListItem from '../components/Main/ArticleListItem';
import BlogerList from '../components/Main/BlogerList';

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
        {id:'0',name:'全部',link:'/login'},
        {id:'1',name:'原创',link:'/login'},
        {id:'2',name:'时政',link:'/login'},
        {id:'3',name:'旅游',link:'/login'},
        {id:'4',name:'美食',link:'/login'},
        {id:'5',name:'娱乐',link:'/login'},
        {id:'6',name:'家居',link:'/login'},
        {id:'7',name:'财经',link:'/login'},
        {id:'8',name:'热榜',link:'/login'},
        {id:'9',name:'推荐',link:'/login'},
        {id:'10',name:'热榜',link:'/login'},
        {id:'11',name:'推荐',link:'/login'},
        {id:'12',name:'热榜',link:'/login'}
      ],
      authorList : [
    {
      avatarUrl:'img/avatar.jpg',
      isHot:true,
      name:'English Name',
      firstLetter:'用',
      description:'简介简介简介简介 English desciption',
      isFollowed:true,
    },{
      avatarUrl:'img/eva.jpg',
      isHot:false,
      name:'天煞地煞',
      firstLetter:'天',
      description:'简介简介简介简介哈哈哈简介简介简介简',
      isFollowed:false,
    },{
      avatarUrl:'img/julie.jpg',
      isHot:true,
      name:'用户名',
      firstLetter:'用',
      description:'简介简介简介简介',
      isFollowed:false,
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
          isHot:false,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'img/bg3.jpg',
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
          image:'img/bg4.jpg',
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
          image:'img/bg1.jpg',
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
    };
  },
};
</script>
<style></style>
