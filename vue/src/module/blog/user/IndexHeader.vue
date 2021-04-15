<template>
    <div class="blog-user-index mb-3 col-sm-12 col-12 ">
        <div class="user-bg" v-bind:style="{backgroundImage:'url('+data.bloggerinfo_id.background+')'}">
            <div class="user-bgup">
                <span class="name">{{data.bloggerinfo_id.name}}</span>
                <p class="bdescription text-white">{{data.bloggerinfo_id.description}}</p>
            </div>
        </div>
        <div class="user-avatar d-flex py-2">
            <div class="avatarbox" @click="go()">
                <div 
                v-if="!data.userinfo_userID.avatar" 
                class="avatar-word" :style="{height:'90px',width:'90px',lineHeight:'90px'}">{{data.userinfo_userID.first_letter.toUpperCase()}}</div>
                <img 
                v-if="data.userinfo_userID.avatar" 
                :src="data.userinfo_userID.avatar" 
                :alt="data.userinfo_userID.username">
            </div>
            
            <div class="flex-grow-1">
                <span class="blog-user-index-name">{{data.userinfo_userID.username}}</span><br>
                <span class="blog-user-index-des">{{data.userinfo_userID.description}} </span>
                <span style="color:#39b8eb;font-size:0.8rem" v-if="false"><icon-pen style="width:14px;fill:#39b8eb"></icon-pen>编辑</span>
                <br>
                <span class="blog-user-index-des">博客访问：{{data.bloggerinfo_id.count_read}}</span>
                <span class="blog-user-index-des ml-4">粉丝：{{data.userinfo_userID.count_follower}}</span>
            </div>
            <div class="pr-3" v-if="bloggerID==$store.state.user.userinfo.bloggerID">
                <n-button  
                link 
                size="sm"
                >博客设置</n-button>
            </div>
            <div class="pr-3" v-if="bloggerID!=$store.state.user.userinfo.bloggerID">
                <n-button  
                link 
                size="sm"
                @click="openModal()"
                >
                    <icon-mail style="width:25px;fill:#39b8eb"></icon-mail> <span style="color:#39b8eb;font-size:0.9rem;">发悄悄话</span>
                </n-button>
                
                <n-button 
                :type="data.userinfo_userID.is_following?'simple':'primary'" 
                round 
                size="sm"
                @click="follow"
                class="editbtn ml-3"
                >
                    <icon-plus :style="data.userinfo_userID.is_following?{fill:'#aba7a7'}:{fill:'#fff'}"></icon-plus>{{data.userinfo_userID.is_following?'已关注':'关注'}}
                </n-button>
            </div>
        </div>
        
    <!-- Send QQH Modal -->
    <modal :show.sync="modals.sendQqhModal" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">向 {{data.userinfo_userID.username}} 发送悄悄话</h4>
      
      <div class="datepicker-container d-flex justify-content-center">
        <el-input
        type="textarea"
        :rows="2"
        placeholder="请输入内容"
        v-model="modals.qqhMsgbody">
        </el-input>
      </div>
      
      <template slot="footer">
          <span v-if="modals.modalData" :class="{'text-success':modals.modalData.status,'text-danger':!modals.modalData.status,}">{{modals.modalData.data?'发送成功':'发送失败'}}</span>
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
        bloggerID:Number
    },
    watch:{
        'bloggerID':function(){
            this.getInfo();
        }
    },
    components:{
        IconPen,
        IconMail,
        IconPlus,
        [Button.name]: Button,
        [Input.name]: Input,
        Modal,
        LoginDialog
    },
    mounted:function(){
        this.getInfo();
    },
    methods:{
        getInfo(){
            blog.blogger_info(this.bloggerID).then(res=>{
                this.data = res.data;
                this.data.bloggerinfo_id.background = this.data.bloggerinfo_id.background?this.data.bloggerinfo_id.background:this.defaultBackground;
                console.log(this.data,this.$store.state.user.userinfo);
            })
        },
        openModal(){
            if(this.$store.state.user.userinfo.UserID!==undefined){
                this.modals.sendQqhModal=true
            }else{
                this.$refs.dialog.isLogin()
            }
        },
        sendQqh(data){
            this.send(this.$store.state.user.userinfo.id,data.userID,this.modals.qqhMsgbody);
        },
        async send(userID,touserID,msgbody) {
            let user = this.$store.state.user;
            let res = await user.sendQqh(userID,touserID,msgbody);
          
            this.modals.modalData=res
          
            setTimeout(()=>{
                this.modals.modalData=undefined;
                this.modals.sendQqhModal=res.status?false:true;
            },2000)
        //   console.log(res);
        },
        go(){
            let url=this.$route.path
            let isidx = url.indexOf('blog/user')
            if(isidx==-1){
                this.$router.push('/blog/user/' + this.data.bloggerID);
            }
        },
        following_add(id){console.log(this.$store.state.user.userinfo)
            if(this.$store.state.user.userinfo.UserID!==undefined){
                account.following_add(id).then(res=>{
                if(res.status) {
                    this.data.userinfo_userID.is_following = 1;
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
                        this.data.userinfo_userID.is_following = 0;
                    }else{
                        this.$message.error(res.error);
                    }
                });
            }
        },
        follow(){
            if(this.data.userinfo_userID.is_following){
                this.following_delete(this.data.userID)
            }else{
                this.following_add(this.data.userID)
            }
        }
    },
    data() {
        return {
            defaultBackground:'/img/default_bg.jpg',
            data:{
                bloggerinfo_id:{
                    background:'',
                    description:'',
                    count_read:0,
                    count_follower:0
                },
                id:0,
                userID:0,
                userinfo_userID:{
                    avatar:'',
                    username:'',
                    is_following:0
                }
            },
            modals:{
                sendQqhModal:false,
                qqhMsgbody:'',
                modalData:undefined
            },
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
    height:160px;
    padding-top: 60px;
    padding-left: 110px;
    background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.3) 100%);
    background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.3) 100%)
}
.blog-user-index .user-bgup .name{
    font-size:1.6rem;
    padding: 6px 0;
    color:white;
    text-shadow: 0 0 4px rgb(0 0 0 / 50%);
    border-radius: 4px;

}
.blog-user-index .user-avatar{
    background-color: #f6f6f6;
}
.blog-user-index .user-avatar img,
.blog-user-index .avatar-word{
    min-width: 90px;
    width:90px;
    height: 90px;
    margin: -30px 10px 10px 10px;
    border:2px white solid;
    border-radius: 50%;
}
.blog-user-index .blog-user-index-name{
    font-size: 1.125rem;
    font-weight: 700;
}
.blog-user-index .bdescription{font-size:18px}
.blog-user-index .blog-user-index-des{
        font-size: 0.9rem;
        color: gray;
        max-width: 393px;
        display: inline-block;
}
.blog-user-index .avatar-word{
    background-color: aliceblue;
    text-align: center;
    font-weight: 500;
    font-size: 36px;
    line-height: 90px;
}
</style>