<template>
  <div class="container">
        <div>
         <main-menu></main-menu>
        </div>
    <h6 class="font-weight-normal pb-3 ml-2"> 我的收藏</h6>

   <!-- 用户没有收藏任何文章时候默认 -->
   <div class="text-center mt-5">
      <h5>您的文件夹是空的, 您还没有收藏任何文章</h5>
      <p>当您收藏了文章, 它就会出现在这里</p>
    </div>
   <!-- 用户收藏文章后 -->
    <div class="row">
        <div class="col-sm-12 col-12">
          <div>
            <article-list-item 
              v-for="item in articlelists"
              v-bind:key="item.id"
              v-bind:data="item"
              type="0">
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
    }
  }
};
</script>
<style></style>
