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
         <!-- 把悄悄话写在这个span里面 开始 -->
          <div class="qiaoqiao-list">
           <div class="menu">
              全部悄悄话
           </div>
           <ul>
              <li>
                 <div class="pull-right">
                    <span class="time">2020.08.07</span> 
                    <div>
                       <a>
                          <svg width="3px" height="11px" viewBox="0 0 3 11" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                              <defs>
                                  <path d="M8,9.5 C8,8.67157288 8.67157288,8 9.5,8 C10.3284271,8 11,8.67157288 11,9.5 C11,10.3284271 10.3284271,11 9.5,11 C8.67157288,11 8,10.3284271 8,9.5 Z M8,5.5 C8,4.67157288 8.67157288,4 9.5,4 C10.3284271,4 11,4.67157288 11,5.5 C11,6.32842712 10.3284271,7 9.5,7 C8.67157288,7 8,6.32842712 8,5.5 Z M8,13.5 C8,12.6715729 8.67157288,12 9.5,12 C10.3284271,12 11,12.6715729 11,13.5 C11,14.3284271 10.3284271,15 9.5,15 C8.67157288,15 8,14.3284271 8,13.5 Z" id="path-1"></path>
                              </defs>
                              <g id="supercharge.design-icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                  <g id="ellipsis-vertical" transform="translate(-8.000000, -4.000000)">
                                      <mask id="mask-2" fill="white">
                                          <use xlink:href="#path-1"></use>
                                      </mask>
                                      <g id="Shape"></g>
                                      <g id="Group" mask="url(#mask-2)">
                                          <g transform="translate(0.000000, 0.500000)" id="color/black">
                                              <rect id="Change-Fill-Color" fill="#9A9A9A" x="0" y="0" width="18" height="17"></rect>
                                          </g>
                                      </g>
                                  </g>
                              </g>
                          </svg>
                        </a> 
                        <ul class="dropdown-menu">
                          <li><a>删除会话</a></li>
                          <li><a>加入黑名单</a></li>
                          <li><a>举报用户</a></li>
                       </ul>
                    </div>
                 </div>
                 <a href="#" target="_blank" class="avatar">
                    <img alt="Circle Image" class="rounded-circle" src="/img/julie.jpg" lazy="loaded"> 
                 </a>
                 <a href="#" target="_blank" class="name">呜啦啦</a>
                 <a href="" class="wrap"><p>好的 多谢关注</p></a> 
              </li>
           </ul>
          </div>
        <!-- 把悄悄话写在这个span里面 结束 -->
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
/*qiaoqiaohua*/
.qiaoqiao-list .menu {
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 700;
}
.qiaoqiao-list .wrap {
        display: block;
        padding: 20px 20px 20px 0;
        min-height: 88px;
}
.qiaoqiao-list ul{
        list-style: none;
        padding-left: 0
}
.qiaoqiao-list li {
        position: relative;
        border-top: 1px solid #f0f0f0;
}
.qiaoqiao-list .avatar {
        float: left;
        margin: 20px 10px 20px 5px;
        width: 48px;
        height: 48px;
}
.qiaoqiao-list .name {
        position: absolute;
        top: 25px;
        color: #14171a;
        font-weight: 700;
}
.qiaoqiao-list .avatar img {
        width: 100%;
        height: 100%;
        border: 1px solid #ddd;
}
.qiaoqiao-list p {
        margin: 28px 0 0;
        font-size: 1rem;
        color: #999;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
}
.qiaoqiao-list .pull-right {
        margin: 20px 20px 0 0;
        font-size: 13px;
        float: right!important;
}
.qiaoqiao-list .pull-right div {
        display: inline;
}
.qiaoqiao-list .pull-right .time {
        font-size: small;
        color: #9A9A9A;
        font-weight: 500;
        margin-right: 10px;
}

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
