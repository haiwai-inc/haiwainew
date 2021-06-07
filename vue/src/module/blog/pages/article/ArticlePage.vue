<template>
  <div class="article-page">
    <div class="container">
      <div>
        <main-menu ></main-menu>
      </div>
      <div class="row">
        <div v-if="!articleDetail.status" class="mt-4 pt-4 mx-auto text-center  mb-4 ">
        <h4>{{articleDetail.error}}</h4></div>
        <div class="col-sm-8 col-12" v-if="articleDetail.status">
          
          <div>
            <h4 class="py-3">{{articleDetail.data.postInfo_postID.title|textTrans}}<span v-if="articleDetail.data.is_publish===0" class="text-muted">（{{$t('message').article.hide_text}}）</span></h4>
            <div class="d-flex justify-content-between align-items-center">
              <span class="blogger-box">
                <bloger-list-item :data="articleDetail.data" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
              </span>
               
              <div class="media-icons">
                <button type="button" class="btn btn-icon btn-round btn-neutral" :title="$t('message').article.like_text" @click="like()">
                  <span v-if="articleDetail.data.postInfo_postID.is_buzz" style="fill:#39B8EB" v-html="icons.like"></span>
                  <span v-if="!articleDetail.data.postInfo_postID.is_buzz" style=" stroke:#39B8EB" v-html="icons.like_outline"></span>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-neutral" :title="$t('message').article.bookmark_text" style="fill:#39B8EB" @click="bookmark()">
                  <span v-if="articleDetail.data.postInfo_postID.is_bookmark" v-html="icons.star"></span>
                  <span v-if="!articleDetail.data.postInfo_postID.is_bookmark" v-html="icons.star_outline"></span>
                </button>
                
                <el-popover
                placement="top-end"
                trigger="click"
                v-model="share.showShareBar">
                  <div class="shareIcons">
                    <ShareNetwork
                    v-for="item in shareNetworks"
                    :key="item.network"
                    :network="item.network"
                    :url="shareItem.url"
                    :title="shareItem.title"
                    :description="shareItem.description"
                    quote=""
                    hashtags=""
                    >
                      <span class="shareIcon mr-1" v-html="item.icon" @click="share.showShareBar=false"></span>  
                    </ShareNetwork>
                    <el-popover style="display:inline-block"
                    placement="bottom-end"
                    width="400" 
                    trigger="click"
                    visible-arrow="false"
                    v-model="share.wechatQR">
                      <div class="float-right" @click="share.wechatQR=false">{{$t('message').article.share_wechat_close}}</div>
                      <div class="mt-5">{{$t('message').article.share_wechat_text}}</div>
                      <img style="margin: 0 95px;" :src="shareItem.QRcode" alt="">
                      <a href="#" @click="share.showShareBar=false" slot="reference"><span class="shareIcon d-none d-sm-block" v-html="icons.wechat"></span></a>
                    </el-popover>
                  </div>
                    
                  <button type="button" class="btn btn-icon btn-round btn-neutral" :title="$t('message').article.share_text" slot="reference">
                    <span style=" fill:#39B8EB;" v-html="icons.share"></span>
                  </button>
                </el-popover>
              
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div style="color:gray;">{{articleDetail.data.create_date*1000 | formatDate}}</div>
              <div style="color:gray;" class="ml-3">{{$t('message').article.read_count}}: {{articleDetail.data.countinfo_postID.count_read}}</div>
              <el-button class="ml-3" type="text" icon="el-icon-edit" v-if="articleDetail.data.userID==$store.state.user.userinfo.UserID" style="color:#39b8eb" @click="gotoEditor(articleDetail.data)">{{$t('message').article.edit}}</el-button>
              <el-popconfirm  v-if="articleDetail.data.userID==$store.state.user.userinfo.UserID"
                placement="top-end"
                :confirm-button-text="$t('message').article.comment_delet"
                :cancel-button-text="$t('message').article.comment_cancel"
                :title="$t('message').article.delete_confirm"
                :hide-icon="true"
                @confirm="article_delete(articleDetail.data)"
              >
                <el-button type="text" icon="el-icon-delete" slot="reference" class="ml-3" style="color:#39b8eb">删除</el-button>
              </el-popconfirm>
            </div>
            <div class="content" v-html="$options.filters.textTrans(articleDetail.data.postInfo_postID.msgbody)">
              <!-- blog 正文 -->
            </div>
            
            <previous-next-bar
              v-if="articleDetail.data.article_previous_next.next ||articleDetail.data.article_previous_next.previous"
             :data="articleDetail.data"></previous-next-bar>
            
          </div>
          <div class="comment" v-if="articleDetail.data.is_comment">
            <textarea type="textarea" v-model="replymsgbody" rows="3" class="w-100 mt-2 p-2" :placeholder="$t('message').article.comment_placeholder" @keyup="checkstatus"></textarea>
            <n-button 
              type="primary"
              round 
              simple 
              :disabled="replybtndisable" 
              @click="reply_add">{{$t('message').article.comment_button}}</n-button>
            <h5 class="commentlable">{{$t('message').article.comment_text}}（{{articleDetail.data.countinfo_postID.count_comment}}）</h5>
            
          </div>
          <div v-if="!showcomment" class="text-center">评论数据获取失败</div>
          <div v-infinite-scroll="getComment"
          infinite-scroll-disabled="disabled"
          infinite-scroll-distance="50" v-if="comment.length>0">
              <comment 
              v-for="item in comment"
              :key="item.postID"
              :data="item"
              :author="articleDetail.data.userID"
              @regetcomment="rewrite"
              @opendialog="$refs.dialog.isLogin()"
              ></comment>
          </div>
          <div class="text-center py-5" v-if="loading.comment"><!-- loader -->
              <i class="now-ui-icons loader_refresh spin"></i>
          </div>
          <p class="text-center py-4" style="cursor:pointer" v-if="!noMore" @click="getComment">{{$t('message').article.comment_more}}</p>
          <p class="text-center py-4" v-if="noMore">没有更多了</p>
        </div>
        <div class="col-sm-4 d-none d-sm-block" v-if="articleDetail.status">
         <!-- r1 -->
            <div class="box my-3">
              <span class="blogger-box">
                <bloger-list-item :data="articleDetail.data" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
              </span>
               <!-- 左边相同样式 -->
               <span v-for="(item,index) in recommend.authorArticle" :key="index">
                 <recommend-list-item :data="item" v-if="index<5"></recommend-list-item>
               </span>
               <div class="justify-content-right border-top d-flex text-right ">
                 <router-link  :to="'/blog/user/'+articleDetail.data.userID">
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
                 </router-link>
               </div>
            </div>
          <!-- r3 -->
            <div class="box my-3" v-if="recommend.articles.length>0">
               <div class="title  d-flex justify-content-between">
                  <h5>{{$t('message').article.recomment_text}}</h5>
                  <!-- <button type="button" class="btn btn-link btn-default" style="padding-right: 0px;" @click="getRecommend()"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</button> -->
               </div>
               <span v-for="(item,index) in recommend.articles" :key="index">
                 <recommend-list-item :data="item" v-if="index<10"></recommend-list-item>
               </span>
            </div>
          <!-- r3 end-->
         <blog-help></blog-help>
          <div class=" my-3  ml-3  text-secondary text-left text-small">
             <p class="small d-inline mb-1">Copyright ©2021 Haiwai.com</p>
             <div class="row">
                <div class="ml-3 mr-2"><a href="/privacy" class="small text-secondary">隐私</a></div>
                <div class="mr-2"><a href="/tou" class="small text-secondary">条款</a></div>
                <div class="mr-2"><a href="#" class="text-secondary small">关于</a></div>
             </div>
          </div>
          <!-- footer -->
        </div>
      </div>
    </div>
    
    <login-dialog ref="dialog"></login-dialog>
    
  </div>
</template>
<script>
import {formatDate} from '@/directives/formatDate.js';
import {textTrans} from '@/directives/textTrans.js';
import MainMenu from '../components/Main/MainMenu';
import BlogerListItem from '../components/Main/BlogerListItem';
import RecommendListItem from '../components/Main/RecommendListItem';
import { Button } from '@/components';
import icons from "@/components/Icons/Icons";
import Comment from './Comment';
import PreviousNextBar from './PreviousNextBar';
import blog from '../../blog.service';
import account from '../../../user/service/account';
import { Popover } from 'element-ui';
import LoginDialog from '../../../user/login/LoginDialog.vue';
import BlogHelp from '../components/Main/BlogHelp.vue';



export default {
  name: 'article-page',
  components: {
    MainMenu,
    BlogerListItem,
    RecommendListItem,
    Comment,
    PreviousNextBar,
    [Button.name]: Button,
    [Popover.name]:Popover,
    LoginDialog,
    BlogHelp
  },
  mounted: function () {
    this.article_view();
    this.user_login_wxc_to_haiwai(this.token);
  },
  computed:{
    disabled () {
      return this.loading.comment || this.noMore
    }
  },
  methods:{
    article_view(){
      this.showcomment = false;
      let postid = this.$route.params.id ;
      blog.article_view(postid).then(res=>{
        this.articleDetail = res;console.log(res);
        if(res.status){
          let descrip = res.data.postInfo_postID.msgbody
          this.shareItem.title = res.data.postInfo_postID.title;
          this.shareItem.description = descrip.replace(/<[^>]+>/g,"").substr(0,100);
          this.initRecommendProp(res);
          this.getRecentArticle(res,0);
          this.getComment();
        }
      });
    },
    initRecommendProp(res){
      var arr = [];
      res.data.postInfo_postID.tags.forEach(r=>{
        arr.push(r.id)
      });
      this.recommend.props.tags = arr.length>0?arr.toString():''
      this.recommend.props.lastID = 0;
      this.getRecommend()
    },
    getRecommend(){
      let postID = this.$route.params.id;
      blog.article_list_tag(this.recommend.props.tags,postID).then(res=>{
        console.log(res);
        if(res.status){
          let arr = res.data
          this.recommend.articles = arr;
          this.recommend.props.lastID = arr.length>10?arr[9].postID:arr.length!=0?arr[arr.length-1].postid:0;
        }
      })
    },
    getRecentArticle(res,lastID){
      let postID = this.$route.params.id;
      var bloggerID = res.data.bloggerID;
      blog.article_list_recent(bloggerID,lastID,postID).then(res=>{
        console.log(res);
        if(res.status){
          this.recommend.authorArticle = res.data
        }
      })
    },
    reply_add(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        let obj = {
          article_data:{msgbody:this.replymsgbody,
          postID:this.articleDetail.data.postID,
          typeID:1}
        }
        this.replybtndisable = true;
        blog.reply_add(obj).then(res=>{
          this.replybtndisable = false;
          if(res.status){
            this.getFirst20();
          }else{
            this.$message.error(res.error)
          }
          
          this.replymsgbody="";
        })
      }
    },
    checkstatus(){
      this.replybtndisable = this.replymsgbody?false:true;
    },
    getFirst20(){
      this.lastID = 0;
      this.comment = [];
      this.getComment();
    },
    getComment(){
      this.loading.comment = true;
      this.noMore = false;
      if(this.$store.state.user.userinfo.UserLevel!=2){
        blog.article_view_comment(this.$route.params.id,this.lastID).then(res=>{
          let r = res.data;
          this.comment = this.comment.concat(r);
          this.lastID = r.length>0?r[r.length-1].postID:0;
          if(r.length<20){
            this.noMore = true;
          }
          this.showcomment=res.status?true:false;
          this.loading.comment = false;
          console.log(this.comment,this.loading.comment,this.noMore,this.lastID)
        })
      }else{
        this.$store.state.user.article_view_comment(this.$route.params.id,this.lastID).then(res=>{
          let r = res.data;
          this.comment = this.comment.concat(r);
          this.lastID = r.length>0?r[r.length-1].postID:0;
          if(r.length<20){
            this.noMore = true;
          }
          this.showcomment=res.status?true:false;
          this.loading.comment = false;
          console.log(this.comment,this.loading.comment,this.noMore,this.lastID)
        })
      }
    },
    rewrite(id){
      console.log("reget",id);
      if(!id){
        this.comment.splice(0,1)
      }
      blog.article_view_comment_one(id).then(res=>{
        this.comment.forEach(obj=>{
          if (obj.postID==id){
            let idx = this.comment.indexOf(obj);
            this.comment.splice(idx,1,res.data) 
          }
        })
      })
    },
    // 喜欢
    like(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        if( this.articleDetail.data.postInfo_postID.is_buzz==0){
          this.buzz_add(this.articleDetail.data);
        }else{
          this.buzz_delete(this.articleDetail.data);
        }
      }
    },
    buzz_add(item){
      blog.buzz_add(item.postID).then(res=>{
        console.log(res);
        if(res.status){
          this.articleDetail.data.postInfo_postID.is_buzz=1
        }
      })
    },
    buzz_delete(item){
      blog.buzz_delete(item.postID).then(res=>{
        console.log(res)
        if(res.status){
          this.articleDetail.data.postInfo_postID.is_buzz=0
        }
      })
    },
    // 收藏
    bookmark(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        this.articleDetail.data.postInfo_postID.is_bookmark?this.bookmark_delete(this.articleDetail.data):this.bookmark_add(this.articleDetail.data)
      }
    },
    bookmark_add(item){
      blog.bookmark_add(item.postID).then(res=>{
        // console.log(res);
        if(res.status)this.articleDetail.data.postInfo_postID.is_bookmark=1;
      })
    },
    bookmark_delete(item){
      blog.bookmark_delete(item.postID).then(res=>{
        // console.log(res);
        if(res.status)this.articleDetail.data.postInfo_postID.is_bookmark=0;
      })
    },
    gotoEditor(item){
      let url = '/blog/my/editor/?postid='+item.postID;
      this.$router.push(url);
    },
    article_delete(item){
      blog.article_delete(item.postID,0).then(res=>{
        if(res.status){
          this.$router.push('/blog/')
        }
      })
    },
    user_login_wxc_to_haiwai(token){
      let user =this.$store.state.user;
      if(this.token && !user.userinfo.id){
        user.user_login_wxc_to_haiwai(token).then(res=>{
            console.log(res);
        })
      }
    }
  },
  data() {
    return {
      // showLogin:false,
      icons:icons,
      loginuserID:-1,
      showcomment:false,
      articleDetail: {},
      comment:[],
      replymsgbody:"",
      replybtndisable:true,
      lastID:0,
      noMore:false,
      loading:{comment:false},
      share:{
        showShareBar:false,
        wechatQR:false
      },
      shareItem:{
        url:window.location.href,
        title:'',
        description:'',
        QRcode:'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='+window.location.href
      },
      shareNetworks:[
        {
          network:'facebook',
          icon:icons.facebook
        },{
          network:'twitter',
          icon:icons.twitter
        },{
          network:'line',
          icon:icons.line
        },{
          network:'whatsapp',
          icon:icons.whatsapp
        },{
          network:'weibo',
          icon:icons.weibo
        }

      ],
      recommend:{
        authorArticle:[],
        articles:[],
        props:{
          lastID:0,
          tags:''
        }
      },
      token:this.$route.query.haiwai_token,
    };
  },
   filters: {
    formatDate(time) {
    var date = new Date(time);
    return formatDate(date, 'yyyy-MM-dd hh:mm');
    },
    textTrans(string) {
      return textTrans(string);
    }
  }
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
        background-color: #f6f9fc;
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
.article-page .commentlable{
  border-bottom: #ddd 1px solid;
  padding:10px 0;
  margin-bottom: 12px;
}
.article-page .comment textarea{
  border: #ddd 1px solid ;
}

/* r2 */
.shareIcons a:hover{
  text-decoration: none;
}
.shareIcon svg{
  width:28px;
  height:28px;
}
.article-page h4{
  margin-bottom: 0;
}
.article-page .content{
  padding-top:1rem;
}
.article-page .content p img,
.article-page .content p iframe{
  width:85%;
  margin:0 auto;
  display:block;
}
.article-page .blogger-box{
  width:300px
}
.article-page .media-icons .btn{
  font-size: 1.5rem !important;
  margin:-5px 0 0 0;
}

@media (max-width: 575.98px){
  .article-page .blogger-box{
    width:220px
  }
  .article-page .content p img,
  .article-page .content p iframe{
    width:100%;
  }
}
</style>
