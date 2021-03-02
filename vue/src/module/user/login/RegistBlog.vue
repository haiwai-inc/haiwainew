<template>
        <div class="ml-auto mr-auto text-center mt-4" style="max-width:375px;font-size:1.25rem">
            请点击下面按钮激活您的博客。<br>我们会自动为您生成一个博客名<br><b v-if="$store.state.user.userinfo.userinfo_id">{{$store.state.user.userinfo.userinfo_id.username}}的博客</b><br>如果您对这个名字不满意,可以在“个人设置”中的“博客设置”修改。<br><br>
            <el-button type="primary" style="width:300px" round @click="onSubmit">立即激活博客</el-button>
        </div>
</template>
<script>
export default {
    name: 'regist-blog',
    methods:{
        onSubmit(){
            this.$store.state.user.blog_register()
        }
    },
    beforeCreate() {
        this.$store.state.user.getUserStatus().then(r=>{
        if(r.data.bloggerID){
            this.$router.push('/blog/editor');
        }
        });
    },
}
</script>
<style>

</style>