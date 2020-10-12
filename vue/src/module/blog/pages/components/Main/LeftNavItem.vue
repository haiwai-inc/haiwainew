<template>
  <div class="left-nav-item d-flex justify-content-between">
      <div class="name">
        <icon-message v-if="data.id===0" style="stroke:#333"></icon-message>
        <icon-mail v-if="data.id===1" style="fill:#333"></icon-mail>
        <icon-like-outline v-if="data.id===3" style="stroke:#333"></icon-like-outline>
        {{data.title}}</div>
      <div class="descrip">{{data.unread}} </div> 
  </div>
</template>
<script>
import {IconMessage,IconMail,IconLikeOutline} from '@/components/Icons';
export default {
  name: 'left-nav-item',
  props: {
    active: {
      type: Boolean,
      default: false,
    },
    data:{
      id:Number,
      title:String,
      articleList:[],
      unread:Number,
    }
  } ,
  components:{
    IconMessage,IconMail,IconLikeOutline
  }
};

</script>
<style>

.left-nav-item{
  padding: 12px 18px;
  border-bottom:1px solid #ddd;
  cursor: pointer;
}
.left-nav-item .name {
  font-size: 1rem;
  font-weight: 600
}
.left-nav-item .descrip{
  font-size:small;
  color: #888888;
  font-weight: 500;
  fill:#888888;
  line-height: 24px;
}

@media (max-width: 575.98px){
    
}
</style>
