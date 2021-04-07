<template>
    <div class="container">
      <!-- <div>
        <main-menu type="-1"></main-menu>
      </div> -->
      <div v-if="$store.state.user.userinfo.bloggerID==0">
          <regist-blog></regist-blog>
      </div>
      <div class="row mt-4" v-if="$store.state.user.userinfo.bloggerID!=0">
        <div class="col-sm-3 d-none d-sm-block">
            <div class="d-flex justify-content-between">
                <h5>我的博文目录</h5><a href="javascript:void(0)" @click="openDialog(0)">+ 新建目录</a>
            </div>
            <ul>
                <li class="d-flex align-items-center li_item" 
                v-for="(item,index) in collectionList" 
                :key="index"
                :class="{active:item.id===currentTabId}"
                >
                    <div class="flex-fill collections">{{item.name}} ({{item.count_article}})</div>
                    <el-dropdown trigger="click">
                        <span class="el-dropdown-link">
                            <span v-html="iconmore3v"></span>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <span @click="openDialog(item)"><el-dropdown-item icon="el-icon-edit">编辑文集名</el-dropdown-item></span>
                            <el-dropdown-item v-if="index!=0" icon="el-icon-arrow-up">向上移动</el-dropdown-item>
                            <el-dropdown-item v-if="index<collectionList.length-1" icon="el-icon-arrow-down">向下移动</el-dropdown-item>
<!-- 
    删除确认
 -->
                            <el-popconfirm
                                v-if="collectionList.length>1"
                                placement="top-end"
                                confirm-button-text="刪除"
                                cancel-button-text='取消'
                                :title="$t('message').editor.wenji_comfirm_title+item.name+$t('message').editor.wenji_comfirm_title1"
                                :hide-icon="true"
                                @confirm="category_delete(item)"
                            >
                                <a href="javascript:void(0)" slot="reference">

                                    <el-dropdown-item divided icon="el-icon-delete">
                                        {{$t('message').editor.wenji_delet_menu}}
                                    </el-dropdown-item>
                                </a>
                            </el-popconfirm>
                        </el-dropdown-menu>
                    </el-dropdown>
                </li>
            </ul>
            <!-- <user-index-sort :data="sortList"></user-index-sort> -->
            <!-- <div class="collection-list mt-3">
                <collection-list v-bind:data="collectionList" :userdata="false" title="文集"></collection-list>
            </div> -->
        </div>
        <div class="col-sm-9 col-12">
            <div></div>
            <!-- <div class="profile-header mt-2 mb-3">
            <ul class="nav justify-content-center">
                <li class="col nav-item text-center px-0" v-for="(item,index) in this.tabs" :key="index">
                    <a 
                    class="nav-link" 
                    :class="{active:currentTabId==item.id}" 
                    href="#"
                    @click="changeTab(item.id)">{{item.text}}</a>
                </li>
            </ul>
            </div> -->
            <div v-if="articleList.length>0"
            v-infinite-scroll="getArticleList"
            infinite-scroll-disabled="noMore"
            infinite-scroll-distance="50">
               <div class="list_item row" v-for="item in articleList" :key="item.id">
                   <div class="col-10">
                       
                       <h5 style="margin:0;" class="text-truncate">{{item.title}}</h5>
                       <span class="text-muted" style="font-size:0.8rem">{{item.edit_date*1000|formatDate}}</span>
                       
                   </div>
                   <div class="col-2" style="padding:0">
                       <el-button type="text" icon="el-icon-edit" @click="$router.push('/blog/my/editor/?id='+item.postID)">编辑</el-button>
                       <el-button type="text" icon="el-icon-delete">删除</el-button>
                    </div>
               </div>
            </div>
            <div class="text-center py-5" v-if="loading.article"><!-- loader -->
                <i class="now-ui-icons loader_refresh spin"></i>
            </div>
            <p class="text-center py-4" v-if="noMore">没有更多了</p>
        </div>
        
      </div>

        <el-dialog width="350px" :title="formTitle" :visible.sync="dialogFormVisible">
            <el-form :model="form" :rules="rules" ref="form">
                <el-form-item label="" prop="name">
                    <el-input 
                    v-model="form.name" 
                    autocomplete="off" 
                    maxlength="16" 
                    show-word-limit 
                    placeholder="输入博文目录名">
                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
// import MainMenu from '../../pages/components/Main/MainMenu.vue';
// import ArticleListItem from '../../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../../pages/components/Main/UserIndexSort.vue';
// import CollectionList from '../../pages/components/Main/CollectionList.vue';
// import IndexHeader from '../IndexHeader';
import RegistBlog from '../../../user/login/RegistBlog';
import HaiwaiIcons from "@/components/Icons/Icons";
import {formatDate} from '@/directives/formatDate.js';

import blog from '../../blog.service';

export default {
  name: 'blog-user-index',
  components: {
    // MainMenu,
    // ArticleListItem,
    // CollectionList,
    // IndexHeader,
    RegistBlog,
  },
  mounted() {
    blog.category_list(this.userID).then(res=>{
        this.collectionList=res.data;
        console.log(this.collectionList[0].id,this.$store.state.user.userinfo);
        this.changeTab(this.collectionList[0].id)
    })
    
  },
  methods:{
      
    changeTab(id){
        this.currentTabId = id;
        this.lastID.article = 0;
        this.articlelists = [];
        this.getArticleList();
    },
    getArticleList(){
        blog.category_article_list(this.currentTabId,0).then(res=>{
          // 需要完善翻頁
          console.log(res);
          this.articleList = res.data.filter(obj=>obj.visible!=0);
          this.articleList.forEach(item=>{
            if(item.postInfo_postID.title==""){
              item.postInfo_postID.title = this.$t('message').editor.title_ph
            }
          })
          this.loading.article=false;
        })
    },
    getList(res){
        if(res.status){
            let arr = res.data;
            this.noMore = arr.length<30 ? true : false;
            this.lastID.article = arr.length===30 ? arr[arr.length-1].id : this.lastID.article;
            this.articlelists = this.articlelists.concat(arr) ;
            this.loading.article=false;
            console.log(arr);
        }
    },
    addCategory(){
        this.$refs['form'].validate((valid) => {
            console.log(valid)
            if (valid) {
                this.btnDisable = true;
                blog.category_add(this.form.name).then(res=>{
                    console.log(res);
                    if(res.status){
                        this.getCategories(this.userID);
                    }
                })
            }
        })
    },
    updateCategory(item){
        this.$refs['form'].validate((valid) => {
          if (valid) {
            this.btnDisable = true;
            blog.category_update(this.form.name,item.id).then(res=>{
              if(res.status){
                this.getCategories(this.userID);
              }
            })
            }
        })
    },
    category_delete(item){
        blog.category_delete(item.id).then(res=>{
          if(res.status){
            this.getCategories(this.userID);
          }
        })
    },
    getCategories(id){
        blog.category_list(id).then(res=>{
          console.log(res);
          if(res.status){
            this.collectionList = res.data;
          }
          this.btnDisable = false;
          this.form.name = '';
          this.dialogFormVisible = false;
        })
      },
    openDialog(item){
        this.item = item
        this.formTitle = item?"修改目录名":"新建博文目录" ;
        this.form.name = item?item.name:''
        this.dialogFormVisible = true
        console.log(this.item)
    },
    submit(){
        if(this.item!==0){
            this.updateCategory(this.item)
        }else{
            this.addCategory()
        }
    }
  },
  data() {
    var checkNameSame = (rule, value, callback) =>{
        this.collectionList.forEach(item=>{
            if(item.name === value){
                return callback(new Error('与现有目录名重复'))
            }
        })
        callback()
    }
    return {
        iconmore3v: HaiwaiIcons.iconmore3v,
        userID:this.$store.state.user.userinfo.bloggerID,
        token:this.$route.query.haiwai_token,
        articleList:[],
        currentTabId:0,
        noMore:false,
        lastID:{article:0,wenji:0},
        loading:{article:true,wenji:true},
        dialogFormVisible: false,
        btnDisable:false,
        formTitle:'',
        item:0,
        form:{
            name:''
        },
        rules:{
            name:[
                {required: true, message: '请输入活动名称', trigger: 'blur'},
                { validator:checkNameSame, trigger: 'blur' }]
        },
        tabs:[
            {
                id:0,
                text:'最新博文',
            },{
                id:1,
                text:'最热博文',
            },{
                id:2,
                text:'新评博文',
            }
        ],
        authorInfo : {},
        articlelists: [],
        collectionList:[],
        collectionList0 : [
            {
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "日记",
                visible: 1,
            },{
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "文集1",
                visible: 1,
            },{
                bloggerID: 1,
                count_article: 1,
                id: 1,
                name: "文集2",
                visible: 1,
            },
        ],
    };
  },
  filters: {
    formatDate(time) {
        var date = new Date(time);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  }
};
</script>
<style>
.profile-header .nav-link{
    color:#657786  
}
.profile-header .nav-link.active {
    color: #1D1D1D;
    border-bottom: 2px solid #39B8EB;
    font-weight: 600;
}
ul{padding: 0;}
ul li{list-style-type:none;}
.li_item,.list_item{
    border-bottom: 1px solid #eee;
}
.list_item{
    padding:10px 0
}
.active{
    background-color: #f2f8fd;
}
.collections{
    font-size: 18px;
    padding: 16px ;
}
</style>
