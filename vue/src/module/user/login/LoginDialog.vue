<template>
  <div style="width:350px">
    <el-dialog :visible.sync="showLogin" width="395px">
        <login-page 
          v-if="showPage==='login'"
          @onchange="isShowLogin"
          @onloginerr="showNoverify"
          @closedialog="showLogin=false"
          :redirect="redirect"
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
    </el-dialog>
  </div>
</template>
<script>
import LoginPage from './LoginPage';
import SignupPage from './SignupPage';
import SignupError from './SignError';

export default {
  name: 'login-dialog',
  props:{
    // show_dialog:{type:Boolean,default:false}
    // show_dialog:Boolean
    redirect:{type:String,default:""}
  },
  components: {
    LoginPage,
    SignupPage,
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
      },
      showLogin:false
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
    },
    isLogin(){
      // return this.$store.state.user.userinfo?true:false
      if(!this.$store.state.user.userinfo){
        this.showLogin = true;
      }else{
        return true
      }
    },
  }
};
</script>
<style>

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
