<template>
  <div class="bolger-box">
    <div class="blogger-title d-flex justify-content-between">
      <h4>{{title}}</h4>
      <n-button type="default" link v-bind:style="{paddingRight:0 }" @click="refresh()"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</n-button>
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
    refresh(){
      this.hotBloggerList.currentList += 10;
      this.hotBloggerList.count += 10;
      if(this.hotBloggerList.count>this.data.length){
        this.hotBloggerList.currentList = 0;
        this.hotBloggerList.count = 10;
      }
      this.hotBloggerList.data = this.data.slice(this.hotBloggerList.currentList,this.hotBloggerList.count);

      console.log(this.hotBloggerList.data,this.data,this.hotBloggerList.currentList)
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
    this.hotBloggerList.data = this.data.slice(this.hotBloggerList.currentList,this.hotBloggerList.count)
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
