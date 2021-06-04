<template>
  <div class="row">
    <div class="col-sm-4 login-left px-0">
      <left-bar>设置</left-bar>
    </div>
    <div class="col-sm-8 col-12 pt-5">
      <p class="text-center">请重新设置您的密码</p>
      <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" style="width:350px" class="mx-auto my-auto">
        <el-form-item label="新密码" prop="pass">
          <el-input type="password" v-model="ruleForm.pass" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="checkPass">
          <el-input type="password" v-model="ruleForm.checkPass" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="resetForm('ruleForm')">清 空</el-button>
          <el-button type="primary" @click="submitForm('ruleForm')">提 交</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
import LeftBar from './LeftBar';
import account from "../service/account";

export default {
  name: 'login-index',
  bodyClass: 'login-index',
  components: {
    LeftBar,
  },
  mounted(){
    this.token = this.$route.query.token;
    document.documentElement.setAttribute("class", "");
  },
  data() {
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if (this.ruleForm.checkPass !== '') {
          this.$refs.ruleForm.validateField('checkPass');
        }
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.ruleForm.pass) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };
    return {
      token:'',
      ruleForm: {
        pass: '',
        checkPass: '',
      },
      rules: {
        pass: [
          { validator: validatePass, trigger: 'blur' },
          { min: 6, max: 24, message: '长度在 6 到 24 个字符', trigger: 'blur' },
        ],
        checkPass: [
          { validator: validatePass2, trigger: 'blur' }
        ],
      }
    }
  },
  methods:{
    submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            account.user_password_reset(this.ruleForm.pass,this.token).then(res=>{
              if(res.status){
                this.$store.state.user.getUserStatus().then(res=>{
                  this.$store.state.user.userinfo= res.data;
                  this.$router.push('/blog/');
                })
              }else{
                this.$message({
                  showClose:true,
                  message:res.error,
                  type:'error',
                  duration:0
                });
              }
            })
          } else {
            return false;
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
  }

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
  box-shadow: inset 0 0 5em 1em rgb(0 0 0 / 11%)
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
