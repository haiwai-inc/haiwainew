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
          {{user.username}}
          <button v-on:click="login()"> Login</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  name: 'api_example',
  bodyClass: 'index-page',
//   components: {
//     MainMenu,
//   },
  data() {
    return {
        user:this.$store.state.user
    };
  },
  methods:{
      login() {
          this.$api.get("Haha");
      }
  }      
};
</script>
<style></style>
