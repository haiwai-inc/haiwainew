<template>
    <comment :data="articleDetail.comment"></comment>
</template>
<script>
import Comment from '../../blog/pages/article/Comment'
export default {
  name: 'article-page',
  components: {
    Comment
  },
  data(){
      return{
          comments:{
              title:'这个标题是第一个',
                comment:[
                {
                    ID:1234,
                    avatarUrl:'/img/julie.jpg',
                    name:'朱莉',
                    time:'15:30 10/12/2020',
                    authorHomepage:'blog/user/1',
                    content:'随便说点什么叽叽歪歪',
                    like:3,
                    showReply:false,
                    replies:[
                    {
                        ID:2234,
                        avatarUrl:'/img/ryan.jpg',
                        name:'Ryan',
                        time:'15:30 10/12/2020',
                        authorHomepage:'blog/user/1',
                        content:'随便回复点什么，巴拉巴拉',
                        like:3,
                    },{
                        ID:2234,
                        avatarUrl:'/img/julie.jpg',
                        name:'Julie',
                        time:'15:30 10/12/2020',
                        authorHomepage:'blog/user/1',
                        content:'<span class="text-info">@Ryan</span> 随便给Ryan回复点什么，巴拉巴拉',
                        like:1,
                    }
                    ]
                },{
                    ID:1234,
                    avatarUrl:'/img/julie.jpg',
                    name:'Julie',
                    time:'30分钟前',
                    authorHomepage:'blog/user/1',
                    content:'随便说点什么叽叽歪歪',
                    like:0,
                    showReply:false,
                    replies:[
                    {
                        ID:2234,
                        avatarUrl:'/img/ryan.jpg',
                        name:'Ryan',
                        time:'15:30 10/12/2020',
                        authorHomepage:'blog/user/1',
                        content:'随便回复点什么，巴拉巴拉',
                        like:0,
                    },{
                        ID:2234,
                        avatarUrl:'/img/julie.jpg',
                        name:'Julie',
                        time:'15:30 10/12/2020',
                        authorHomepage:'blog/user/1',
                        content:'<span class="text-info">@Ryan</span> 随便给Ryan回复点什么，巴拉巴拉',
                        like:0,
                    }
                    ]
                }
                ],
          }
          
      }
  }
}
</script>
<style>

</style>