<template>
    <div class="blog-user-index">
        <div class="user-bg" v-bind:style="{backgroundImage:'url('+authorInfo.backgroundImg+')'}">
            <div class="user-bgup"></div>
        </div>
        <div class="user-avatar d-flex py-2">
            <div>
                <img :src="authorInfo.avatarUrl" :alt="authorInfo.name">
            </div>
            
            <div>
                <span class="blog-user-index-name">{{authorInfo.name}}</span><br>
                <span class="blog-user-index-des">{{authorInfo.description}}</span><br>
                <span class="blog-user-index-des">博客访问：{{authorInfo.read}}</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  name: 'blog-user-index-header',
  data() {
    return {
        authorInfo :{
            avatarUrl:'/img/avatar.jpg',
            isHot:true,
            name:'English Name',
            firstLetter:'用',
            description:'简介简介简介简介 English desciption',
            isFollowed:true,
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