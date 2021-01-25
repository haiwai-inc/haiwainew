<template>
      <div class="mx-auto content">
        <fg-input
          addon-left-icon="now-ui-icons users_single-02"
          placeholder="邮箱"
        >
        </fg-input>
        <fg-input
          addon-left-icon="now-ui-icons objects_key-25"
          placeholder="密码"
        >
        </fg-input>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-link btn-primary">忘记密码</button>
          <span class="noaccount">
            还没有账号？
            <button type="button" class="btn btn-link btn-primary" @click="isShowLogin(false)">前往注册</button>
          </span>
        </div>
        <div>
          <!-- <n-checkbox v-model="checkboxes.unchecked"><span class="checkbox">在这台电脑上记住我（一个月之内不用再登录）</span></n-checkbox> -->
          <n-button type="primary" round class="w-100" size="lg">登录</n-button>
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

console.log(account)
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
    return {
      checkboxes: {
        unchecked: false,
        checked: true,
        disabledUnchecked: false,
        disabledChecked: true
      }
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
        const profile = user.getBasicProfile()
        var id_token = user.getAuthResponse().id_token;
        account.google_sign_in(id_token).then(res=>{
          console.log(res.data.data)
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
