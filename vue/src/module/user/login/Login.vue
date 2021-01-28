<template>
  <div class="row">
    <div class="col-sm-4 login-left">
      <left-bar>{{showPage==='login'?'登录':'注册'}}</left-bar>
    </div>
    <div class="col-sm-8 col-12">
        <login-page 
          v-if="showPage==='login'"
          @onchange="isShowLogin"
        ></login-page>
        <signup-page 
          v-if="showPage==='signin'"
          @onchange="isShowLogin"
        ></signup-page>
        <signup-error
          v-if="showPage==='verified'"
          :data="signErr"
          @onchange="isShowLogin"
        ></signup-error>
    </div>
  </div>
</template>
<script>
import LoginPage from './LoginPage';
import SignupPage from './SignupPage';
import LeftBar from './LeftBar';
import SignupError from './SignError';

export default {
  name: 'login-index',
  bodyClass: 'login-index',
  components: {
    LoginPage,
    SignupPage,
    LeftBar,
    SignupError
  },
  mounted(){
    
    let err = this.$route.query.error;
    let id = this.$route.query.id;
    if(err){
      this.showPage=this.$route.query.error;
      this.signErr.error = err;
      this.signErr.id = id;
    }
  },
  data() {
    return {
      showPage:'login',
      signErr:{
        error:'',
        id:''
      }
    };
  },
  methods:{
    isShowLogin(v){
      this.showPage = v
    }
  }
};
</script>
<style>
html, body, #app, .wrapper, .login-index .row{
  height:100%;
}
.el-input input{
  border-radius: 20px;
}
.login-index .content{
  width:350px;
  padding: 100px 0 50px 0;
}
.login-index .login-left{
  background-color: #39B8EB;
  height: 100%;
}
@media (max-width: 575.98px){
    .login-index .login-left{
      height:60px;
    }
    .login-index .content{
      width:350px;
      padding: 0;
    }
}
</style>
