<template>
  <div class="row">
    <div class="col-sm-4 login-left">
      <left-bar>{{showPage==='login'?'登录':'注册'}}</left-bar>
    </div>
    <div class="col-sm-8 col-12">
    <!-- login tab -->
      <div class="px-3">
        <ul class="tab-group list-unstyled">
              <li class="tab active">
                <a href="#">注册</a>
              </li>
              <li class="tab">
                <a href="#">登录</a>
              </li>
        </ul>
      </div>
    <!-- login tab -->
        <login-page 
          v-if="showPage==='login'"
          @onchange="isShowLogin"
          @onloginerr="showNoverify"
          :redirect="'/'"
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
    SignupError,
  },
  mounted(){
    let err = this.$route.query.error;
    let id = this.$route.query.id;
    if(err){
      this.showPage=this.$route.query.error;
      if(this.showPage!='verified'){this.showPage='login'}
      this.signErr.error = err;
      this.signErr.id = id;
    }else{
      this.showPage="login"
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
    },
    showNoverify(e){
      this.signErr.error=e[0];
      this.signErr.id=e[1];
      this.showPage='verified';
      console.log(this.signErr)
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
/* login tab */
.login-index .tab-group .active a {
        color: #39b8eb;
        border-bottom: .2rem solid #39b8eb;
}
.login-index .tab-group li a {
        padding: 15px;
        color: #a0b3b0;
        font-size: 20px;
        float: left;
        width: 50%;
        text-align: center;
        margin: 0 0 40px 0;
}
.login-index .tab-group  a:hover{
        text-decoration:none;
        color: #39b8eb
}
</style>
