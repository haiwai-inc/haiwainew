<template>
            <div class="box my-4 pl-3 bolger-box sticky-top">
              <span v-html="icons.helpcenter"></span>
              <span class="ml-3 mb-3 h6">帮助中心</span>
              <div class=row>
                <div class="col-6 mt-3 text-secondary">如何上传图片</div>
                <div class="col-6 mt-3  text-secondary">怎样发视频</div>
                <div class="col-12 mt-3 text-secondary">如何贴音乐</div>
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
<style></style>
