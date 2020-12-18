<template>
    <div class="blog-user-index">
        <div class="user-bg" v-bind:style="{backgroundImage:'url('+data.bloggerinfo_id.background+')'}">
            <div class="user-bgup"></div>
        </div>
        <div class="user-avatar d-flex py-2">
            <div class="avatarbox">
                <div 
                v-if="!data.userinfo_userID.avatar" 
                class="avatar-word" :style="{height:'90px',width:'90px',lineHeight:'90px'}">{{data.userinfo_userID.first_letter}}</div>
                <img 
                v-if="data.userinfo_userID.avatar" 
                :src="data.userinfo_userID.avatar" 
                :alt="data.userinfo_userID.username">
            </div>
            
            <div class="flex-grow-1">
                <span class="blog-user-index-name">{{data.userinfo_userID.username}}</span><br>
                <span class="blog-user-index-des">{{data.bloggerinfo_id.description}} </span>
                <span style="color:#39b8eb;font-size:0.8rem" v-if="false"><icon-pen style="width:14px;fill:#39b8eb"></icon-pen>编辑</span>
                <br>
                <span class="blog-user-index-des">博客访问：{{data.bloggerinfo_id.count_read}}</span>
                <span class="blog-user-index-des ml-4">粉丝：{{data.bloggerinfo_id.count_follower}}</span>
            </div>
            <div class="pr-3">
                <n-button  
                link 
                size="sm"
                @click="openModal()"
                >
                    <icon-mail style="width:25px;fill:#39b8eb"></icon-mail> <span style="color:#39b8eb;font-size:0.9rem;">发悄悄话</span>
                </n-button>
                
                <n-button 
                :type="data.userinfo_userID.is_follower?'default':'primary'" 
                round 
                simple 
                @click="$router.push('/blog/user/1')"
                class="editbtn ml-3"
                size="sm"
                >
                    <icon-plus :style="data.userinfo_userID.is_follower?{fill:'#888888'}:{fill:'#39b8eb'}"></icon-plus>{{data.userinfo_userID.is_follower?'已关注':'关注'}}
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
          <span :class="{'text-success':modals.modalData.status,'text-danger':!modals.modalData.status,}">{{modals.modalData.data}}</span>
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
        @click.native="sendQqh(data.id)"
        >
          发送
        </n-button>
      </template>
    </modal>
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

export default {
    name: 'blog-user-index-header',
    components:{
        IconPen,
        IconMail,
        IconPlus,
        [Button.name]: Button,
        [Input.name]: Input,
        Modal,
        
    },
    mounted:function(){
        blog.blogger_info(this.$route.params.id).then(res=>{
            this.data = res.data.data;
            this.data.bloggerinfo_id.background = this.data.bloggerinfo_id.background?this.data.bloggerinfo_id.background:this.defaultBackground;
            console.log(this.data);
        })
    },
    methods:{
        
    },
    data() {
        return {
            defaultBackground:'/img/bg5.jpg',
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
                    is_follower:0
                }
            },
            modals:{
                sendQqhModal:false,
                qqhMsgbody:'',
                modalData:{}
            },
        };
    },
    methods:{
        openModal(){
            this.modals.sendQqhModal=true
        },
        sendQqh(id){
            this.send(1,id,this.modals.qqhMsgbody);
        },
        async send(userID,touserID,msgbody) {
          let user = this.$store.state.user;
          let res = await user.sendQqh(userID,touserID,msgbody);
          this.modals.modalData=res.data
            setTimeout(()=>{
                this.modals.modalData={};
                this.modals.sendQqhModal=res.data.status?false:true;
            },2000)
        //   console.log(res);
        }
    }
}
</script>
<style>
.blog-user-index .user-bg{
    background-size:cover;
    background-position-y: center
}
.blog-user-index .user-bgup{
    height:100px
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
.blog-user-index .blog-user-index-des{
    font-size: 0.85rem;
    color:gray
}
.blog-user-index .avatar-word{
    background-color: aliceblue;
    text-align: center;
    font-weight: 500;
    font-size: 36px;
    line-height: 90px;
}
</style>