<template>
    <div v-if="this.follows.authorList.length>0">
        <h6 @click="getfollower"
        class="pb-2 font-weight-normal"
        >{{follows.title}}</h6>
        <bloger-list-item v-for="(item,index) in follows.authorList" :key="index" :data="item" :usertype="'follower'"></bloger-list-item>
    </div>
</template>
<script>
import BlogerListItem from '../../blog/pages/components/Main/BlogerListItem'
export default {
  name: 'notice-follow',
  components: {
    BlogerListItem
  },
  created() {
    this.getfollower();
  },
  methods:{
    async getfollower(){
      let v= await this.$store.state.user.my_follower_list(0);
      if(v.status){
        this.follows.authorList = v.data;
        console.log(this.follows.authorList);
      }
    }
  },
  data(){
    return{
        follows:{
          title:'我的粉丝',
          authorList : []
      }
    }
  }
}
</script>
<style>

</style>