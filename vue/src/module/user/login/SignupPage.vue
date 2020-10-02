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
        <fg-input
          addon-left-icon="now-ui-icons objects_key-25"
          placeholder="确认密码"
        >
        </fg-input>
        
        <div>
          <n-checkbox v-model="checkboxes.unchecked"><span class="checkbox">我已阅读并同意 <a href=#>海外博客-用户协议</a></span></n-checkbox>
          <n-button type="primary" round class="w-100 mt-5" size="lg">注册</n-button>
          <p class="text-center checkbox my-2">或</p>
          <n-button type="primary" round simple class="w-100 mb-3" @click="isShowLogin(true)">
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
export default {
  name: 'signup-page',
  bodyClass: 'signup-page',
  components: {
    [Button.name]: Button,
    [Checkbox.name]: Checkbox,
    [FormGroupInput.name]: FormGroupInput,
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
  methods:{
      isShowLogin(v){
          this.$emit('onchange',v)
      }
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
