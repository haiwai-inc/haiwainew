<template>
    <div class="container">
      <!-- <div>
        <main-menu type="-1"></main-menu>
      </div> -->
        <div v-if="$store.state.user.userinfo.bloggerID==0">
            <regist-blog></regist-blog>
        </div>
        <div class="row mt-4" v-if="$store.state.user.userinfo.bloggerID!=0">
            
        <div class="w-100 d-sm-none p-3" style="border-bottom:1px solid #ccc">
            <div class="d-flex justify-content-between align-items-center">
                <h5>博文管理</h5><a href="javascript:void(0)" @click="openDialog(0)">+ 新建目录</a>
            </div>
            博文目录：<el-select v-model="currentTabId" placeholder="请选择" @change="changeTab(currentTabId)">
                <el-option
                v-for="item in collectionList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
                <span style="float: left">{{ item.name }}<span class="ml-2 text-muted" v-if="!item.is_publish">-隐</span></span>
                <span style="float: right; color: #8492a6; font-size: 13px">{{ item.count_article }}</span>
                </el-option>
            </el-select>
            <el-button type="text" class="ml-3" @click="show_setting=!show_setting">
                设置
                <i class="el-icon-arrow-up el-icon--right" v-if="show_setting"></i>
                <i class="el-icon-arrow-down el-icon--right" v-if="!show_setting"></i>
            </el-button>
            <div v-if="show_setting" class="pt-3">
                <left-bar 
                :currentTabId='currentTabId' 
                :collectionList='collectionList'
                @changetab="changeTab"
                @opendialog="openDialog"
                @shift="category_shift"
                @delete="category_delete"></left-bar>
            </div>
            
        </div>
        <div class="col-sm-3 d-none d-sm-block">
            <div class="d-flex justify-content-between align-items-center py-3">
                <h5>博文管理</h5><a href="javascript:void(0)" @click="openDialog(0)">+ 新建目录</a>
            </div>
            <!-- <ul>
                <li class="d-flex align-items-center li_item" 
                v-for="(item,index) in collectionList" 
                :key="index"
                :class="{active:item.id===currentTabId}"
                >
                    <div class="flex-fill collections" @click="changeTab(item.id)">{{item.name}} ({{item.count_article}})<span class="ml-2 text-muted" v-if="!item.is_publish">-隐</span></div>
                    <el-dropdown trigger="click">
                        <span class="el-dropdown-link">
                            <span v-html="iconmore3v"></span>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <span @click="openDialog(item)">
                                <el-dropdown-item icon="el-icon-setting">目录设置</el-dropdown-item>
                            </span>
                            <span @click="category_shift(index,index-1)" v-if="index!=0">
                                <el-dropdown-item icon="el-icon-arrow-up">向上移动</el-dropdown-item>
                            </span>
                            <span @click="category_shift(index,index+1)" v-if="index<collectionList.length-1">
                                <el-dropdown-item icon="el-icon-arrow-down">向下移动</el-dropdown-item>
                            </span>
                            <el-popconfirm
                                v-if="!item.is_default"
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
            </ul> -->
            <left-bar 
            :currentTabId='currentTabId' 
            :collectionList='collectionList'
            @changetab="changeTab"
            @opendialog="openDialog"
            @shift="category_shift"
            @delete="category_delete"></left-bar>
        </div>
        <div class="col-sm-9 col-12">
            <div v-infinite-scroll="getArticleList"
            infinite-scroll-disabled="noMore"
            infinite-scroll-distance="50">
               <div class="list_item row" v-for="item in articleList" :key="item.id">
                   <div class="col-10" @click="$router.push(item.postID===0?'/blog/my/editor':'/blog/p/'+item.postID)">
                       <h5 style="margin:0;" class="text-truncate" :style="{color:item.postID!==0?'black':'grey'}">
                           <el-tooltip content="已发布的博文" placement="top"  effect="light" v-if="item.postID!==0">
                                <i class="el-icon-document-checked"></i>
                           </el-tooltip>
                           <el-tooltip content="未发布的草稿" placement="top"  effect="light" v-if="item.postID===0">
                                <i class="el-icon-document" style="color:gray"></i>
                           </el-tooltip>
                           {{item.postInfo_postID.title}}
                           <small v-if="item.postID===0">(草稿)</small>
                        </h5>
                       <span class="text-muted" style="font-size:0.8rem; padding-left:32px">{{item.edit_date*1000|formatDate}}</span>
                       
                   </div>
                   <div class="col-2" style="padding:0">
                       <el-button type="text" icon="el-icon-edit" class="mr-2" @click="goeditor(item)">编辑</el-button>
                       
                       <el-popconfirm
                        placement="top-end"
                        confirm-button-text="刪除"
                        cancel-button-text='取消'
                        :title="item.postID!==0?'确定删除这篇文章吗？':'确定删除这篇草稿吗？'"
                        :hide-icon="true"
                        @confirm="delArticle(item)"
                        >
                        <el-button type="text" icon="el-icon-delete" slot="reference">删除</el-button>
                        </el-popconfirm>
                    </div>
               </div>
            </div>
            <div class="text-center py-5" v-if="loading.article"><!-- loader -->
                <i class="now-ui-icons loader_refresh spin"></i>
            </div>
            <p class="text-center py-4" v-if="noMore">没有更多文章了</p>
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
                <el-radio v-model="form.is_publish" :label="1">公开目录</el-radio>
                <el-radio v-model="form.is_publish" :label="0">隐藏目录</el-radio>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
import {  Radio } from "element-ui";
// import ArticleListItem from '../../pages/components/Main/ArticleListItem.vue';
import UserIndexSort from '../../pages/components/Main/UserIndexSort.vue';
// import CollectionList from '../../pages/components/Main/CollectionList.vue';
// import IndexHeader from '../IndexHeader';
import RegistBlog from '../../../user/login/RegistBlog';
import HaiwaiIcons from "@/components/Icons/Icons";
import {formatDate} from '@/directives/formatDate.js';

import blog from '../../blog.service';
import LeftBar from './LeftBar.vue';

export default {
  name: 'blog-user-index',
  components: {
    [Radio.name]:Radio,
    RegistBlog,
    LeftBar,
  },
  mounted() {
    this.$store.state.user.category_list(this.userID).then(res=>{
        this.collectionList=res.data;
        console.log(this.collectionList);
        this.changeTab(this.collectionList[0].id)
    })
    
  },
  methods:{
      
    changeTab(id){
        this.currentTabId = id;
        this.lastID.article = 0;
        this.articleList = [];
        this.getArticleList();
        console.log(id)
    },
    getArticleList(){
        this.loading.article=true;
        blog.category_article_list(this.currentTabId,this.lastID.article).then(res=>{
          // 需要完善翻頁
          this.getList(res);console.log(res)
        //   this.articleList = res.data.filter(obj=>obj.visible!=0);
        //   this.articleList.forEach(item=>{
        //     if(item.postInfo_postID.title==""){
        //       item.postInfo_postID.title = this.$t('message').editor.title_ph
        //     }
        //   })

        })
    },
    getList(res){
        if(res.status){
            let arr = res.data.filter(obj=>obj.visible!=0);;
            this.noMore = arr.length<30 ? true : false;
            this.lastID.article = arr.length===30 ? arr[arr.length-1].postID : this.lastID.article;
            if(this.lastID.article==0){
                this.articleList = [] ;
            }
            this.articleList = this.articleList.concat(arr) ;
            this.loading.article=false;
            console.log(arr);
            this.articleList.forEach(item=>{
                if(item.postInfo_postID.title==""){
                    item.postInfo_postID.title = this.$t('message').editor.title_ph
                }
            })
        }
    },
    addCategory(){
        this.$refs['form'].validate((valid) => {
            console.log(valid)
            if (valid) {
                this.btnDisable = true;
                blog.category_add(this.form.name,this.form.is_publish).then(res=>{
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
            blog.category_update(this.form.name,this.form.is_publish,item.id).then(res=>{
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
    category_shift(item){
        var from = item[0];
        var to = item[1];
        let arr = this.collectionList;
        let e = arr[from];
        let sort = [];
        if(this.shiftable){
            arr.splice(from,1);
            arr.splice(to,0,e);
            arr.forEach(item=>{
                sort.push(item.id)
            })
        }
        this.shiftable = false;
        blog.category_shift(this.userID,sort.toString()).then(res=>{
            if(res.status){
                this.shiftable = true
            }
        })
        console.log(this.collectionList);
    },
    delArticle(item){
        if(item.postID!==0){
          blog.article_delete(item.postID,0).then(res=>{
            if(res.status){
              this.getArticleList();
              this.getCategories(this.userID);
            }
          })
        }else{
          this.$store.state.user.draft_delete(item.postID).then(res=>{
            if(res.status){
              this.getArticleList();
            }
          })
        }
    },
    getCategories(id){
        this.$store.state.user.category_list(id).then(res=>{
          console.log(res);
          if(res.status){
            this.collectionList = res.data;
          }
          this.btnDisable = false;
          this.form.name = '';
          this.form.is_publish = 1;
          this.dialogFormVisible = false;
        })
      },
    openDialog(item){
        this.item = item
        this.formTitle = item?"目录设置":"新建博文目录" ;
        this.form.name = item?item.name:''
        this.form.is_publish = item?item.is_publish:1
        this.dialogFormVisible = true
        console.log(this.item)
    },
    submit(){
        if(this.item!==0){
            this.updateCategory(this.item)
        }else{
            this.addCategory()
        }
    },
    goeditor(item){console.log(item)
        let url = '/blog/my/editor/'
        url+=item.postID===0?'':'?postid='+item.postID;
        this.$router.push(url);
    }
  },
  data() {
    var checkNameSame = (rule, value, callback) =>{
        if(this.item==0){
            this.collectionList.forEach(item=>{
                if(item.name === value){
                    return callback(new Error('与现有目录名重复'))
                }
            })
        }
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
        shiftable:true,
        form:{
            name:'',
            is_publish:1
        },
        rules:{
            name:[
                {required: true, message: '请输入目录名称', trigger: 'blur'},
                { validator:checkNameSame, trigger: 'blur' }]
        },
        show_setting:false,
        authorInfo : {},
        articlelists: [],
        collectionList:[],
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
    cursor: pointer;
    font-size: 18px;
    padding: 16px ;
}
</style>
