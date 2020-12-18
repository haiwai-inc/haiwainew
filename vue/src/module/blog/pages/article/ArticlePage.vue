<template>
  <div class="article-page">
    <div class="container">
      <div>
        <main-menu ></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-8 col-12" v-if="showpage">
          <div v-if="!articleDetail.status" class="text-center text-warning">{{articleDetail.error}}</div>
          <div v-if="articleDetail.status">
            <h4>{{articleDetail.data.postInfo_postID.title}}</h4>
            <div class="d-flex justify-content-between">
              <span class="blogger-box">
                <bloger-list-item :data="articleDetail.data" type="small"></bloger-list-item>
              </span>
            
            <span class="media-icons">
              <button type="button" class="btn btn-icon btn-round btn-neutral" title="喜欢" v-if="false">
                <span style="fill:#39B8EB" v-html="icons.like"></span>
              </button>
              <button type="button" class="btn btn-icon btn-round btn-neutral" title="喜欢">
                <span style=" stroke:#39B8EB" v-html="icons.like_outline"></span>
              </button>
              <button type="button" class="btn btn-icon btn-round btn-neutral" title="收藏" style="fill:#39B8EB" v-if="articleDetail.data.postInfo_postID.is_bookmark">
                <!-- <icon-star></icon-star> -->
                <span v-html="icons.star"></span>
              </button>
              <button type="button" class="btn btn-icon btn-round btn-neutral" title="收藏" style="fill:#39B8EB" v-if="!articleDetail.data.postInfo_postID.is_bookmark">
                <span v-html="icons.star_outline"></span>
              </button>
              <button type="button" class="btn btn-icon btn-round btn-neutral" title="分享">
                <span style=" fill:#39B8EB;" v-html="icons.share"></span>
              </button>
            </span>
            </div>
            <div class="content" v-html="articleDetail.data.postInfo_postID.msgbody">
              <!-- blog 正文 -->
            </div>
            <div class="article-menu">
                 <div class="article-menub">
                    <a class="text-black" href="#">
                       <i>
                          <svg viewBox="64 64 896 896" focusable="false"  width="1em" height="1em">
                             <path d="M724 218.3V141c0-6.7-7.7-10.4-12.9-6.3L260.3 486.8a31.86 31.86 0 0 0 0 50.3l450.8 352.1c5.3 4.1 12.9.4 12.9-6.3v-77.3c0-4.9-2.3-9.6-6.1-12.6l-360-281 360-281.1c3.8-3 6.1-7.7 6.1-12.6z"></path>
                          </svg>
                       </i>
                       <span>上一篇</span>
                    </a>
                 </div>
                 <div class="article-menub article-menut " role="button" >查看xx连载目录</div>
                 <div class="article-menub">
                    <a class="text-black" href="#">
                       <span>下一篇</span>
                       <i>
                          <svg viewBox="64 64 896 896" width="1em" height="1em" >
                             <path d="M765.7 486.8L314.9 134.7A7.97 7.97 0 0 0 302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 0 0 0-50.4z"></path>
                          </svg>
                       </i>
                    </a>
                 </div>
            </div>
          </div>
          <div v-if="showcomment && !comment.status" class="text-center">评论数据获取失败</div>
          <div class="comment" v-if="showcomment && comment.status">
            <h4>评论（{{comment.data.length}}）</h4>
            <comment :data="comment.data"></comment>
          </div>
        </div>
        <div class="col-sm-4 d-none d-sm-block" v-if="showpage">
         <!-- r1 -->
            <div class="box my-3">
              <span class="blogger-box">
                <!-- <bloger-list-item :data="articleDetail.data" type="small"></bloger-list-item> -->
              </span>
               <!-- 左边相同样式 -->
               <span v-for="(item,index) in recommend.authorArticle" :key="index">
                 <recommend-list-item :data="item"></recommend-list-item>
               </span>
            </div>
          <!-- r2 -->
            <div class="box">
               <div class="title  d-flex justify-content-between">
                  <h5>文集-芳草渡 (56) </h5>
               </div>
               <span v-for="(item,index) in recommend.collections" :key="index" >
                 <recommend-list-item type="small" :data="item"></recommend-list-item>
               </span>
               

               <div class="justify-content-right border-top d-flex text-right ">
                  <button type="button" class="btn btn-link btn-default f-right" style="padding-right: 0px;">
                     <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>more</title>
                        <g id="more" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <path d="M11.5740443,6.24199451 L11.5740443,20.2419945 M5,12 L11.5,6 L18,12" id="Arrow" stroke="#6D7278" stroke-width="2" transform="translate(11.500000, 13.120997) rotate(46.000000) translate(-11.500000, -13.120997) "></path>
                           <rect id="Rectangle" stroke="#6D7278" stroke-width="2" x="3" y="3" width="18" height="18" rx="1"></rect>
                        </g>
                     </svg>
                     更多
                  </button>
               </div>
            </div>
          <!-- r3 -->
            <div class="box my-3">
               <div class="title  d-flex justify-content-between">
                  <h5>相关推荐</h5>
                  <button type="button" class="btn btn-link btn-default" style="padding-right: 0px;"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</button>
               </div>
               <span v-for="(item,index) in recommend.authorArticle" :key="index">
                 <recommend-list-item :data="item"></recommend-list-item>
               </span>
            </div>
          <!-- r3 end-->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from '../components/Main/MainMenu';
import BlogerListItem from '../components/Main/BlogerListItem';
import RecommendListItem from '../components/Main/RecommendListItem';
import icons from "@/components/Icons/Icons";
import Comment from './Comment';
import blog from '../../blog.service';

export default {
  name: 'article-page',
  components: {
    MainMenu,
    BlogerListItem,
    RecommendListItem,
    Comment,
  },
  mounted: function () {
    this.article_view()
  },
  methods:{
    article_view(){
      this.showpage = false;
      this.showcomment = false;
      let postid = this.$route.params.id
      blog.article_view(postid).then(res=>{
        this.articleDetail = res.data;
        this.showpage = true;
        console.log(res)
      })
      blog.article_view_comment(postid).then(res=>{
        this.comment = res.data;
        this.showcomment=true;
        console.log(this.comment)
      })
    }
  },
  data() {
    return {
      icons:icons,
      showcomment:false,
      showpage:false,
      articleDetail: {},
      comment:{},
      recommend:{
        authorArticle:[
          {
            articleUrl:'/blog/p/12345',
            articleID:12345,
            articleTitle:'老妈的摄影作品 - 麻雀也是肉',
            read:1234,
            date:'2020.08.07',
            image:'/img/bg8.jpg'
          },
          {
            articleUrl:'/blog/p/12345',
            articleID:12345,
            articleTitle:'老妈的摄影作品 - 苍蝇也是肉',
            read:1234,
            date:'2020.08.07',
            image:''
          },
          {
            articleUrl:'/blog/p/12345',
            articleID:12345,
            articleTitle:'老妈的摄影作品 - 蝙蝠不是鸟',
            read:1234,
            date:'2020.08.07',
            image:'/img/bg8.jpg'
          },
        ],
        collections:[
          {
            articleUrl:'/blog/p/12345',
            articleID:12345,
            articleTitle:'老妈的摄影作品 - 蝙蝠不是鸟',
          }
        ]
      }
    };
  },
};
</script>
<style>
.fontsize0 .content{
  font-size: 1.1rem;
}
.fontsize1 .content{
  font-size: 1.3rem;
}
.fontsize2 .content{
  font-size: 1.5rem;
}
/* r1 r2 r3*/
.article-page .box {
        background-color: aliceblue;
        border-radius: 16px;
        padding: 10px 0
}

.article-page .title
{
padding: 0 18px;
}
.article-page h5{
  margin-top: 15px;
  font-weight:400
}

/* menu */
.article-menu {
        display: flex;
        align-items: center;
        margin: 30px 0;
        line-height: 20px;
        border-radius: 30px;
        background-color: #f2f2f2;
}
.article-menub {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 33.33%;
        height: 60px;
        font-size: 14px;
        font-weight: bold;
}
.article-menut {
        height: 20px;
        color: #8c8c8c;
        font-weight: normal;
        border-left: 1px solid #eee;
        border-right: 1px solid #eee;
        cursor: pointer;
}

/* r2 */

.article-page h4{
  margin-bottom: 0;
}
.article-page .content{
  padding-top:1rem;
}
.article-page .content p img{
  width:85%;
  margin:0 auto;
  display:block;
}
.article-page .blogger-box{
  width:300px
}
.article-page .media-icons .btn{
  font-size: 1.5rem !important;
  margin:10px 0 0 0;
}

@media (max-width: 575.98px){
  .article-page .blogger-box{
    width:220px
  }
  .article-page .content p img{
  width:100%;
}
}
</style>
