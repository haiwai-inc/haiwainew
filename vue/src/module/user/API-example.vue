<template>
  <div>
    <div class="container">
      <div>
        <!-- <main-menu type="2"></main-menu> -->
      </div>
      <div class="row">
        <div class="col-sm-8 col-12">
          
        </div>
        <div class="col-sm-4 d-none d-sm-block">
          <!-- {{user.state.check}} -->
          <img :src="user.avatar" alt="">
          {{user.username}}
          <button v-on:click="login()"> Login</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Account from './service/account'
let accountModule ={
  state: {
    account: new Account()
  },
  mutations: {
    increment (state) {
    }
  }
}
export default {
  name: 'api_example',
  bodyClass: 'index-page',
  //Create new module for store that is used only under the current module
  beforeCreate(){
      this.$store.registerModule('account', accountModule)
  },
  data() {
    return {
        user:this.$store.state.user
    };
  },
  methods:{
      async login() {
          console.log(this.$store.state.account);
          let user = this.$store.state.user;
          let res = await user.getUserInfo(1);
          let account = this.$store.state.account.account;
          account.signal(res.data.avatar);
      }
  }      
};
</script>
<style></style>
