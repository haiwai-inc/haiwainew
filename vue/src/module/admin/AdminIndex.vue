<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-2">博客管理</div>
            <div class="col-sm-10"><blog-articles></blog-articles></div>
        </div>
    </div>
</template>
<script>
import BlogArticles from './blog/BlogArticles.vue'

export default {
    name:"admin-index",
    components:{
        BlogArticles
    }
}
</script>
<style>

</style>