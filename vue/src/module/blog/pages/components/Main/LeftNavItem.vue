<template>
  <div class="left-nav-item d-flex justify-content-between" @click="wActive(data.id)" :style="data.id===activeId?{fill: '#39b8eb', background: 'aliceblue'}:''">
      <div class="name" :style="data.id===activeId?{color:'#39b8eb', fill: '#39b8eb'}:''">
       <span v-html="data.icon" class="icon"></span>
        {{data.title}}</div>
      <div class="descrip" :style="data.id===activeId?{color:'#39b8eb',}:''">{{data.unread?data.unread:''}}</div> 
  </div>
</template>
<script>
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
  components:{},
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
  color:#5b7083
}
.left-nav-item .name {
  font-size: 1.2rem;
  font-weight: 600
}
.left-nav-item .name .icon svg{
  fill:#1d1d1d;
  stroke:#1d1d1d;
  stroke-width:0.3px
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

}

@media (max-width: 575.98px){
    
}
</style>
