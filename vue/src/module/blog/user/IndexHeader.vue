<template>
    <div class="blog-user-index mb-3 col-sm-12 col-12 " v-if="data.id">
        <div v-show="data.bloggerinfo_bloggerID" class="user-bg d-flex" v-bind:style="{backgroundImage:'url('+data.bloggerinfo_bloggerID.background+')',backgroundSize:'cover'}">
            <div class="user-bgup flex-fill">
                <span class="name" slot="reference">{{data.bloggerinfo_bloggerID.name}}</span>

                <p class="bdescription" v-html="data.bloggerinfo_bloggerID.description"></p>
            </div>
            <div class="pr-3 pt-auto" style="position:absolute;right:10px;top:10px" v-if="data.id==$store.state.user.userinfo.UserID">
                <el-popover
                    placement="bottom-end"
                ref="blog_home_setting"
                v-model="bubbles.blog_home_setting"
                width="300"
                popper-class="bubble"
                trigger="manual"
                >
                    <p>{{user.userinfo.bubble.instruction.blog_home_setting}}</p>
                    <div style="text-align: right; margin: 0">
                        2/3
                        <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('blog_home_setting')">知道了</el-button>
                    </div>
                    <n-button v-if="data.bloggerID!==0" @click="$router.push('/profile/?id=0')" size="sm" slot="reference"><span class="d-none d-sm-block">博客设置</span><i class="el-icon-setting d-sm-none"></i></n-button>
                </el-popover>
                <el-button v-if="data.bloggerID==0"  type="primary" @click="$router.push('/blog_register')">开通博客</el-button>
            </div>
        </div>
        
        <div class="user-avatar d-flex py-2">
            <div class="avatarbox" @click="go()">
                <div 
                v-if="!data.userinfo_id.avatar" 
                class="avatar-word" :style="{height:'150px',width:'150px',lineHeight:'150px'}">{{data.userinfo_id.first_letter}}</div>
                <img 
                v-if="data.userinfo_id.avatar" 
                :src="data.userinfo_id.avatar" 
                :alt="data.userinfo_id.username">
            </div>
            
            <div class="flex-fill">
                <span style="color:#39b8eb;font-size:0.8rem" v-if="false"><icon-pen style="width:14px;fill:#39b8eb"></icon-pen>编辑</span>
               <div class="row textgroup">
                <span class="col-auto mr-2 blog-user-index-des name"><icon-V class="mr-2 text-primary lable" v-if="data.userinfo_id.is_hot_blogger"></icon-V>{{data.userinfo_id.username}}</span>
                <span class="col-9 blog-user-index-des x">{{data.userinfo_id.description}} </span>
                </div>
                <span v-if="data.bloggerID" class="blog-user-index-des">博客访问：{{data.bloggerinfo_bloggerID.count_read}}</span>
                <span class="blog-user-index-des ml-4">粉丝：{{data.userinfo_id.count_follower}}</span>
                <div class="float-right pr-4 qqh" v-if="data.id!=$store.state.user.userinfo.UserID">
                    <n-button  
                    link 
                    size="sm"
                    @click="openModal()"
                    >
                        <icon-mail style="width:25px;fill:#39b8eb"></icon-mail> <span style="color:#39b8eb;font-size:1rem;">发悄悄话</span>
                    </n-button>
                    
                    <n-button 
                    :type="data.userinfo_id.is_following?'text':'primary'" 
                    round 
                    size="sm"
                    @click="follow"
                    class="editbtn ml-3"
                    >
                        <icon-plus :style="{fill:'#fff'}"></icon-plus>{{data.userinfo_id.is_following?'已关注':'关注'}}
                    </n-button>
                </div>
            </div>
            <div class="pr-3" v-if="data.id==$store.state.user.userinfo.UserID">
                <el-popover
                    placement="bottom-end"
                ref="blog_home_profile"
                v-model="bubbles.blog_home_profile"
                width="300"
                popper-class="bubble"
                trigger="manual"
                >
                    <p>{{user.userinfo.bubble.instruction.blog_home_profile}}</p>
                    <div style="text-align: right; margin: 0">
                        2/3
                        <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('blog_home_profile')">知道了</el-button>
                    </div>
                    <n-button size="sm" @click="$router.push('/profile/?id=1')" slot="reference">
                        <span class="d-none d-sm-block">账号设置</span>
                        <i class="el-icon-setting d-sm-none"></i>
                    </n-button>
                </el-popover>  
            </div>

        </div>
        
    <!-- Send QQH Modal -->
    <modal :show.sync="modals.sendQqhModal" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">向 {{data.userinfo_id.username}} 发送悄悄话</h4>
      
      <div class="datepicker-container d-flex justify-content-center">
        <el-input
        type="textarea"
        :rows="2"
        placeholder="请输入内容"
        v-model="modals.qqhMsgbody">
        </el-input>
      </div>
      
      <template slot="footer">
          <span v-if="modals.modalData" :class="{'text-success':modals.modalData.status,'text-danger':!modals.modalData.status,}">{{modals.modalData.status?'发送成功':modals.modalData.error}}</span>
        <n-button 
        class="mr-3"
        type="default" 
        link
        @click.native="modals.sendQqhModal = false"
        >
          取消
        </n-button>
        <n-button 
        type="primary"
        round 
        simple
        @click.native="sendQqh(data)"
        >
          发送
        </n-button>
      </template>
    </modal>
    <login-dialog ref="dialog"></login-dialog>
    </div>
</template>
<script>
import {
    IconPen,
    IconMail,
    IconPlus,
    IconV
} from '@/components/Icons';
import {
    Button,
    Modal,
} from '@/components';
import { Input } from 'element-ui';
import blog from '../blog.service';
import account from '../../user/service/account';
import LoginDialog from '../../user/login/LoginDialog';

export default {
    name: 'blog-user-index-header',
    props:{
        info:Object
    },
    watch:{
        'info':function(val){
            this.data = val;
            this.init_data(val);
        }
    },
    components:{
        IconPen,
        IconMail,
        IconPlus,
        IconV,
        [Button.name]: Button,
        [Input.name]: Input,
        Modal,
        LoginDialog
    },
    beforeMount:function(){
        this.init_data(this.data);
    },
    mounted() {
        this.showBubble();
    },
    methods:{
        // getInfo(){
        //     blog.blogger_info(this.bloggerID).then(res=>{
        //         this.data = res.data;
        //         this.data.bloggerinfo_bloggerID.background = this.data.bloggerinfo_bloggerID.background?this.data.bloggerinfo_bloggerID.background:this.defaultBackground;
        //         console.log(this.data,this.$store.state.user.userinfo);
        //     })
        // },
        init_data(data){
            if(!data.bloggerID){
                this.data.bloggerID = 0 ;
                this.data.bloggerinfo_bloggerID = this.default_blginfo;
            }
            
        },
        openModal(){
            if(this.$store.state.user.userinfo.UserID!==undefined){
                this.modals.sendQqhModal=true
            }else{
                this.$refs.dialog.isLogin()
            }
        },
        sendQqh(data){
            this.send(this.$store.state.user.userinfo.id,data.id,this.modals.qqhMsgbody);
        },
        async send(userID,touserID,msgbody) {
            let user = this.$store.state.user;
            let res = await user.sendQqh(userID,touserID,msgbody);
          
            this.modals.modalData=res
          
            setTimeout(()=>{
                this.modals.modalData=undefined;
                this.modals.sendQqhModal=res.status?false:true;
            },4000)
        //   console.log(res);
        },
        go(){
            let url=this.$route.path
            let isidx = url.indexOf('blog/user')
            if(isidx==-1){
                this.$router.push('/blog/user/' + this.data.id);
            }
        },
        following_add(id){console.log(this.$store.state.user.userinfo)
            if(this.$store.state.user.userinfo.UserID!==undefined){
                account.following_add(id).then(res=>{
                if(res.status) {
                    this.data.userinfo_id.is_following = 1;
                }else{
                    this.$message.error(res.error);
                }
                });
            }else{
                this.$refs.dialog.isLogin()
            }
        },
        following_delete(id){console.log("remove")
            if(this.$store.state.user.userinfo.UserID!==undefined){
                account.following_delete(id).then(res=>{
                    if(res.status == true) {
                        this.data.userinfo_id.is_following = 0;
                    }else{
                        this.$message.error(res.error);
                    }
                });
            }
        },
        follow(){
            if(this.data.userinfo_id.is_following){
                this.following_delete(this.data.id)
            }else{
                this.following_add(this.data.id)
            }
        },
        showBubble(){console.log(this.user.userinfo.UserID,this.data.id)
            var bubbles=['blog_home_manage','blog_home_profile','blog_home_setting'];
            for(let i=0;i<bubbles.length;i++){
                let type = bubbles[i]
                if(this.user.userinfo.bubble.user[type]==1 && this.user.userinfo.UserID==this.data.id){
                this.bubbles[type]=true;
                return
                }else{
                this.bubbles[type]=false;
                }
            };
        },
        removeBubble(type){
            this.user.remove_bubble(type).then(res=>{
                if(res.status){
                this.user.userinfo.bubble = res.data;
                this.showBubble();
                }
            })
        },
    },
    data() {
        return {
            user:this.$store.state.user,
            default_blginfo:{
                background : '/img/default_bg.jpg',
                name : "未开通博客",
                description : "此用户尚未开通博客..."},
            data:this.info,
            modals:{
                sendQqhModal:false,
                qqhMsgbody:'',
                modalData:undefined
            },
            bubbles:{
                blog_home_manage:false,
                blog_home_profile:false,
                blog_home_setting:false
            }
        };
    },
}
</script>
<style>
.blog-user-index .user-bg{
    background-size:cover;
    background-position-y: center;
    background-color: #fbfbfb
}
.blog-user-index .user-bgup{
    height:230px;
    padding: 106px 30px 0 165px;
    background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.3) 100%);
    background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.3) 100%)
}
.blog-user-index .user-bgup .name{
    font-size:1.6rem;
    padding: 6px 0;
    color:white;
 text-shadow: 1px 1px 0 #0000000f,
    -1px 1px 0 #0000000f,
    1px -1px 0 #0000000f,
    -1px -1px 0 #0000000f,
    0px 1px 0 #0000000f,
    0px -1px 0 #0000000f,
    -1px 0px 0 #0000000f,
    1px 0px 0 #0000000f,
    2px 2px 0 #0000000f,
    -2px 2px 0 #0000000f,
    2px -2px 0 #0000000f,
    -2px -2px 0 #0000000f,
    0px 2px 0 #0000000f,
    0px -2px 0 #0000000f,
    -2px 0px 0 #0000000f,
    2px 0px 0 #0000000f,
    1px 2px 0 #0000000f,
    -1px 2px 0 #0000000f,
    1px -2px 0 #0000000f,
    -1px -2px 0 #0000000f,
    2px 1px 0 #0000000f,
    -2px 1px 0 #0000000f,
    2px -1px 0 #0000000f,
    -2px -1px 0 #0000000f;
}
.blog-user-index .user-avatar{
    background-color: #f6f6f6;
}
.blog-user-index .user-avatar img,
.blog-user-index .avatar-word{
    min-width: 150px;
    width:150px;
    height: 150px;
    margin: -62px 10px 10px 10px;
    border:2px white solid;
    border-radius: 50%;
}
.blog-user-index .blog-user-index-name{
    font-size: 1.125rem;
    font-weight: 700;
    text-align:center
}
.blog-user-index .bdescription{
        font-size: 19px;
        color: #fff;
        text-shadow: 0 0 4px rgb(0 0 0 / 50%);
        font-weight: 500;
        line-height: 26px;
}
.blog-user-index .blog-user-index-des{
        font-size: 1rem;
        color: gray;
        display: inline-block;
        margin-top: 11px;
}
.blog-user-index .blog-user-index-des.x{
         margin: 15px 15px 15px 0;
         color: #647685;
         font-size: 1.1rem;
         padding-left:0;
}
.blog-user-index .blog-user-index-des.name{
         font-size: 1.4rem;
         color:black;
         padding-right: 0;
         font-weight:400;
         margin:12px 0 12px 0
}       
.blog-user-index .avatar-word{
    background-color: aliceblue;
    text-align: center;
    font-weight: 500;
    font-size: 36px;
    line-height: 90px;
}
.el-popover.bubble {
  background-color: #14171a;
  color:white
}
.el-popover.bubble .popper__arrow,
.el-popover.bubble .popper__arrow::after{
  border-top-color: #14171a;
  border-bottom-color: #14171a;
}
@media (max-width: 767.98px) {
     .blog-user-index .bdescription,
     .blog-user-index .blog-user-index-des.x{
     display:none
     }
.blog-user-index .user-bgup {
        background: no-repeat;
        padding: 20px 10px;
        height: 20vw;
}
.blog-user-index.user-bg.d-flex{
    background-image: no;

    }
.blog-user-index .qqh {
        margin: 10px;
        width: 100%;
        float: left
 }       
.blog-user-index .user-avatar img,
.blog-user-index .avatar-word{
        min-width: 60px;
        width:60px;
        height: 60px;
        margin: -7px 10px 10px 10px;
        border:2px white solid;
        border-radius: 50%;
    }
.blog-user-index .editbtn {
    border-color: #888888e6!important;
}
.blog-user-index  .row.textgroup{
    flex-wrap: inherit
}
.blog-user-index{
        padding: 0 !important;
}
}
</style>