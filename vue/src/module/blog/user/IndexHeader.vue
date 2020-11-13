<template>
    <div class="blog-user-index">
        <div class="user-bg" v-bind:style="{backgroundImage:'url('+authorInfo.backgroundImg+')'}">
            <div class="user-bgup"></div>
        </div>
        <div class="user-avatar d-flex py-2">
            <div>
                <img :src="authorInfo.avatarUrl" :alt="authorInfo.name">
            </div>
            
            <div class="flex-grow-1">
                <span class="blog-user-index-name">{{authorInfo.name}}</span><br>
                <span class="blog-user-index-des">{{authorInfo.description}} </span>
                <span style="color:#39b8eb;font-size:0.8rem"><icon-pen style="width:14px;fill:#39b8eb"></icon-pen>编辑</span><br>
                <span class="blog-user-index-des">博客访问：{{authorInfo.read}}</span>
            </div>
            <div class="pr-3">
                <n-button  
                link 
                size="sm"
                @click="openModal()"
                >
                    <icon-mail style="width:16px;fill:#39b8eb"></icon-mail> <span style="color:#39b8eb;font-size:0.8rem;">发悄悄话</span>
                </n-button>
                
                <n-button 
                :type="authorInfo.isFollowed?'default':'primary'" 
                round 
                simple 
                @click="$router.push('/blog/user/1')"
                class="editbtn ml-3"
                size="sm"
                >
                    <icon-plus :style="authorInfo.isFollowed?{fill:'#888888'}:{fill:'#39b8eb'}"></icon-plus>{{authorInfo.isFollowed?'已关注':'关注'}}
                </n-button>
            </div>
        </div>
        <div class="profile-header mt-2">
           <ul class="nav justify-content-center">
              <li class="col nav-item text-center px-0">
                 <a class="nav-link active" href="#">最新博文</a>
              </li>
              <li class="col nav-item text-center px-0">
                 <a class="nav-link" href="#">最热博文</a>
              </li>
              <li class="col nav-item text-center px-0">
                 <a class="nav-link" href="#">新评博文</a>
              </li>
           </ul>
        </div>
    <!-- Send QQH Modal -->
    <modal :show.sync="modals.sendQqhModal" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">向{{userID}}发送悄悄话</h4>
      
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
        @click.native="sendQqh(userID)"
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
    props:{
        userID:String,
    },
    data() {
        return {
            authorInfo :{
                avatarUrl:'/img/avatar.jpg',
                isHot:true,
                name:'English Name',
                firstLetter:'用',
                description:'简介简介简介简介 English desciption',
                isFollowed:false,
                read:12345,
                backgroundImg:'/img/bg11.jpg'
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
                // this.modals.sendQqhModal=res.data.status?false:true;
            },2000)
          console.log(res);
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
.blog-user-index .user-avatar img{
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
.profile-header .nav-link{
     color:#657786  
}
.profile-header .nav-link.active {
        color: #1D1D1D;
        border-bottom: 2px solid #39B8EB;
        font-weight: 600;
}

</style>