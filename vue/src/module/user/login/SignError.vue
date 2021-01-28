<template>
      <div class="mx-auto content">
        您的注册确认连接已经过期，请点击下方按钮重新获取确认邮件。
        <div style="padding-left:10px">
          <el-alert
            :title="this.res.data"
            type="success"
            center
            v-if="this.res.status">
          </el-alert>
          <el-alert
            :title="this.res.error"
            type="error"
            center
            v-if="this.res.error">
          </el-alert>
          <n-button type="primary" round class="w-100" size="lg" @click="submit()">重新发送确认信</n-button>
          <!-- <p class="text-center checkbox my-2">或</p>
          <n-button type="primary" round simple class="w-100 mb-3" @click="isShowLogin('login')">
            去登录
          </n-button> -->
        </div>
      </div>
</template>
<script>
import {
  Button,
  // Checkbox,
  // FormGroupInput,
} from '@/components';
import account from "../service/account";

export default {
  name: 'signup-error',
  props:{
    data:{error:String,id:Number}
  },
  components: {
    [Button.name]: Button,
    // [Checkbox.name]: Checkbox,
    // [FormGroupInput.name]: FormGroupInput,
  },
  data() {
    return {
      res:{}
    }
  },
  methods:{
    isShowLogin(v){
        this.$emit('onchange',v)
    },
    
    submit() {
      account.sendverifymail(this.data.id).then(res=>{
        console.log(res);
        if(!res.data.status){
          this.res = res.data
        }
      })
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
