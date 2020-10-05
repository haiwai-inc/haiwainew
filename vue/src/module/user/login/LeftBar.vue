<template>
  <div class="login-leftpart">
      <div class="p-3 back"
      @click="$router.push('/blog')">
        <left-arrow style="fill:white;margin-right:24px"></left-arrow><haiwai-logo-white></haiwai-logo-white>
      </div>
      <div class="slogan d-none d-sm-block">
        <span>
          <span class='hilight'>
            <slot></slot>
          </span>
        您的<br>
        海外博客账号<br>
        <small>写出您的故事</small></span>
      </div>
  </div>
</template>
<script>
import {HaiwaiLogoWhite,LeftArrow} from '@/components/Icons';
export default {
  name: 'left-bar',
  props:{
    text:Boolean
  },
  data() {
    return {
      checkboxes: {
        unchecked: false,
        checked: true,
        disabledUnchecked: false,
        disabledChecked: true
      }
    };
  },
  components: {
    HaiwaiLogoWhite,LeftArrow
  }
};
</script>
<style>
.login-leftpart{
  height:100%;
  position: relative;
}
.login-leftpart .back{
  cursor: pointer;
}
.login-leftpart .slogan{
  position: absolute;
  top:45%;
  margin-top:-50px;
  padding-left: 30%;
  color:white;
}
.login-leftpart .slogan span{
  font-size: 24px;
  font-weight: 700;
}
.login-leftpart .slogan .hilight{
  color:#ffaa15;
}
@media (max-width: 575.98px){
    .login-leftpart .slogan{
      position: absolute;
      top:0;
      margin-top:70px;
      padding-left: 30%;
      color:white;
    }
}
</style>
