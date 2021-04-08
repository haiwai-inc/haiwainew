<template>
    <div>
        <h6 class="pb-3 font-weight-normal" @click="getlike">我收到的赞</h6>
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