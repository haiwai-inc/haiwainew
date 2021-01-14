<template>
<div>
  <div class="jumbotron text-center">
    <h4 class="font-weight-bold">《另一种历史》第1期</h4>
    <p class="lead"><i class="now-ui-icons ui-1_check text-success font-weight-bold mr-2"></i>发布成功，点击查看文章</p>
    <p class="lead">
      <a class="btn bg-white btn-simple btn-primary btn-round mx-2" href="#" role="button"> 
       <facebook-logo class="mr-2"></facebook-logo>Facebook 
      </a>
      <a class="btn bg-white btn-simple btn-success btn-round mx-2" href="#" role="button">
       <line-logo class="mr-2"></line-logo>Line
       </a>
      <a class="btn bg-white btn-simple btn-success btn-round mx-2" href="#" role="button">
       <wechat-logo class="mr-2"></wechat-logo>WeChat
       </a>
      <a class="btn bg-white btn-simple btn-secondary btn-round mx-2" href="#" role="button">
        <copy-logo class="mr-2"></copy-logo>复制链接
      </a>
    </p>
  </div>
</div>
</template>
<script>
import {
  WechatLogo,
  LineLogo,
  FacebookLogo,
  CopyLogo
} from '@/components/Icons';


export default {
      name: 'success',
      components: {
        WechatLogo ,
        LineLogo,
        FacebookLogo,
        CopyLogo
        },
      data() {
        return {
         user:this.$store.state.user
         };
      },
      methods:{
      }
};
</script>
<style></style>