<template>
    <div>
        <h6
        class="pb-2"
        >{{follows.title}}</h6>
        <bloger-list-item v-for="(item,index) in follows.authorList" :key="index" :data="item"></bloger-list-item>
    </div>
</template>
<script>
import BlogerListItem from '../../blog/pages/components/Main/BlogerListItem'
export default {
  name: 'notice-follow',
  components: {
    BlogerListItem
  },
  data(){
      return{
          follows:{
              title:'全部关注',
              authorList : [
            {
            avatarUrl:'/img/avatar.jpg',
            isHot:true,
            name:'English Name',
            firstLetter:'用',
            description:'简介简介简介简介 English desciption',
            isFollowed:true,
            },{
            avatarUrl:'/img/eva.jpg',
            isHot:true,
            name:'天煞地煞',
            firstLetter:'天',
            description:'简介简介简介简介哈哈哈简介简介简介简',
            isFollowed:true,
            },{
            avatarUrl:'/img/julie.jpg',
            isHot:true,
            name:'用户名',
            firstLetter:'用',
            description:'简介简介简介简介',
            isFollowed:true,
            },
          ]
      }}
  }
}
</script>
<style>

</style>