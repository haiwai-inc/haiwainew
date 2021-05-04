<template>
    <div>
        <h6 class="pb-3 font-weight-normal" @click="getlike">我收到的赞</h6>
          <div class="row  no-gutters flex-md-row mb-4  h-md-450 position-relative" v-if="articlelists.length==0">
             <div class="col-12 pt-4 col-md-8"><img src="/img/like.png" class="logo"></div>
             <div class="col-12 col-md-3 p-4 d-flex flex-column position-static">
                <div class="row featurette ">
                   <div class="mt-5 col-md-12 m torder-md-2">
                      <h5 class="featurette-heading">如果有人给您的文章点赞</h5>
                      <p class="lead text-dark">您就可以在<br>“我收到的赞”<br>查看详细信息</p>
                   </div>
                </div>
             </div>
          </div>
          <article-list-item
            v-for="item in articlelists"
            v-bind:key="item.postID"
            v-bind:data="item"
            type="0"
          >
          </article-list-item>
    </div>
</template>
<script>
import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem.vue";
export default {
  name: 'notice-like',
  components: {
    ArticleListItem
  },
  created() {
    this.getlike();
  },
  methods:{
    async getlike(){
      let v = await this.$store.state.user.my_buzz_article_list(0);
      if(v.status){
        this.articlelists = v.data
      }
      console.log(this.articlelists)
    },
  },
  data(){
    return{
      articlelists: [],
    }
  }
}
</script>
<style>

</style>