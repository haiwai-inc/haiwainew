<template>
            <div class="box mt-4 pl-3 bolger-box sticky-top help">
              <span v-html="icons.helpcenter"></span>
              <span class="ml-3 mb-3 "><a href="http://www1.haiwai.com/blog/c/618/4393" class="title font-weight-bold text-dark">帮助中心</a></span>
              <div class=row>
              <div class="col-12 mt-3 text-secondary"><a href="https://www1.haiwai.com/blog/p/1636880" class="small">复制文学城博客和相关问题解答</a></div>
              </div>
              <div class=row>
               <div class="col-12 mt-3 text-secondary"><a href="https://www1.haiwai.com/blog/p/1636901" class="small">如何在海外注册和登入</a></div>
              </div>
              <div class=row>
               <div class="col-6 mt-3 text-secondary"><a href="https://www1.haiwai.com/blog/p/1448485" class="small">如何发博文</a></div>
                <div class="col-6 mt-3 text-secondary"><a href="https://www1.haiwai.com/blog/p/1448486" class="small">如何上传图片</a></div>
              </div>
              <div class=row>  
                <div class="col-6 mt-3  text-secondary"><a href="https://www1.haiwai.com/blog/p/1448487" class="small">如何发视频</a></div>
                <div class="col-6 mt-3 text-secondary"><a href="https://www1.haiwai.com/blog/p/1448488" class="small">如何贴音乐</a></div>
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
.help a 
{color: #6c757d;
font-size:1rem}

.help a.title
{font-size:1.3rem} 

.bolger-box {
        background-color: #f6f9fc;
        border-radius: 16px;
        padding: 18px 0;
        z-index: auto
}
</style>
