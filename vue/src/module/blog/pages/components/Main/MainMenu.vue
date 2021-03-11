<template>
  <div class="main-menubox d-flex mt-2">
    <n-button v-for="(item,index) in menuitems"
        :key="index"
        type="default" 
        link 
        @click="$router.push(item.link)" 
        v-bind:class="{active:type==item.id}"
    >
        <span>{{item.name}}</span>
    </n-button>
  </div>
</template>
<script>
import {
  Button,
} from '@/components';
export default {
  name: 'main-menu',
  props: {
    type:String,
  },
  components: {
    [Button.name]: Button,
    },
  data() {
    return {
      menuitems: [
          {id:'0',name:'关注',link:'/blog/follows'},
          {id:'1',name:'推荐',link:'/blog'},
          {id:'2',name:'热榜',link:'/blog/hot'}
      ]
    }
  }
};
</script>
<style>
.main-menubox span{
  font-size: 1.125rem !important;
  font-weight: 500;
  margin-right: 1rem;
  color: gray;
}
.main-menubox button{
  margin: 0;
  margin-top:-5px;
  margin-bottom: 10px;
}
.main-menubox .active span{
    color:black;
    font-weight:600
}
.main-menubox .active{
    cursor:default !important;
}
</style>
