<template>
    <div>  
        <h6 class="border-bottom pb-3">
          收到的评论
        </h6>
        <div v-for="(item,index) in comments" :key="index">
            <h5 
            @click="$router.push(item.articleUrl)"
            class="notice-comment-t"
            >{{item.title}}</h5>
            <comment :data="item.comment"></comment>
        </div>
    </div>
</template>
<script>
import Comment from '../../blog/pages/article/Comment'
export default {
  name: 'notice-comment',
  components: {
    Comment
  },
  data(){
      return{
          comments:[{
              title:'这个标题是第一个',
              articleUrl:'/blog/p/1',
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
          },{
              title:'这个标题是第2个',
              articleUrl:'/blog/p/1',
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
                    replies:[]
                }
                ],
          }
          ]
      }
  }
}
</script>
<style>
 .notice-menu{
  margin-bottom: 20px;
  font-size: 16px;
  font-weight: 700;
}
.notice-comment-t{
    cursor: pointer;
    padding: 10px 0 0;
}
</style>