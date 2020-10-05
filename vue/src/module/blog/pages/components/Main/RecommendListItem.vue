<template>
    <div class="recommend-list row no-gutters" id="author" >
      <div class="align-self-center right pr-2" :class="{'col-10':data.image!=='','col-12':data.image===''||type!=='default'}">
        <div class="d-flex align-self-center">
            <div class="name">{{data.articleTitle}}</div>
        </div>
        <div class="d-flex justify-content-between my-2" v-if="type==='default'">
            <div class="descrip">阅读 . {{data.read}}</div>
            <div class="descrip">{{data.date}}</div>
        </div>
      </div>
      <div class="col-2" v-if="data.image!=='' && type=='default'" >
        <img alt="" v-bind:src="data.image"  class="rounded">
      </div>
    </div>
</template>
<script>
export default {
  name: 'recommend-list-item',
  props: {
    type: {
      type: String,
      default: 'default',
      description: 'Alert type'
    },
    data:{
      articleUrl:String,
      articleTitle:String,
      articleID:Number,
      read:Number,
      date:String,
      image:String
    }
  }  
};

</script>
<style>

.recommend-list{
  padding: 12px 18px;
  border-top:1px solid #ddd;
}
.recommend-list .name {
  font-size: 1rem;
  font-weight: 600
}
.recommend-list .descrip{
  font-size:small;
  color: #9A9A9A;
  font-weight: 500
}
.recommend-list img{

}

@media (max-width: 575.98px){
    
}
</style>
