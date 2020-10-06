<template>
  <div class="collection-box">
    <div 
      class="user-index-sort d-flex align-self-center" 
      v-for="(item, index) in data" 
      :key="index">
      <icon-news class="icon" v-if="item.icon==='0'"></icon-news>
      <icon-fire class="icon" v-if="item.icon==='1'"></icon-fire>
      <icon-message class="icon" v-if="item.icon==='2'"></icon-message>
      <div class="flex-grow-1 pt-1 pl-2">
        {{item.text}} </div><icon-right></icon-right>
    </div>
  </div>
</template>
<script>
import {
    IconRight,
    IconNews,
    IconMessage,
    IconFire
} from '@/components/Icons';

export default {
  name: 'user-index-sort',
  props: {
    data:{}
  },
  components: {
    IconRight,
    IconNews,
    IconMessage,
    IconFire
  }
  
};

</script>
<style>
.collection-box{
  background-color: aliceblue;
  border-radius: 16px;
  padding:18px 0;
}
.collection-box .cuser-index-sort{
  padding:0 18px;
}
.ratate90{
  -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.collection-box .user-index-sort{
  cursor: pointer;
  border-top:1px solid #ddd;
  padding: 13px 18px;
  font-size: 1rem;
  color:#14171a;
  fill:#14171a
}
.collection-box .user-index-sort:hover{
  color:#39b8eb;
  fill:#39b8eb
}
.collection-box .user-index-sort:first-child{
  border:0
}
</style>
