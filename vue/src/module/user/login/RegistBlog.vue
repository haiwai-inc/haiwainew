<template>
        <div class="ml-auto mr-auto text-center mt-4" style="max-width:375px;font-size:1.25rem">
            <p>{{$t('message').blog.blog_regist_prefix}}</p>
            <b v-if="$store.state.user.userinfo.userinfo_id">{{$store.state.user.userinfo.userinfo_id.username}}的博客</b>
            <p class="pt-3">{{$t('message').blog.blog_regist_suffix}}</p>
            <el-button type="primary" style="width:300px" round @click="onSubmit">
                {{$t('message').blog.btn_regist_now}}
            </el-button>
        </div>
</template>
<script>
export default {
    name: 'regist-blog',
    methods:{
        onSubmit(){
            this.$store.state.user.blog_register().then(res=>{
                if(res.status){
                    this.$store.state.user.getUserInfo(this.$store.state.user.userinfo.UserID).then(res=>{
                        this.$router.push('/blog/user/'+this.$store.state.user.userinfo.UserID);
                    })
                }
            })
        }
    },
    beforeCreate() {
        this.$store.state.user.getUserStatus().then(r=>{
            if(r.data.bloggerID){
                // this.$router.push('/blog/user/'+this.$store.state.user.userinfo.UserID);
            }
        });
    },
}
</script>
<style>

</style>