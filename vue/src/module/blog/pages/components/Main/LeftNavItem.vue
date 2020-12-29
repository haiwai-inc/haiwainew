<template>
  <div class="left-nav-item d-flex justify-content-between" @click="wActive(data.id)">
      <div class="name" :style="data.id===activeId?{color:'#39b8eb'}:''">
        <icon-message class="icon" v-if="data.id===0" :style="data.id===activeId?{stroke:'#39b8eb'}:{stroke:'#333'}"></icon-message>
        <icon-mail class="icon" v-if="data.id===1" :style="data.id===activeId?{fill:'#39b8eb'}:{fill:'#333'}"></icon-mail>
        <icon-follow class="icon" v-if="data.id===2" :style="data.id===activeId?{fill:'#39b8eb'}:{fill:'#333'}"></icon-follow>
        <icon-like-outline class="icon" v-if="data.id===3" :style="data.id===activeId?{stroke:'#39b8eb'}:{stroke:'#333'}"></icon-like-outline>
        {{data.title}}</div>
      <div class="descrip" :style="data.id===activeId?{color:'#39b8eb'}:''">{{data.unread}}</div> 
  </div>
</template>
<script>
import {IconMessage,IconMail,IconFollow,IconLikeOutline} from '@/components/Icons';
export default {
  name: 'left-nav-item',
  props: {
    activeId: {
      type: Number,
    },
    data:{
      id:Number,
      title:String,
      articleList:[],
      unread:Number,
    }
  } ,
  components:{
    IconMessage,IconMail,IconFollow,IconLikeOutline,IconLikeOutline
  },
  methods:{
    wActive(id){
      this.$emit('which-active',id);
    }
  }
};

</script>
<style>

.left-nav-item{
  padding: 12px 18px;
  border-bottom:1px solid #f0f0f0;
  cursor: pointer;
}
.left-nav-item .name {
  font-size: 1.2rem;
  font-weight: 600
}
.left-nav-item .descrip{
  font-size:small;
  color: #888888;
  font-weight: 500;
  fill:#888888;
  line-height: 24px;
}
.left-nav-item.active, .left-nav-item:hover {
        background-color: aliceblue;
}
.left-nav-item .icon{
  margin-right:10px;
  font-size:10px
}

@media (max-width: 575.98px){
    
}
</style>
