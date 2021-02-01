<template>
      <div class="mx-auto content">
        <el-form :model="signupForm" :rules="rules" ref="signupForm" label-width="10px">
          <el-form-item
            prop="email"
            label=""
          >
            <el-input v-model="signupForm.email"  placeholder="邮箱"></el-input>
          </el-form-item>
          <el-form-item label="" prop="password">
            <el-input type="password" placeholder="密码" v-model="signupForm.password" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="" prop="checkPassword">
            <el-input type="password" placeholder="确认密码" v-model="signupForm.checkPassword" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item prop="policy">
            <el-checkbox-group v-model="signupForm.policy">
              <el-checkbox label="" name="policy">我已阅读并同意 <a href=#>海外博客-用户协议</a></el-checkbox>
            </el-checkbox-group>
          </el-form-item>
        </el-form>
        <div style="padding-left:10px">
          <el-alert
            :title="signErr.email"
            type="error"
            center
            v-if="signErr.email">
          </el-alert>
          <el-alert
            :title="signErr.password"
            type="error"
            center
            v-if="signErr.password">
          </el-alert>
          <n-button type="primary" round class="w-100" size="lg"  @click="submitForm('signupForm')" :disabled="signupForm.submitDisable">注册</n-button>
          <p class="text-center checkbox my-2">或</p>
          <n-button type="primary" round simple class="w-100 mb-3" @click="isShowLogin('login')">
            去登录
          </n-button>
        </div>
      </div>
</template>
<script>
import {
  Button,
  Checkbox,
  FormGroupInput,
} from '@/components';
import account from "../service/account";

export default {
  name: 'signup-page',
  components: {
    [Button.name]: Button,
    [Checkbox.name]: Checkbox,
    [FormGroupInput.name]: FormGroupInput,
  },
  data() {
    var validateMail =(rule,value,callback)=>{
      if(value===''){
        callback(new Error('请输入邮箱地址'));
      }else{
        account.checkemail(value).then(res=>{
          if(!res.status){
            callback(new Error(res.error))
          }
          callback();
        })
      }
    }
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if (this.signupForm.checkPassword !== '') {
          this.$refs.signupForm.validateField('checkPassword');
        }
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.signupForm.password) {
        callback(new Error('两次输入密码不一致!'));
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
      signupForm:{
        email:'',
        password:'',
        checkPassword:'',
        policy:[],
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
        checkPassword: [
          { required: true, validator: validatePass2, trigger: 'blur' }
        ],
        policy: [
          { type:'array',required: true, message: '需要阅读并同意用户协议', trigger: 'change' }
        ],
      },
      signErr:{email:'',password:''},
    };
  },
  methods:{
    isShowLogin(v){
        this.$emit('onchange',v)
    },
    initForm(){
      this.signupForm={
        email:'',
        password:'',
        checkPassword:'',
        policy:[]
      }
      this.signErr={email:'',password:''}
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.signupForm.submitDisable = true;
          account.signup(this.signupForm).then(res=>{
            console.log(res);
            if(res.status){
              this.initForm();
              this.$router.go(-1);
            }else{
              this.signErr = res.error;
              this.signupForm.submitDisable = true;
            }
          })
        } else {
          console.log('error submit!!');
          this.signupForm.submitDisable = false;
          return false;
        }
      });
    },
  }
};
</script>
<style>
.signup-page .btn{
  margin: 0;
}
.signup-page .noaccount, .signup-page .checkbox{
  font-size: 14px;
  color:gray;
}
.signup-page .form-check{
  margin-bottom: 6px;
}
.signup-page .form-check .form-check-sign::before, .signup-page .form-check .form-check-sign::after{
  width: 18px;
  height:18px;
}
.signup-page .form-check .form-check-label{
  line-height: 18px;
  padding-left: 24px;
}
</style>
