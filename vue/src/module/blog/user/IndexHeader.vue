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
                <icon-mail style="width:16px;fill:#39b8eb"></icon-mail> <span style="color:#39b8eb;font-size:0.8rem;">悄悄话</span>
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
        <div class="ProfileMain-header">
           <ul class="nav justify-content-center border-bottom">
              <li class="nav-item">
                 <a class="nav-link active" href="#">最新博文</a>
              </li>
              <li class="nav-item">
                 <a class="nav-link" href="#">最热博文</a>
              </li>
              <li class="nav-item">
                 <a class="nav-link" href="#">最新评论</a>
              </li>
           </ul>
        </div>
    </div>
</template>
<script>
import {
    IconPen,
    IconMail,
    IconPlus,
} from '@/components/Icons';
import {
    Button
} from '@/components';
export default {
    name: 'blog-user-index-header',
    components:{
        IconPen,
        IconMail,
        IconPlus,
        [Button.name]: Button,
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
    };
    },
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
</style>