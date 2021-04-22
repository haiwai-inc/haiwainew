<template>
  <div class="bolger-box">
    <div class="blogger-title d-flex justify-content-between">
      <h5 class="font-weight-bold">{{title}}</h5>
      <n-button type="default" link v-bind:style="{paddingRight:0 }" @click="refresh(1)"><i class="now-ui-icons arrows-1_refresh-69"></i> {{$t('message').blog.bloggerlist_refresh}}</n-button>
    </div>
    <bloger-list-item 
    v-for="(item,index) in hotBloggerList.data" 
    v-bind:key="index" 
    :data="item"></bloger-list-item>
  </div>
</template>
<script>import {
  Button,
} from '@/components';
import BlogerListItem from './BlogerListItem'
export default {
  name: 'bloger-list',
  props: {
    type:String,
    title:String,
    data:Array
  },
  components: {
    [Button.name]: Button,
    BlogerListItem
  },
  methods:{
    refresh(n){
      this.hotBloggerList.currentList += n*10;
      this.hotBloggerList.count += n*10;
      if(this.hotBloggerList.count>this.data.length){
        this.hotBloggerList.currentList = 0;
        this.hotBloggerList.count = n*10;
      }
      this.hotBloggerList.data = this.data.slice(this.hotBloggerList.currentList,this.hotBloggerList.count);
      // console.log(this.hotBloggerList.data,this.data,this.hotBloggerList.currentList)
    }
  },
  data(){
    return{
      hotBloggerList:{
        currentList:0,
        count:10,
        data:Array
      }
    }
  },
  created () {
    this.hotBloggerList.data = this.data.slice(this.hotBloggerList.currentList,this.hotBloggerList.count);
    this.refresh(Math.ceil(Math.random()*10)+1);
  }
  
};

</script>
<style>
.bolger-box{
  background-color: #f6f9fc;
  border-radius: 16px;
  padding:18px 0;
}
.blogger-title{
  padding:0 18px;
}

</style>
