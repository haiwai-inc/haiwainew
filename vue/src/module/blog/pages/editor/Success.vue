<template>
   <div>
      <div class="jumbotron text-center">
         <h4 class="font-weight-bold">{{this.$store.state.user.publidhed.postInfo_postID.title}}</h4>
         <p class="lead font-weight">
         <i class="now-ui-icons ui-1_check text-success font-weight-bold mr-2"></i><router-link  :to="'/blog/p/'+this.$store.state.user.publidhed.postID">发布成功，点击查看文章</router-link></p>
         <p class="lead my-4 h6 font-weight-bold">
            <a class="btn bg-white btn-simple btn-primary btn-round mx-2" href="#" role="button">
               <facebook-logo class="mr-2"></facebook-logo>
               Facebook
            </a>
            <a class="btn bg-white btn-simple btn-success btn-round mx-2" href="#" role="button">
               <line-logo class="mr-2"></line-logo>
               Line
            </a>
            <a class="btn bg-white btn-simple btn-success btn-round mx-2" href="#" role="button">
               <wechat-logo class="mr-2"></wechat-logo>
               WeChat
            </a>
            <a class="btn bg-white btn-simple btn-secondary btn-round mx-2" href="#" role="button">
               <copy-logo class="mr-2"></copy-logo>
               复制链接
            </a>
         </p>
      </div>
   </div>
  <!-- <div class="col-sm-4 d-none d-sm-block">
      <div class="hot-bloger" v-if="bloggerList.length>0">
         <bloger-list v-bind:data="bloggerList" title="热门博主"></bloger-list>
      </div>
   </div>-->
</template>
<script>
   //import BlogerList from './components/Main/BlogerList.vue';
   import {
   WechatLogo,
   LineLogo,
   FacebookLogo,
   CopyLogo
   } from '@/components/Icons';
   
   export default {
   name: 'success',
   components: {
   WechatLogo,
   LineLogo,
   FacebookLogo,
   CopyLogo,
   //BlogerList
   },
   data() {
      return {
      //bloggerList : [],
      //res_bloggerList:[],
      user:this.$store.state.user
      };
   },
   methods:{
   },
   };
</script>
<style>

</style>