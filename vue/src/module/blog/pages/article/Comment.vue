<template>
  <div class="comment">
    <div v-for="(item, index) in data" :key="index" class="d-flex align-items-start mt-2">
        <avatar :data="item.userinfo_userID" :imgHeight="38" class="mr-2"></avatar>
        <div>
            <span class="replyName">{{item.userinfo_userID.username}}</span><span class="replyTime">{{item.edit_date*1000 | formatDate}}</span>
            <p class="replyContent" v-html="item.postInfo_postID.msgbody"></p>
            <p class="replyFoot">
              <span 
              @click="like(item)">
                <icon-like-outline 
                :style="{fill:item.postInfo_postID.is_buzz==1?'#39b8eb':'gray',height:'18px',cursor:'pointer'}"></icon-like-outline></span>
              {{item.countinfo_postID.count_buzz}} <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message>
              <a href="#" style="color:gray"
              @click="reply(item)">回复</a>
            <a
            v-if="item.userID==loginuserID" class="ml-5" style="color:gray" >删除</a></p>
            <!-- <div v-if="item.replies.length>0"> https://cloud.tencent.com/developer/article/1360724 -->
            <div v-if="item.reply.length>0">
              <div :id="item.postID" style="display:none">
                <div v-for="(r,idx) in item.reply" :key="'r'+idx" class="d-flex align-items-start mt-2">
                  <avatar :data="r.userinfo_userID" :imgHeight="32" class="mr-2"></avatar>
                  <div>
                      <span class="replyName">{{r.userinfo_userID.username}}</span>
                      <span class="ml-2 text-primary" style="font-size:0.85rem" v-if="item.has_author">[作者]</span>
                      <span class="replyTime">{{r.edit_date*1000 | formatDate}}</span>
                      <p class="replyContent" v-html="r.postInfo_postID.msgbody"></p>
                      <p class="replyFoot">
                        <span @click="like(r)"><icon-like-outline :style="{fill:r.postInfo_postID.is_buzz==1?'#39b8eb':'gray',height:'18px',cursor:'pointer'}"></icon-like-outline></span>{{r.countinfo_postID.count_buzz}}
                        <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message>
                        <a href="#" style="color:gray" @click="reply(r)">回复</a>
                        <a v-if="r.userID==loginuserID" class="ml-5" style="color:gray">删除</a>
                      </p>
                  </div>
                </div>
              </div>
              <button class="btn btn-link btn-info" style="padding-left:0px" v-on:click="replystatus(item.postID)">{{replyshowstatus}} {{item.reply.length}} 条回复<span class="ml-2" v-if="item.has_author">[含作者]</span></button>
            </div>
        </div>
    </div>
<!-- 回复 modal -->
    <modal :show.sync="modals.reply" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px"><span class="text-muted">回复</span> {{replyusername}} <span class="text-muted">评论</span></h4>
      <textarea type="textarea" v-model="replymsgbody" rows="3" class="w-100 my-2" placeholder="写下您的评论..." @keyup="checkstatus"></textarea>
      <template slot="footer">
        <n-button 
        class="mr-3"
        type="default" 
        link
        @click.native="modals.reply = false"
        >
          取消
        </n-button>
        <n-button 
        type="primary"
        round 
        simple
        :disabled="replybtndisable"
        @click="article_reply_add()"
          >回复</n-button
        >
      </template>
    </modal>
<!-- 登录 modal -->
    <modal :show.sync="modals.login" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px"><span class="text-muted">登录/注册</span></h4>
      <p>待开发！！！</p>
      <template slot="footer">
        <n-button 
        class="mr-3"
        type="default" 
        link
        @click.native="modals.login = false"
        >
          取消
        </n-button>
        
      </template>
    </modal>
  </div>
</template>
<script>
import {IconLikeOutline,IconMessage} from '@/components/Icons';
import { Modal, Button } from '@/components';
import Avatar from '../components/Main/Avatar';
import account from '../../../user/service/account';
import {formatDate} from '@/directives/formatDate.js';
import blog from '../../blog.service';

export default {
  name: 'comment',
  props:{
      data:{}
  },
  components: {
      Avatar,
      IconLikeOutline,
      IconMessage,
      Modal,
      [Button.name]: Button,
  },
  mounted: function () {
    account.login_status().then(res=>{ //判断是否登录
      if(res.data.data==undefined){
        this.loginuserID = -1
      }else{
        this.loginuserID = res.data.data.UserID ;
      }
    });
  },
  methods:{
    // logedin () {
    //   let status;
    //   account.login_status().then(res=>{ //判断是否登录
    //     if(res.data.data==undefined){
    //       status = false;
    //     }else{
    //       status = true ;
    //     }
    //     return status;
    //   })
    // },
    // 点赞、取消点赞
    like(item){
      if(this.loginuserID!=-1){
        item.postInfo_postID.is_buzz==0?this.buzz_add(item.postID):this.buzz_delete(item.postID);
      }else{
        this.modals.login = true ;
      }
      
    },
    buzz_add(id){
      blog.buzz_add(id).then(res=>{
        console.log(res);
        this.regetComment();
      })
    },
    buzz_delete(id){
      blog.buzz_delete(id).then(res=>{
        console.log(res)
        this.regetComment();
      })
    },
    // 回复
    reply(item){
      this.modals.reply = true;
      this.replyusername = item.userinfo_userID.username;
      this.replyID = item.postID;
    },
    article_reply_add(){
      let obj = {
        article_data:{msgbody:this.replymsgbody,
        postID:this.replyID,
        typeID:1}
      }
      this.replybtndisable = true;
      blog.article_reply_add(obj).then(res=>{
        this.replybtndisable = false;
        this.regetComment();
        this.replymsgbody="";
        this.modals.reply = false;
      })
    },
    // 删除回复
    article_reply_delete(){
      
    },
    regetComment(){
      this.$emit('reget-commnet')
    },
    checkstatus(){
      this.replybtndisable = this.replymsgbody?false:true;
    },
    replystatus(id){
      let obj = document.getElementById(id);
      obj.style.display = obj.style.display=='none'?'block':'none';
      this.replyshowstatus = obj.style.display=='none'?'显示':'隐藏';
    }
  },
  data(){
    return {
      loginuserID:0,
      replyshowstatus:'显示',
      replymsgbody:'',
      replyusername:'',
      replybtndisable:true,
      replyID:0,
      modals: {
        reply: false,
        delete: false,
        login:false
      },
    }
  },
  filters: {
    formatDate(time) {
        var date = new Date(time);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  }

};
</script>
<style>

.fontsize0 .comment .replyContent{
  font-size: 1rem;
}
.fontsize1 .comment .replyContent{
  font-size: 1.2rem;
}
.fontsize2 .comment .replyContent{
  font-size: 1.4rem;
}

.comment .replyContent,.comment .replyFoot{
    font-size: 1rem;
    margin-bottom: 3px;
}
.comment .replyName{
    font-weight: 700;
}
.comment .replyTime{
    color:gray;
    font-size: .875rem;
    margin-left: 1rem;
}

@media (max-width: 575.98px){

}
</style>
