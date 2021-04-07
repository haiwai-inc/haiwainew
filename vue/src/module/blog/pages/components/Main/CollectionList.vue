<template>
  <div class="collection-box">
    <span class="blogger-box">
      <bloger-list-item v-if="userdata" :data="userdata" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
    </span>
    <div class="collection-title d-flex justify-content-between">
      <h4>{{title}}</h4>
      <el-button v-if="$store.state.user.userinfo.bloggerID==data[0].bloggerID" type="text" link v-bind:style="{paddingRight:0 }" @click="go()"><i class="el-icon-setting"></i> 管理博文目录</el-button>
    </div>
    <div v-if="data.length===0" class="pl-3">暂无文集</div>
    <div v-if="data.length!==0">
      <collection-list-item 
      v-for="(item,index) in data" 
      v-bind:key="index" 
      :data="item"></collection-list-item>
    </div>
    <!-- <div v-if="data.length>4" class="collection-footer">展开全部 <icon-right class="ratate90"></icon-right></div> -->
  </div>
</template>
<script>
// import {
//     // IconPlus,
//     IconRight
// } from '@/components/Icons';
import {
  Button,
} from '@/components';
import CollectionListItem from './CollectionListItem';
import BlogerListItem from './BlogerListItem';

export default {
  name: 'collection-list',
  props: {
    type:String,
    title:String,
    data:{},
    userdata:{}
  },
  data(){
    return {
      collapse:true
    }
  },
  components: {
    [Button.name]: Button,
    CollectionListItem,
    BlogerListItem,
    // IconPlus,
    // IconRight
  },
  methods: {
    go(){
      this.$router.push("/blog/my/")
    }
  },
  mounted() {
    console.log(this.$store.state.user.userinfo)
  },
};

</script>
<style>
.collection-box{
  background-color: aliceblue;
  border-radius: 16px;
  padding:18px 0;
}
.collection-box .collection-title{
  padding:0 18px;
}
.ratate90{
  -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}
.collection-box .collection-footer{
  cursor: pointer;
  border-top:1px solid #ddd;
  padding: 10px 18px 0px;
  font-size: 0.85rem;
  text-align: right;
}

</style>
