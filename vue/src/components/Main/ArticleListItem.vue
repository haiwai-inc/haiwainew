<template>
  <div class="list-itme d-flex">
    <div>
        <h4 class='article-title'>{{data.title}}</h4>
        <p class="descript">{{data.description}}</p>
        <div class='list-itme-tail d-flex justify-content-between mb-2'>
          <div class="name">
            <i class="now-ui-icons objects_diamond text-primary" v-if="data.isHot"></i> {{data.author}}
          </div>
          <div class="tail-data">
            <span><i class="now-ui-icons ui-2_favourite-28"></i> {{data.likes}}</span>
            <span class="ml-3"><i class="now-ui-icons ui-2_chat-round"></i> {{data.commont}}</span>
          </div>
            
        </div>
    </div>
    <div 
      id="image" 
      class='list-itme-image' 
      v-if="data.image"
      v-bind:style="{backgroundImage:'url('+data.image+')'}">
        <div class="imgspace" id="imgspace"></div>
    </div>
</div>
</template>
<script>

export default {
  name: 'article-list-item',
  props: {
    type:String,
    data:{
      articleID:String,
      articleUrl:String,
      title:String,
      description:String,
      author:String,
      authorID:String,
      isHot:Boolean,
      read:String,
      comments:String,
      likes:String,
      image:[String]
    }
  },
  
};
// console.log(type);
</script>
<style>
h4{
  margin-top:0 !important;
}
.list-itme{
  border-bottom: 1px solid #eee;
}
.list-itme .list-itme-image{
    background-repeat:no-repeat;
    background-size:cover;
    background-position:center;
    border-radius: 4px; 
    margin:auto .85rem;
}
.imgspace{
    width:160px;
    height:105px;
}
h4.article-title{
  margin-bottom: .5rem;
  padding-top:1rem;
}
p.descript,div.list-itme-tail{
  color:#999;
  line-height: 1.8 ;
  font-size: .94rem ;
}
.list-itme-tail .name{
  color:#333;
}
.list-itme-tail .tail-data{
  font-size: 0.75rem;
  padding-right:1rem;
}
p.descript{
  margin-bottom: 0.25rem;
}
@media (max-width: 575.98px){
    .list-itme .list-itme-image{
        margin:auto 0;
        margin-left: .5rem;
    }
    .imgspace{
        width:90px;
        height:90px;
    }
}
</style>
