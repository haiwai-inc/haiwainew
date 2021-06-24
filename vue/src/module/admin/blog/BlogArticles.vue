<template>
    <div>
        <el-radio-group v-model="type" class="ml-5 mb-3" @change="changtab">
            <el-radio-button label="recent">全部博文</el-radio-button>
            <el-radio-button label="recommand">已推荐博文</el-radio-button>
        </el-radio-group>
        <ul v-if="articlelist.length>0" style="list-style-type:none">
            <li v-for="item in articlelist" :key="item.id">
                <div class=" mb-2 d-flex p-2" style="background-color:#ddeeff66">
                    <span >推荐标题：<b>{{item.recommend?item.recommend.title:item.postInfo_postID.title}}</b></span>
                    
                    <el-popover v-if="item.recommend"
                    placement="bottom-start"
                    width="350" 
                    :ref="'pop-'+item.postID"
                    trigger="click">
                        <el-form :model="item.recommend" :rules="rules" ref="titleForm" label-width="0px">
                        <el-form-item
                            prop="title"
                            label=""
                        >
                            <el-input v-model="item.recommend.title"></el-input>
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
                    <a href="javascript:void(0)" class="ml-3" v-if="!item.recommend" @click="recommend(item)">推荐</a>
                    <a href="javascript:void(0)" v-if="item.recommend" @click="remove_recommend(item)" class="ml-3 text-danger">撤销推荐</a>
                
                </div>
                <article-list-item :data="item"></article-list-item>
            </li>
        </ul>
        <div class="text-center py-5" v-if="loading"><!-- loader -->
            <i class="now-ui-icons loader_refresh spin"></i>
        </div>
        <p class="text-center py-4" v-if="!noMore && !loading">
            <el-button type="primary" plain @click="getlist">加载更多内容</el-button>
        </p>
        <p class="text-center py-4" v-if="noMore && !loading">没有更多了</p>
    </div>
</template>
<script>
import ArticleListItem from "../../blog/pages/components/Main/ArticleListItem";
import HaiwaiIcons from "@/components/Icons/Icons";
import { RadioButton,RadioGroup } from 'element-ui';

export default {
    name:"blog-articles",
    components:{
        ArticleListItem,
        [RadioButton.name]:RadioButton,
        [RadioGroup.name]:RadioGroup
    },
  computed:{
    disabled () {
      return this.noMore || this.loading
    }
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
            type:'recent',
            icon_edit:HaiwaiIcons.icon_edit,
            articlelist:[],
            lastID:0,
            noMore:false,
            loading:false,
            titleForm:{title:''},
            rules:{
                title:[
                    { required: true, validator: validateTitle, trigger: 'blur' },
                ],
            },
        } 
    },
    mounted() {
        this.getlist()
    },
    methods:{
        changtab(){
            this.articlelist =[];
            this.getlist()
        },
        async getlist(){
            this.loading = true;
            let res = await this.$store.state.user.admin_article_list(this.type,this.lastID);
            this.setList(res);
        },

        setList(res){
            this.loading = false;
            if(res.status){
                let arr = res.data;
                this.noMore = arr.length<40 ? true : false;
                this.lastID = arr.length<40 ? this.lastID : arr[arr.length-1].comment_date ;
                this.articlelist = this.articlelist.concat(arr) ;
                console.log(arr,this.lastID,this.noMore);
            }
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
            let pop = 'pop-'+item.postID;
            this.$store.state.user.article_recommand_update(item.postID,item.recommend.title).then(res=>{
                if (res.status){
                    this.$refs[`${pop}`][0].doClose();
                    this.getlist();
                }
            })
        }
    }
}
</script>
<style>

</style>