<template>
  <div class="collection-box">
    <div class="collection-title d-flex justify-content-between">
      <h4>{{title}}</h4>
      <n-button type="default" link v-bind:style="{paddingRight:0 }"><icon-plus></icon-plus> <b style="color:#333;">添加文集</b></n-button>
    </div>
    <collection-list-item 
    v-for="(item,index) in data" 
    v-bind:key="index" 
    :data="item"></collection-list-item>
    <div v-if="data.length>4" class="collection-footer">展开全部 <icon-right class="ratate90"></icon-right></div>
  </div>
</template>
<script>
import {
    IconPlus,
    IconRight
} from '@/components/Icons';
import {
  Button,
} from '@/components';
import CollectionListItem from './CollectionListItem'
export default {
  name: 'collection-list',
  props: {
    type:String,
    title:String,
    data:{}
  },
  data(){
    return {
      collapse:true
    }
  },
  components: {
    [Button.name]: Button,
    CollectionListItem,
    IconPlus,
    IconRight
  }
  
};

</script>
<style>
.collection-box{
  background-color: aliceblue;
  border-radius: 16px;
  padding:18px 0;
}
.collection-box .collection-title{
  padding:0 18px;
}
.ratate90{
  -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.collection-box .collection-footer{
  cursor: pointer;
  border-top:1px solid #ddd;
  padding: 10px 18px 0px;
  font-size: 0.85rem;
  text-align: right;
}

</style>
