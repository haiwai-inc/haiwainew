<template>
  <div class="list-itme d-flex mb-3">
    <div class="flex-fill">
      <router-link :to="'/blog/p/'+data.postID">
        <h4 class='article-title'>{{data.postInfo_postID.title | textTrans}}</h4>
        <p class="descript" v-html="$options.filters.textTrans(data.postInfo_postID.msgbody)"></p>
      </router-link>
        <div class='list-itme-tail d-flex justify-content-between mb-2'>
          <div class="name">
            <icon-V class="text-primary lable" v-if="data.userinfo_userID.is_hot_blogger"></icon-V> 
            
            {{data.userinfo_userID.username}}
            
          </div>
          <div class="tail-data">
            <span>
              <!-- <i class="now-ui-icons ui-2_favourite-28"></i> -->
              <span class="icons" v-html="icons.like_outline"></span>
               {{data.countinfo_postID.count_buzz}}</span>
            <span class="ml-3">
              <span class="icons" v-html="icons.message"></span>
               {{data.countinfo_postID.count_comment}}</span>
            <span class="ml-3" v-if="type=='bookmark'">
              <a herf="javascript:void(0)" v-if="data.postInfo_postID.is_bookmark==1" @click="deletBookmark(data.postID)">取消收藏</a>
            </span>

            <!-- <a class="ml-3" v-if="data.userID==$store.state.user.userinfo.UserID" href="javascript:void(0)" style="color:#39b8eb" @click="gotoEditor(data)">编辑</a> -->
            <!-- <el-popconfirm  v-if="data.userID==$store.state.user.userinfo.UserID"
              placement="top-end"
              confirm-button-text='删除'
              cancel-button-text='取消'
              title="确定删除这篇文章吗？"
              :hide-icon="true"
              @confirm="article_delete(data)"
            >
              <a href="javascript:void(0)" slot="reference" class="ml-3" style="color:#39b8eb">删除</a>
            </el-popconfirm> -->
          </div>
        </div>
    </div>
    <div 
      id="image" 
      class='list-itme-image' 
      v-if="data.postInfo_postID.pic"
      v-bind:style="{backgroundImage:'url('+data.postInfo_postID.pic+')'}">
        <router-link :to="'/blog/p/'+data.postID">
          <div class="imgspace" id="imgspace"></div>
        </router-link>
    </div>
</div>
</template>
<script>
import {
    IconV
} from '@/components/Icons';
import icons from '@/components/Icons/Icons.js'
import blog from '../../../blog.service';
import {textTrans} from '@/directives/textTrans.js';

export default {
  name: 'article-list-item',
  props: {
    type:String,
    data:{
      countinfo_postID:{},
      create_date:Number,
      id:Number,
      postID:Number,
      postInfo_postId:{},
      title:String,
      userID:Number,
      userinfo_userID:{}
    }
  },
  components: {
    IconV
  },
  data(){
    return{
      icons:icons,
    }
  },
  methods:{
    deletBookmark(postID){
      this.$emit('delete-bookmark',postID);
    },
    // article_delete(item){
    //   blog.article_delete(item.postID,0).then(res=>{
    //     if(res.status){
    //       this.$forceUpdate()
    //     }
    //   })
    // },
    // gotoEditor(item){
    //   let url = '/blog/my/editor/?postid='+item.postID
    //   this.$router.push(url);
    // }
  },
  filters: {
    textTrans(string) {
      return textTrans(string);
    }
  }
   
};
// console.log(type);
</script>
<style>
h4{
  margin-top:0 !important;
}
.list-itme{
  border-bottom: 1px solid #eee;
}
.list-itme .list-itme-image{
    background-repeat:no-repeat;
    background-size:cover;
    background-position:center;
    border-radius: 4px; 
    margin:auto .85rem;
}
.list-itme a{
  color:#14171a;
  text-decoration: none;
}

.imgspace{
    width:160px;
    height:105px;
}
h4.article-title{
  margin-bottom: .5rem;
  margin-top:1rem;
  font-weight: 600;
  font-size: 1.2em
}
h4.article-title:hover{
color: #235592 
}
.list-itme .descript{
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  word-break: break-all;
}
.list-itme .descript:hover{
color: #646464
}
p.descript,div.list-itme-tail{
        color: #121212;
        line-height: 1.6;
        font-size: 1rem;
        font-weight: normal;
}
.list-itme-tail .name{
  color:#333;
}
.list-itme-tail .tail-data{
        font-size: 0.95rem;
        padding-right: 1rem;
        padding-top: 8px;
        color: #5b7083;
}
.list-itme-tail .tail-data a{
  cursor: pointer;
}
.tail-data .icons svg{
  stroke:gray;
  height:16px;
  width:16px;
}
p.descript{
  margin-bottom: 0.25rem;
}
@media (max-width: 575.98px){
    .list-itme .list-itme-image{
        margin:auto 0;
        margin-left: .5rem;
    }
    .imgspace{
        width:90px;
        height:90px;
    }
}
</style>
