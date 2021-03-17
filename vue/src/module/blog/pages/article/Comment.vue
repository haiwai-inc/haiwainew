<template>
  <div class="comment d-flex align-items-start mt-2">
    
    <!-- <div v-for="(item, index) in data" :key="index" class="d-flex align-items-start mt-2"> -->
        <avatar :data="data.userinfo_userID" :imgHeight="38" class="mr-2"></avatar>
        <div class="flex-fill">
          <div class="d-flex w-100">
            <span class="replyName">{{data.userinfo_userID.username}}</span>
            <span class="replyTime">{{data.edit_date*1000 | formatDate}}</span>
            <span class="ml-auto">
              <drop-down
              class="nav-item dropdown"
              :haiwaiIcon="iconmore3v"
              :hideArrow="true"
              haiwaiClass="haiwaiicon"
              style="padding:0;"
            >
              <a class="dropdown-item" href="#">举报</a>
              <a class="dropdown-item pl-4" href="javascript:void(0)" @click="blockUser(data.userID)">加入黑名单</a>
            </drop-down>
            </span>
          </div>
      
          <p class="replyContent" v-html="data.postInfo_postID.msgbody"></p>
          <p class="replyFoot">
            <span 
            @click="like(data)">
              <icon-like-outline 
              :style="{fill:data.postInfo_postID.is_buzz==1?'#39b8eb':'gray',height:'18px',cursor:'pointer'}"></icon-like-outline></span>
            {{data.countinfo_postID.count_buzz}} <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message>
            <el-popover 
              placement="bottom-start"
              width="375" 
              :ref="'pop-'+data.postID"
              trigger="click">
                <textarea  type="textarea" v-model="replymsgbody" rows="3" class="w-100 my-2 p-2" placeholder="写下您的评论..." @keyup="checkstatus"></textarea>
                <n-button 
                type="primary"
                round 
                simple
                :disabled="replybtndisable"
                @click="reply_add()"
                  >回复</n-button
                >
                <a href="javascript:void(0)" slot="reference" @click="reply(data)" style="color:gray"><span>回复</span></a>
              </el-popover>
            <!-- <a style="color:gray" @click="reply(data)">回复</a> -->
            <el-popconfirm v-if="data.userID==loginuserID"
              placement="top-end"
              confirm-button-text='删除'
              cancel-button-text='取消'
              title="确定删除这条回复吗？"
              :hide-icon="true"
              @confirm="article_reply_delete(data.postID)"
            >
              <a href="javascript:void(0)" slot="reference" class="ml-5" style="color:gray">删除</a>
            </el-popconfirm>
          </p>
          <!-- <a
          v-if="item.userID==loginuserID" class="ml-5" style="color:gray" >删除</a> -->
          <!-- <div v-if="item.replies.length>0"> https://cloud.tencent.com/developer/article/1360724 -->
          <div v-if="data.reply">
            <div :id="data.postID" style="display:none">
              <div v-for="(r,idx) in data.reply" :key="'r'+idx" class="d-flex align-items-start mt-2">
                <avatar :data="r.userinfo_userID" :imgHeight="32" class="mr-2"></avatar>
                <div class="flex-fill">
                  <div class="d-flex w-100">
                    <span class="replyName">{{r.userinfo_userID.username}}</span>
                    <span class="ml-2 text-primary" style="font-size:0.85rem" v-if="r.userID==author">[作者]</span>
                    <span class="replyTime">{{r.edit_date*1000 | formatDate}}</span>
                    <span class="ml-auto">
                      <drop-down
                      class="nav-item dropdown"
                      :haiwaiIcon="iconmore3v"
                      :hideArrow="true"
                      haiwaiClass="haiwaiicon"
                      style="padding:0;"
                    >
                      <a class="dropdown-item" href="#">举报</a>
                      <a class="dropdown-item pl-4" href="javascript:void(0)" @click="blockUser(r.userID)">加入黑名单</a>
                    </drop-down>
                    </span>
                  </div>
                    
                    <p class="replyContent" v-html="r.postInfo_postID.msgbody"></p>
                    <p class="replyFoot">
                      <span @click="like(r)"><icon-like-outline :style="{fill:r.postInfo_postID.is_buzz==1?'#39b8eb':'gray',height:'18px',cursor:'pointer'}"></icon-like-outline></span>{{r.countinfo_postID.count_buzz}}
                      <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message>
                      <el-popover 
                      placement="bottom-start"
                      width="375" 
                      @show="reply(r)"
                      :ref="'pop-'+r.postID"
                      trigger="click">
                        <textarea style="border: #ddd 1px solid;" type="textarea" v-model="replymsgbody" rows="3" class="w-100 my-2" placeholder="写下您的评论..." @keyup="checkstatus"></textarea>
                        <n-button 
                          type="primary"
                          round 
                          simple
                          :disabled="replybtndisable"
                          @click="reply_add()"
                            >回复</n-button
                          >
                        <a href="javascript:void(0)" slot="reference" style="color:gray" ><span>回复</span></a>
                      </el-popover>
                      
                      <el-popconfirm v-if="r.userID==loginuserID"
                        placement="top-end"
                        confirm-button-text='删除'
                        cancel-button-text='取消'
                        title="确定删除这条回复吗？"
                        :hide-icon="true"
                        @confirm="article_reply_delete(r.postID)"
                      >
                        <a href="javascript:void(0)" slot="reference" class="ml-5" style="color:gray;">删除</a>
                      </el-popconfirm>
                    </p>
                </div>
              </div>
            </div>
            <button 
            v-if="data.reply.length>0"
            class="btn btn-link btn-info" 
            style="padding-left:0px" 
            v-on:click="replystatus(data.postID)">
              {{replyshowstatus}} {{data.reply.length}} 条回复<span class="ml-2" v-if="has_author">[含作者]</span>
            </button>
          </div>
      </div>
    <!-- </div> -->
<!-- 回复 modal -->
    <modal :show.sync="modals.reply" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px"><span class="text-muted">回复</span> {{currentItem.userinfo_userID.username}} <span class="text-muted">评论</span></h4>
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
        @click="reply_add()"
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
import HaiwaiIcons from "@/components/Icons/Icons";
import { Modal, Button,DropDown } from '@/components';
import Avatar from '../components/Main/Avatar';
import {formatDate} from '@/directives/formatDate.js';
import blog from '../../blog.service';
import {Popover} from 'element-ui';

export default {
  name: 'comment',
  props:{
      data:{},
      author:Number,
  },
  components: {
      Avatar,
      IconLikeOutline,
      IconMessage,
      Modal,
      DropDown,
      [Button.name]: Button,
      [Popover.name]:Popover,
  },
  mounted: function () {
    let userinfor = this.$store.state.user.userinfo
    this.loginuserID = userinfor?userinfor.UserID:-1;
    // console.log(this.data)
  },
  computed:{
    has_author () {
      let s = 0
      this.data.reply.forEach(obj=>{
        if(obj.userID == this.author) {
          s++
        } ;
      })
      return s>0?true:false
    }
  },
  methods:{
    // logedin () {
    //   let status;
    //   account.login_status().then(res=>{ //判断是否登录
    //     if(res.data==undefined){
    //       status = false;
    //     }else{
    //       status = true ;
    //     }
    //     return status;
    //   })
    // },
    // 点赞、取消点赞
    like(item){
      
      this.currentItem = item ;
      if(this.loginuserID!=-1){
        item.postInfo_postID.is_buzz==0?this.buzz_add(item):this.buzz_delete(item);
      }else{
        // this.modals.login = true ;
        this.$emit('opendialog')
      }
        // console.log(this.currentItem);
      
    },
    buzz_add(item){
      blog.buzz_add(item.postID).then(res=>{
        // console.log(res);
        this.regetComment();
      })
    },
    buzz_delete(item){
      blog.buzz_delete(item.postID).then(res=>{
        // console.log(res)
        this.regetComment();
      })
    },
    // 回复
    reply(item){
      this.currentItem = item ;console.log(item);
        if(item.treelevel==2){
          this.replymsgbody = "@"+ item.userinfo_userID.username +"  ";
        }else{
          this.replymsgbody = "";
        }
    },
    reply_add(){
      console.log(this.loginuserID);
      let obj = {
        article_data:{msgbody:this.replymsgbody,
        postID:this.currentItem.treelevel==2?this.currentItem.basecode:this.currentItem.postID,
        typeID:1}
      }
      let pop = 'pop-'+this.currentItem.postID;
      this.replybtndisable = true;
      if(this.loginuserID!=-1){
        blog.reply_add(obj).then(res=>{
          if(res.status){
            this.regetComment();
            this.replybtndisable = false;
            this.currentItem.treelevel==2?this.$refs[`${pop}`][0].doClose():this.$refs[`${pop}`].doClose();
            this.replymsgbody="";
          }
        })
      }else{
        this.$emit('opendialog')
      }
    },
    // 删除回复
    article_reply_delete(id){
      blog.reply_delete(id).then(res=>{
        console.log("Del",id);

      })
    },
    regetComment(){
      let id = 0;
      if(this.currentItem.treelevel==2){
        id = this.currentItem.basecode;
      }else{
        id = this.currentItem.postID;
      }
      this.$emit("regetcomment",id);
    },
    checkstatus(){
      this.replybtndisable = this.replymsgbody?false:true;
    },
    replystatus(id){
      let obj = document.getElementById(id);
      obj.style.display = obj.style.display=='none'?'block':'none';
      this.replyshowstatus = obj.style.display=='none'?'显示':'隐藏';
    },
    //拉黑
    blockUser(id){
      this.$store.state.user.blacklist_add(id).then(res=>{
        console.log(res)
      })
    }
  },
  data(){
    return {
      iconmore3v: HaiwaiIcons.iconmore3v,
      loginuserID:-1,
      replyshowstatus:'显示',
      replymsgbody:'',
      replyusername:'',
      replybtndisable:true,
      noMore:false,
      currentItem:{userinfo_userID:{username:''}},
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
.comment .haiwaiicon svg{
  fill:grey
}
@media (max-width: 575.98px){

}
</style>
