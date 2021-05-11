<template>
  <div>  
        <h6 @click="getfollower" class="pb-2 font-weight-normal">{{follows.title}}</h6>
        <div class="row  no-gutters flex-md-row mb-4  h-md-450 position-relative"  v-if="this.follows.authorList.length==0">
          <div class="col-12 pt-4 col-md-8"><img src="/img/follow.webp" class="logo"></div>
           <div class="col-12 col-md-3 p-4 d-flex flex-column position-static">
              <div class="row featurette ">
                 <div class="mt-5 col-md-12 m torder-md-2">
                    <h5 class="featurette-heading">
                    如果有人关注了您</h5>
                    <p class="lead text-dark">您就可以在<br>“我的粉丝”里看到</p>
                 </div>
              </div>
           </div>
        </div>
    <div v-if="this.follows.authorList.length">
        <bloger-list-item v-for="(item,index) in follows.authorList" :key="index" :data="item" :usertype="'follower'"></bloger-list-item>
    </div>
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