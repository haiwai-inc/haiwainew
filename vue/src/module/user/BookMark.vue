<template>
  <div class="container">
     
    <main-menu></main-menu>
    
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-sm-12 col-12 col-lg-8">
        <h6 class="font-weight-normal pt-3"> 我的收藏</h6>
        <div class="text-center mt-5" v-if="articlelists.length==0">
          <h5>您的文件夹是空的, 您还没有收藏任何文章</h5>
          <p>当您收藏了文章, 它就会出现在这里</p>
        </div>
        <div v-if="articlelists.length>0">
          <article-list-item 
            v-for="item in articlelists"
            v-bind:key="item.postID"
            v-bind:data="item"
            type="bookmark"
            @delete-bookmark="deletBookmark">
          </article-list-item>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from '../blog/pages/components/Main/MainMenu';
import ArticleListItem from '../blog/pages/components/Main/ArticleListItem.vue';

export default {
  name: 'bookmark',
  components: {
    MainMenu,
    ArticleListItem,
  },
  mounted:function(){
    this.getBookmark(0);
  },
  data() {
    return {
        user:this.$store.state.user,
        articlelists:[]
    };
  },
  methods:{
    async getBookmark(lastID){
      let v = await this.user.bookmark_list(lastID)
      if(v.status){
        this.articlelists = v.data;
        console.log(v.data)
      }
    },
    async deletBookmark(postID){console.log(postID)
      let v = await this.user.bookmark_delete(postID);
      if(v.status){
        this.getBookmark(0)
      }
    }
  }
};
</script>
<style></style>
