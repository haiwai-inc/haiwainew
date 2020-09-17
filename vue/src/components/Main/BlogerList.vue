<template>
  <div class="bolger-box">
    <div class="blogger-title d-flex justify-content-between">
      <h4>{{title}}</h4>
      <n-button type="default" link v-bind:style="{paddingRight:0 }"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</n-button>
    </div>
    <bloger-list-item 
    v-for="(item,index) in data" 
    v-bind:key="index" 
    :data="item"></bloger-list-item>
  </div>
</template>
<script>import {
  Button,
  BlogerListItem,
} from '@/components';

export default {
  name: 'bloger-list',
  props: {
    type:String,
    title:String,
    data:{
      articleUrl:String,
      name:String,
      description:String,
      firstLetter:String,
      isFollowed:Boolean,
      isHot:Boolean
    }
  },
  components: {
    [Button.name]: Button,
    BlogerListItem
  }
  
};

</script>
<style>
.bolger-box{
  background-color: aliceblue;
  border-radius: 16px;
  padding:18px 0;
}
.blogger-title{
  padding:0 18px;
}

</style>
