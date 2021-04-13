<template>
  <div class="main-menubox d-flex mt-2">
    <!-- <n-button v-for="(item,index) in menuitems"
        :key="index"
        type="default" 
        link 
        @click="$router.push(item.link)" 
        v-bind:class="{active:type==item.id}"
    >
        <span>{{item.name}}</span>
    </n-button> -->
    <n-button 
        class="pl-0"
        type="default" 
        link 
        @click="$router.push('/blog/follows')" 
        v-bind:class="{active:type==0}"
    >
        <span>{{$t('message').blog.menu_follow}}</span>
    </n-button>
    <n-button
        type="default" 
        link 
        @click="$router.push('/blog')" 
        v-bind:class="{active:type==1}"
    >
        <span>{{$t('message').blog.menu_recommend}}</span>
    </n-button>
    <n-button
        type="default" 
        link 
        @click="$router.push('/blog/hot')" 
        v-bind:class="{active:type==2}"
    >
        <span>{{$t('message').blog.menu_hot}}</span>
    </n-button>
    <n-button
        type="default" 
        link 
        @click="$router.push('/blog/new')" 
        v-bind:class="{active:type==3}"
    >
        <span>最新</span>
    </n-button>
  </div>
</template>
<script>
import {
  Button,
} from '@/components';
export default {
  name: 'main-menu',
  props: {
    type:String,
  },
  components: {
    [Button.name]: Button,
  },
  data() {
    return {
      menuitems: [
        {id:'0',name:this.$t('message').blog.menu_follow,link:'/blog/follows'},
        {id:'1',name:this.$t('message').blog.menu_recommend,link:'/blog'},
        {id:'2',name:this.$t('message').blog.menu_hot,link:'/blog/hot'},
        {id:'3',name:'最新',link:'/blog/new'}
      ]
    }
  }
};
</script>
<style>
.main-menubox span{
  font-size: 1.125rem !important;
  font-weight: 500;
  margin-right: 1rem;
  color: #657786;
}
.main-menubox span:hover{
  color:black
}
.main-menubox button{
  margin: 0;
  margin-top:-5px;
  margin-bottom: 10px;
}
.main-menubox .active span{
    color:black;
    font-weight:600
}
.main-menubox .active{
    cursor:default !important;
}
textarea{
  border: #ddd 1px solid;
  border-radius:5px
}
textarea:focus{
  border: 1px solid #8590a6;;
         outline: none
}
</style>
