<template>
  <div class="row">
    <div class="col-sm-4 login-left d-none d-sm-block">
      left
    </div>
    <div class="col-sm-8 col-12">
      <div class="mx-auto content" >
        <fg-input
          addon-left-icon="now-ui-icons users_single-02"
          placeholder="用户名或邮箱"
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
            <button type="button" class="btn btn-link btn-primary">前往注册</button>
          </span>
        </div>
        <div>
          <n-checkbox v-model="checkboxes.unchecked"><span class="checkbox">在这台电脑上记住我（一个月之内不用再登录）</span></n-checkbox>
          <n-button type="primary" round class="w-100" size="lg">登录</n-button>
        </div>
      </div>
      
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
  name: 'login-page',
  bodyClass: 'login-page',
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
      },
    };
  }
};
</script>
<style>
html, body, #app, .wrapper, .login-page .row{
  height:100%;
}
.login-page .content{
  width:350px;
  padding: 100px 0 50px 0;
}
.login-page .login-left{
  background-color: #39B8EB;
  height: 100%;
}
.login-page .btn{
  margin: 0;
}
.login-page .noaccount, .login-page .checkbox{
  font-size: 14px;
  color:gray;
}

</style>
