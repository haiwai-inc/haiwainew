<template>
  <!-- <navbar
    type="white"
    menu-classes="ml-auto"
  > -->
  <nav class="navbar sticky-top bg-white navbar-expand-lg navbar-relative">
    <template>
      <router-link class="navbar-brand" to="/">
        <img src="/img/logo.png" alt="海外博客" height="32">
      </router-link>
    </template>
    <span>{{title}}</span>
  </nav>
  
</template>

<script>


export default {
  name: 'mini-navbar',
  props: {
    transparent: Boolean,
    colorOnScroll: Number,
    title:String
  },
  components: {
  
  },
  
  data(){
    return {
      
    };
  },
  methods:{
    
  },
  created: function () {
    
  },
  beforeCreate(){
  },
  mounted() {
    
  }
};
</script>

<style>
.el-input__inner{
  border-radius: 20px;
}
.editicon{
  fill:#32caf9;
  margin-right:4px;
  border-bottom: 1px solid #32caf9;
  height:20px;
}
.switchbtn{
  margin:12px 35px;
}
.noticealert{
  position: absolute;
  margin-left: 10px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #FF3636;
}
.editbtn{
   white-space: nowrap;
}
@media (max-width: 575.98px) { 
  .editbtn{
    color:white !important;
    border-color: white !important;
    margin: 1rem 2rem;
  }
  .editbtn .editicon{
    fill:white;
    border-bottom: 1px solid white;
  }
  .switchbtn{
    margin: 12px 30px;
  }
}

</style>
