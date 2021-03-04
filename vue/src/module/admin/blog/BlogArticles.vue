<template>
    <div>
        <ul v-if="articlelist.length>0" style="list-style-type:none">
            <li v-for="item in articlelist" :key="item.id">
                <div class="mt-3 mb-2 d-flex">
                    <span v-if="item.recommend.id">推荐标题：<b>{{item.recommend.title}}</b></span>
                    <el-popover v-if="item.recommend.id"
                    placement="bottom-start"
                    width="350" 
                    @show="setName(item)"
                    ref="title"
                    trigger="click">
                        <el-form :model="item.postInfo_postID" :rules="rules" ref="titleForm" label-width="0px">
                        <el-form-item
                            prop="title"
                            label=""
                        >
                            <el-input v-model="item.postInfo_postID.title"></el-input>
                        </el-form-item>
                        </el-form>
                        <el-button 
                        type="primary"
                        round 
                        simple
                        @click="update(item)"
                            >修改</el-button
                        >
                        <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_edit" class="icon"></span>修改标题</a>
                    </el-popover>
                    <a href="javascript:void(0)" v-if="!item.recommend.id" @click="recommend(item)">推荐</a>
                    <a href="javascript:void(0)" v-if="item.recommend.id" @click="remove_recommend(item)" class="text-danger">撤销推荐</a>
                
                </div>
                <article-list-item :data="item"></article-list-item>
            </li>
        </ul>
    </div>
</template>
<script>
import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem";
import HaiwaiIcons from "@/components/Icons/Icons";

export default {
    name:"blog-articles",
    components:{
        ArticleListItem
    },
    data(){
        var validateTitle =(rule,value,callback)=>{
        if(value===''){
            callback(new Error('请输入标题'));
        }else{
            callback();
        }
      };
        return {
            icon_edit:HaiwaiIcons.icon_edit,
            articlelist:[],
            lastID:0,
            titleForm:{title:''},
            rules:{
                title:[
                    { required: true, validator: validateTitle, trigger: 'blur' },
                ],
            },
        } 
    },
    mounted() {
        // this.$store.state.user.admin_article_list(0).then(res=>{
        //     if(res.status){
        //         this.articlelist = res.data
        //         console.log(this.articlelist)
        //     }
        // })
        this.getlist()
    },
    methods:{
        async getlist(){
            let res = await this.$store.state.user.admin_article_list(this.lastID)
            this.articlelist = res.status?res.data:''
            console.log(this.articlelist)
        },
        recommend(item){
            this.$store.state.user.article_recommand_add(item.postID).then(res=>{
                if (res.status){
                    this.getlist();
                }
            })
        },
        remove_recommend(item){
            this.$store.state.user.article_recommand_delete(item.postID).then(res=>{
                if (res.status){
                    this.getlist();
                }
            })
        },
        update(item){
            this.$store.state.user.article_recommand_update(item.postID,item.postInfo_postID.title).then(res=>{
                if (res.status){
                    this.getlist();
                }
            })
        }
    }
}
</script>
<style>

</style>