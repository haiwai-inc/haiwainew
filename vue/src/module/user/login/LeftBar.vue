<template>
  <div class="login-leftpart textheader">
    <div class="inner-textheader">
      <div class="p-3 back float-left ml-3"
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
      <div>
      <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
      <defs>
      <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
      </defs>
      <g class="parallax">
      <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
      <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
      <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
      <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff"></use>
      </g>
      </svg>
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
  padding-left: 33%;
  color:white;
}
.login-leftpart .slogan span{
  font-size: 24px
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
.textheader {
        position: relative;
        text-align: center;
        background: linear-gradient(60deg, #0a74c9 0%, rgb(0 172 193 / 73%) 100%)
}
.inner-textheader {
  height:91vh;
  width:100%;
  margin: 0;
  padding: 0;
}
.waves {
  position:relative;
  width: 100%;
  height:5vh;
  min-height:100px;
  max-height:150px;
}
.parallax > use {
  animation: move-forever 25s cubic-bezier(.55,.5,.45,.5)     infinite;
}
.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}
.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}
.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}
.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}
@keyframes move-forever {
  0% {
   transform: translate3d(-90px,0,0);
  }
  100% { 
    transform: translate3d(85px,0,0);
  }
}
</style>
