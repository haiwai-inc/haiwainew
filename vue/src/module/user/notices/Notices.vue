<template>
<div class="container">
      <div>
        <main-menu type="-1"></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-4 d-none d-sm-block">
            <left-nav-item v-for="(item,index) in data" :key='index' :data='item'></left-nav-item>
          
        </div>
        <div class="col-sm-8 col-12">
            right
        </div>
      </div>
    </div>
  
</template>
<script>
import MainMenu from '../../blog/pages/components/Main/MainMenu.vue';
import LeftNavItem from '../../blog/pages/components/Main/LeftNavItem'
export default {
  name: 'notices',
  data(){
    return{
      activeId:0,
      data:[
        {
          id:0,
          title:'评论',
          noticeList:[],
          unread:2,
        },{
          id:1,
          title:'悄悄话',
          noticeList:[],
          unread:0,
        },{
          id:2,
          title:'关注',
          noticeList:[],
          unread:3,
        },{
          id:3,
          title:'喜欢',
          noticeList:[],
          unread:6,
        },
      ]
    }
  },
  components: {
    LeftNavItem,
    MainMenu
  }
};
</script>
<style></style>
