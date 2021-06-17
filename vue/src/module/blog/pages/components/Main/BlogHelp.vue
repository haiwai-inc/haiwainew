<template>
            <div class="box mt-4 pl-3 bolger-box sticky-top help">
              <span v-html="icons.helpcenter"></span>
              <span class="ml-3 mb-3 h6"><a href="http://www1.haiwai.com/blog/c/618/4393" class="small">帮助中心</a></span>
              <div class=row>
               <div class="col-6 mt-3 text-secondary"><a href="http://www1.haiwai.com/blog/p/1448485" class="small">如何发布博客文章</a></div>
                <div class="col-6 mt-3 text-secondary"><a href="http://www1.haiwai.com/blog/p/1448486" class="small">如何上传图片</a></div>
              </div>
              <div class=row>  
                <div class="col-6 mt-3  text-secondary"><a href="http://www1.haiwai.com/blog/p/1448487" class="small">如何发视频</a></div>
                <div class="col-6 mt-3 text-secondary"><a href="http://www1.haiwai.com/blog/p/1448488" class="small">如何贴音乐</a></div>
              </div>
            </div>
</template>
<script>
import icons from "@/components/Icons/Icons";
export default {
  name: 'blog-help',
  components: {  
  },
    data() {
     return {

           icons:icons,
    };
  },
};
</script>
<style>
.bolger-box a 
{color: #6c757d!important;
font-size:1rem}
.bolger-box {
        background-color: #f6f9fc;
        border-radius: 16px;
        padding: 18px 0;
}
</style>
