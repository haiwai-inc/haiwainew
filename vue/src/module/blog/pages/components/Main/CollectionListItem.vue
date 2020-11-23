<template>
    
  <div class="collection-list-item d-flex justify-content-between">
      <div class="name">{{data.name}}({{data.count_article}})</div>
      <div class="descrip"> <icon-right style="fill:#888"></icon-right></div> 
  </div>
       
</template>
<script>
import {IconRight} from '@/components/Icons'
export default {
  name: 'collection-list-item',
  props: {
    type: {
      type: String,
      default: 'default',
      description: 'Alert type'
    },
    data:{
      id:Number,
      title:String,
      articleList:[],
      read:Number,
    }
  } ,
  components:{
    IconRight
  }
};

</script>
<style>

.collection-list-item{
  padding: 12px 18px;
  border-top:1px solid #ddd;
  cursor: pointer;
}
.collection-list-item .name {
  font-size: 1rem;
  font-weight: 600
}
.collection-list-item .descrip{
  font-size:small;
  color: #888888;
  font-weight: 500;
  fill:#888888;
  line-height: 24px;
}

@media (max-width: 575.98px){
    
}
</style>
