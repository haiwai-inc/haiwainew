<template>
      <div class="mx-auto content">
        <el-form :model="loginForm" :rules="rules" ref="loginForm" label-width="10px">
          <el-form-item
            prop="email"
            label=""
          >
            <el-input v-model="loginForm.email"  placeholder="邮箱"></el-input>
          </el-form-item>
          <el-form-item 
            label="" 
            prop="password"
          >
            <el-input type="password" placeholder="密码" v-model="loginForm.password" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <!-- <fg-input
          addon-left-icon="now-ui-icons users_single-02"
          placeholder="邮箱"
        >
        </fg-input>
        <fg-input
          addon-left-icon="now-ui-icons objects_key-25"
          placeholder="密码"
        >
        </fg-input> -->
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-link btn-primary">忘记密码</button>
          <span class="noaccount">
            还没有账号？
            <button type="button" class="btn btn-link btn-primary" @click="isShowLogin('signin')">前往注册</button>
          </span>
        </div>
        <div>
          <!-- <n-checkbox v-model="checkboxes.unchecked"><span class="checkbox">在这台电脑上记住我（一个月之内不用再登录）</span></n-checkbox> -->
          <n-button type="primary" round class="w-100" size="lg" @click="submitForm('loginForm')" :disabled="loginForm.submitDisable">登录</n-button>
          <p class="text-center checkbox my-2">或</p>
          <n-button type="default" round simple class="w-100 mb-3">
            <wxc-logo-green></wxc-logo-green>  <span style="color:#468045">文学城</span> 账号登录
          </n-button>
          
          <div id="google-signin-button" class="mb-3"></div>
            
          <n-button type="default" round simple class="w-100 mb-3" v-on:click="facebookLogin()">
            <facebook-logo></facebook-logo>  facebook 账号登录
          </n-button>
          
          <!-- <n-button type="default" round simple class="w-100 mb-3" style="background-color:#468045;border-color:#468045">
              <wxc-logo-white></wxc-logo-white><span style="color:white">文学城 账号登录</span>
          </n-button>  -->
        </div>

      </div>
      
</template>
<script>
import {
  Button,
  Checkbox,
  FormGroupInput,
} from '@/components';
import {
  //WxcLogoWhite,
  WxcLogoGreen,
  FacebookLogo
} from '@/components/Icons';

import account from "../service/account";

export default {
  name: 'login-page',
  components: {
    [Button.name]: Button,
    [Checkbox.name]: Checkbox,
    [FormGroupInput.name]: FormGroupInput,
    //WxcLogoWhite,
    WxcLogoGreen,
    FacebookLogo
  },
  data() {
    var validateMail =(rule,value,callback)=>{
      if(value===''){
        callback(new Error('请输入邮箱地址'));
      }else{
        account.checkemail(value).then(res=>{
          if(res.status){
            callback(new Error('此邮箱并未在本网站注册'))
          }
          callback();
        })
      }
    }
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        callback();
      }
    };
    return {
      checkboxes: {
        unchecked: false,
        checked: true,
        disabledUnchecked: false,
        disabledChecked: true
      },
      loginForm:{
        email:'',
        password:'',
        submitDisable:false
      },
      rules: {
        email:[
          { required: true, validator:validateMail, trigger: 'blur' },
          { type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }
        ],
        password: [
          { required: true, validator: validatePass, trigger: 'blur' },
          { min: 6, max: 24, message: '长度在 6 到 24 个字符', trigger: 'blur' },
        ],
      },
      loginErr:{}
    };
  },
  mounted() {
    gapi.signin2.render('google-signin-button', {
        'scope': 'profile email',
        'width': 350,
        'height': 50,
        'longtitle': true,
        'theme': 'light',
        'onsuccess': this.onGoogleSignIn,
        'onfailure': this.onGoogleFailure,
      });
    AppleID.auth.init({
                clientId : '[CLIENT_ID]',
                scope : '[SCOPES]',
                redirectURI : '[REDIRECT_URI]',
                state : '[STATE]',
                nonce : '[NONCE]',
                usePopup : true //or false defaults to false
            });
  },
  methods:{
      isShowLogin(v){
          this.$emit('onchange',v)
      },
      onGoogleSignIn (user) {
        const profile = user.getBasicProfile();
        var id_token = user.getAuthResponse().id_token;
        account.google_sign_in(id_token).then(res=>{
          if(res.status){
            this.$store.state.user.userinfo = res.data;
            this.$router.push('/');
            console.log(res)
          }else{

          }
          gapi.auth2.getAuthInstance().disconnect();
        });
      },
      onGoogleFailure(error) {
        console.log(error);
      },
      facebookLogin(){
        console.log("haha")
        FB.login(function(response){
            // handle the response 
            console.log(response);
            account.facebook_sign_in(response.authResponse.accessToken);
        }, {scope: 'email'});
      },
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.loginForm.submitDisable = true;
              account.login(this.loginForm).then(res=>{
                console.log(res);
                if(res.status){
                  this.$router.push('/')
                }else{
                  this.loginErrFormat(res.error);
                  this.loginForm.submitDisable = false;
                }
              })
          } else {
            console.log('error submit!!');
            this.loginForm.submitDisable = false;
            return false;
          }
        });
      },
      loginErrFormat(err){
        if(err.indexOf('|')!=-1){
          let arr = err.split('|');
          this.$emit('onloginerr',arr)
          console.log(arr)
        }else{
          this.loginErr.msg = err
        }
      }
  }
};
</script>
<style>
.login-page .btn{
  margin: 0;
}
.login-page .noaccount, .login-page .checkbox{
  font-size: 14px;
  color:gray;
}
.login-page .form-check{
  margin-bottom: 6px;
}
.login-page .form-check .form-check-sign::before, .login-page .form-check .form-check-sign::after{
  width: 18px;
  height:18px;
}
.login-page .form-check .form-check-label{
  line-height: 18px;
  padding-left: 24px;
}
/* google btn */
#google-signin-button .abcRioButton{
  border-radius: 25px;
}
</style>
