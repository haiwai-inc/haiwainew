<template>
  <div class="container-md">
    我的收藏
    <article-list-item 
      v-for="item in articlelists"
      v-bind:key="item.articleID"
      v-bind:data="item"
      type="0">
    </article-list-item> 
  </div>
</template>
<script>
import ArticleListItem from '../blog/pages/components/Main/ArticleListItem.vue'
export default {
  name: 'bookmark',
  components: {
    ArticleListItem
  },
  data(){
    return {
      articlelists: [
        {
          articleID:'345678',
          articleUrl:'',
          title:'这里是标题....',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介...',
          author:'这里是作者',
          authorID:'123456789',
          isHot:true,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'/img/bg3.jpg',
        },{
          articleID:'34567',
          articleUrl:'',
          title:'这里是标题....',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
          author:'这里是作者',
          authorID:'123456',
          isHot:false,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'/img/bg4.jpg',
        },{
          articleID:'3456',
          articleUrl:'',
          title:'这里是标题....',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
          author:'这里是作者',
          authorID:'12345',
          isHot:true,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'/img/bg1.jpg',
        },{
          articleID:'345',
          articleUrl:'',
          title:'这里是标题....',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
          author:'这里是作者',
          authorID:'123456789',
          isHot:false,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'',
        },{
          articleID:'3456789',
          articleUrl:'',
          title:'这里是标题....',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
          author:'这里是作者',
          authorID:'123456789',
          isHot:true,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'',
        }
      ],
    }
  }
};
</script>
<style></style>
