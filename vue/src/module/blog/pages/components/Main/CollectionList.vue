<template>
  <div class="collection-box">
    <span class="blogger-box">
      <bloger-list-item v-if="userdata" :data="userdata" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
    </span>
    <div class="collection-title d-flex justify-content-between">
      <h4>{{title}}</h4>
      <el-popover
        placement="top"
        ref="blog_home_manage"
        v-model="bubbles.blog_home_manage"
        width="300"
        popper-class="bubble"
        trigger="manual"
        >
        <p v-if="user.userinfo.bubble">{{user.userinfo.bubble.instruction.blog_home_manage}}</p>
          <div style="text-align: right; margin: 0">
            1/3
            <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('blog_home_manage')">{{$t('message').userindex.bubble_iknow}}</el-button>
          </div>
        <el-button v-if="user.userinfo.bloggerID==user.userinfo.bloggerID" type="text" link v-bind:style="{paddingRight:0 }" @click="go()" slot="reference">
          <i class="el-icon-notebook-2"></i> {{$t('message').userindex.menu_btn_manage}}
        </el-button>
      </el-popover>
      
    </div>
    <div v-if="data.length===0" class="pl-3">暂无目录</div>
    <div v-if="data.length!==0">
      <collection-list-item 
      v-for="(item,index) in data" 
      v-bind:key="index" 
      :data="item"></collection-list-item>
    </div>
    <!-- <div v-if="data.length>4" class="collection-footer">展开全部 <icon-right class="ratate90"></icon-right></div> -->
  </div>
</template>
<script>
// import {
//     // IconPlus,
//     IconRight
// } from '@/components/Icons';
import {
  Button,
} from '@/components';
import CollectionListItem from './CollectionListItem';
import BlogerListItem from './BlogerListItem';

export default {
  name: 'collection-list',
  props: {
    type:String,
    title:String,
    data:{},
    userdata:{}
  },
  data(){
    return {
      user:this.$store.state.user,
      collapse:true,
      bubbles:{
        blog_home_manage:false,
      }
    }
  },
  components: {
    [Button.name]: Button,
    CollectionListItem,
    BlogerListItem,
    // IconPlus,
    // IconRight
  },
  methods: {
    go(){
      this.$router.push("/blog/my/")
    },
    showBubble(){
      var bubbles=['blog_home_manage','blog_home_profile','blog_home_setting'];
      if(this.user.userinfo.bubble){
        for(let i=0;i<bubbles.length;i++){
          let type = bubbles[i]
          if(this.user.userinfo.bubble.user[type]==1 && this.user.userinfo.bloggerID==this.data[0].bloggerID){
            this.bubbles[type]=true;
            return
          }else{
            this.bubbles[type]=false;
          }
        };
      }
    },
    removeBubble(type){
      this.user.remove_bubble(type).then(res=>{
        if(res.status){
          this.user.userinfo.bubble = res.data;
          this.showBubble();
          this.$emit('showbubble')
        }
      })
    },
  },
  mounted() {
    this.showBubble();
  },
};

</script>
<style>
.collection-box{
  background-color: #fbfbfb;
  border-radius: 16px;
  padding:18px 0;
}
.collection-box .collection-title{
  padding:0 18px;
}
.ratate90{
  -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.collection-box .collection-footer{
  cursor: pointer;
  border-top:1px solid #ddd;
  padding: 10px 18px 0px;
  font-size: 0.85rem;
  text-align: right;
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

</style>
