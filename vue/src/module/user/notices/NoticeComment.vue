<template>
    <div>  
        <h6 class="font-weight-normal pb-3">
          我收到的评论
        </h6>
         <div class="row  no-gutters flex-md-row mb-4  h-md-450 position-relative"  v-if="comments.data==0">
             <div class="col-12 pt-4 col-md-8"><img src="/img/com.webp" class="logo"></div>
             <div class="col-12 col-md-3 p-4 d-flex flex-column position-static">
                <div class="row featurette ">
                   <div class="mt-5 col-md-12 m torder-md-2">
                      <h5 class="featurette-heading">当您的文章<br>被评论</h5>
                      <p class="lead text-dark">您就可以在<br>“我收到的评论”里<br>查看详细信息</p>
                   </div>
                </div>
             </div>
          </div>
        <div v-if="comments.status">
        <div v-for="item in comments.data" :key="item.id">
            <h5 
            @click="$router.push('/blog/p/'+item.postInfo_postID.postID)"
            class="notice-comment-t"
            >{{item.postInfo_postID.title}} <span style="font-size:1rem;color:gray">的 {{item.comment.length}} 条新评论</span></h5>
            <ul style="list-style-type: none;" v-if="item.comment">
                <li v-for="c in item.comment" :key="c.id" class="d-flex pt-3">
                    <avatar :data="c.userinfo_userID" :imgHeight="48"></avatar>
                    <div class="pl-3 flex-fill">
                        <div>{{c.userinfo_userID.username}}</div>
                        <div class="py-2">{{c.postInfo_postID.msgbody}}</div>
                        <div class="d-flex" style="font-size:0.85rem;color:gray">
                            <span class="mr-auto">{{c.create_date*1000 | formatDate}}</span>
                            <!-- <span>{{c.count_buzz}}</span>
                            <span class="px-3">{{c.count_comment}}</span> -->
                            <span>
                                <el-popconfirm 
                                    placement="top-end"
                                    confirm-button-text='删除'
                                    cancel-button-text='取消'
                                    title="确定删除这条评论吗？"
                                    :hide-icon="true"
                                    @confirm="reply_delete(c.postID)"
                                >
                                    <a href="javascript:void(0)" slot="reference" style="color:gray;">删除</a>
                                </el-popconfirm>
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <p class="text-center pb-5" style="cursor:pointer" v-if="!noMore" @click="getcomments">加载更多</p>
        <p class="text-center pb-5" v-if="noMore">没有更多了</p>
        </div>
    </div>
</template>
<script>
import Avatar from '../../blog/pages/components/Main/Avatar.vue';
import {formatDate} from '@/directives/formatDate.js';
import blog from '../../blog/blog.service';
// import Comment from '../../blog/pages/article/Comment'
export default {
  name: 'notice-comment',
  components: {
    Avatar
    // Comment
  },
  created() {
      this.getcomments()
  },
  watch:{
    $route(){
      if(this.$route.query.id==1){
        this.lastID=0;
        this.comments.data = [];
        this.getcomments();
      }
    }
  },
  methods:{
      async getcomments(){
          let v = await this.$store.state.user.my_comment_list(0);
          if(v.status){
              this.comments.status = v.status;
              this.comments.data = this.comments.data.concat(v.data);
              if(v.data==20){
                  this.noMore = fasle;
                  this.lastID = v.data[19].id
              }else{
                  this.noMore = true
              }
          }
      },
      reply_delete(id){
      blog.reply_delete(id).then(res=>{
        if(res.status){
            this.getcomments()
        }
      })
    },
  },
  data(){
      return{
          comments:{status:false,data:[]},
          lastID:0,
          noMore:true
      }
  },
  filters: {
    formatDate(time) {
        var date = new Date(time);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  },
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