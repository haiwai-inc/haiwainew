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
        <div style="color:#f56c6c;text-align:center" v-if="loginErr.status">{{loginErr.msg}}</div>
          
          <!-- <n-checkbox v-model="checkboxes.unchecked"><span class="checkbox">在这台电脑上记住我（一个月之内不用再登录）</span></n-checkbox> :disabled="loginForm.submitDisable"-->
          <n-button type="primary" round class="w-100" size="lg" @click="submitForm('loginForm')" >登录</n-button>
          <p class="text-center checkbox my-2">或</p>
          <n-button type="default" round simple class="w-100 mb-3"  @click="dialogFormVisible = true">
            <wxc-logo-green></wxc-logo-green>  <span style="color:#468045">文学城</span> 账号登录
          </n-button>
          
          <div id="google-signin-button" class="mb-3"></div>
            
          <n-button type="default" round simple class="w-100 mb-3" v-on:click="facebookLogin()">
            <facebook-logo></facebook-logo>  facebook 账号登录
          </n-button>

          <n-button type="default" round simple class="w-100 mb-3" v-on:click="lineLogin()">
              Line 账号登录
          </n-button>
          <n-button type="default" round simple class="w-100 mb-3" v-on:click="appleLogin()">
              Apple 账号登录
          </n-button>
          <!-- <div id="appleid-signin"></div> -->
          
          <!-- <n-button type="default" round simple class="w-100 mb-3" style="background-color:#468045;border-color:#468045">
              <wxc-logo-white></wxc-logo-white><span style="color:white">文学城 账号登录</span>
          </n-button>  -->
        </div>
<el-dialog title="文学城用户登录" width="350px" :visible.sync="dialogFormVisible">
  <el-form :model="wxcForm" :rules="wxcrules" ref="wxcForm" label-width="10px">
    <el-form-item
      prop="username"
      label=""
    >
      <el-input v-model="wxcForm.username"  placeholder="文学城用户名"></el-input>
    </el-form-item>
    <el-form-item 
      label="" 
      prop="password"
    >
      <el-input type="password" placeholder="文学城密码" v-model="wxcForm.password" autocomplete="off"></el-input>
    </el-form-item>
  </el-form>
  <div slot="footer" class="dialog-footer">
    <n-button link @click="dialogFormVisible = false">取 消</n-button>
    <n-button type="primary" round  @click="submitForm('wxcForm')">用文学城账号登录</n-button>
  </div>
</el-dialog>
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
import {Dialog} from "element-ui"
import account from "../service/account";

export default {
  name: 'login-page',
  components: {
    [Button.name]: Button,
    [Checkbox.name]: Checkbox,
    [FormGroupInput.name]: FormGroupInput,
    [Dialog.name]:Dialog,
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
    }
    var validateWxcPass = (rule, value, callback) => {
      var patrn=/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if(!patrn.exec(value)){
          callback(new Error('至少包含一个数字和一个字母'))
        }
        callback();
      }
    }
    var validateName =(rule,value,callback)=>{
      if(value===''){
        callback(new Error('请输入用户名'));
      }else{
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
      dialogFormVisible: false,
      wxcForm:{
        username:'',
        password:'',
        submitDisable:false
      },
      wxcrules: {
        username:[
          { required: true, validator: validateName, trigger: 'blur' },
        ],
        password: [
          { required: true, validator: validateWxcPass, trigger: 'blur' },
          { min: 6, message: '至少 6 个字符', trigger: 'blur' },
        ],
      },
      loginErr:{status:false,msg:''}
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
                clientId : 'serviceid.haiwai.blog',
                scope : 'email',
                redirectURI : 'http://local.haiwai.com:8080/login',
                state:"abc",
                usePopup : true,
                // response_mode: "query" //or false defaults to false
            });
    
    this.lineSignin();
  },
  methods:{
      isShowLogin(v){
          this.$emit('onchange',v)
      },
      setLoginState(res){
        this.$store.state.user.userinfo = res.data;
        localStorage.setItem('loginState', res.state);
      },
      onGoogleSignIn (user) {
        const profile = user.getBasicProfile();
        var id_token = user.getAuthResponse().id_token;
        account.google_sign_in(id_token).then(res=>{
          if(res.status){
            this.setLoginState(res);
            this.$router.push('/');
            console.log(res)
          }else{
          }
          this.loginErrFormat(res);
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
      lineLogin(){
        window.location.href = "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1655609154&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2Flogin&state=1&scope=profile%20openid%20email"
      },
      lineSignin(){
        let code = this.$route.query.code;
        let state = this.$route.query.state;
        if(code !== undefined){
          account.line_sign_in(code).then(res=>{
          //   if(!res.error){
          //   this.setLoginState(res);
          //   this.$router.push('/');
          //   console.log(res.data.data)
          // }
          });
        }
      },
      appleLogin(){
        AppleID.auth.signIn().then(result=>{
          console.log(result);
          if(result.error !== undefined){

          }
          else {
            account.apple_sign_in(result.authorization.id_token).then(data=>{
                
            })
          }
        })
      },
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            if(formName==='loginForm'){
              this.loginForm.submitDisable = true;
              account.login(this.loginForm).then(res=>{
                console.log(res);
                if(res.status){
                  this.setLoginState(res);
                  this.$router.push('/')
                }else{
                  this.loginForm.submitDisable = false;
                }
                this.loginErrFormat(res);
              })
            }
            if(formName==='wxcForm'){
              this.wxcForm.submitDisable = true;
              account.wxc_sign_in(this.wxcForm).then(res=>{
                console.log(res);
                if(res.status){
                  this.setLoginState(res);
                  this.$router.push('/')
                }else{
                  this.wxcForm.submitDisable = false;
                }
                this.loginErrFormat(res);
              })
            }
          } else {
            console.log('error submit!!');
            this.loginForm.submitDisable = false;
            this.wxcForm.submitDisable = false;
            return false;
          }
        });
      },
      loginErrFormat(err){
        if(err.status){
          this.loginErr.msg = ''
          this.loginErr.status=false;
        }else{
          if(err.error.indexOf('|')!=-1){
            let arr = err.error.split('|');
            this.$emit('onloginerr',arr)
            console.log(arr)
          }else{
            this.loginErr.msg = err.error
            this.loginErr.status=true;
            console.log(err)
          }
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
