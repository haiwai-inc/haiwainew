<template>
  <div class="publisher">
    <mini-navbar title="发博文"></mini-navbar>
    <div class="container">
    <div class="row editorbox">
      <div class="col-md-9 editor mx-auto col-12" id="editor_container" ref="editorContainer">
        <!-- <div class="col-12 d-flex mt-3">
          <div class=" flex-fill"><h5>发表博客文章</h5></div>
          <div class="mr-3 text-muted">所属目录：我的文章</div>
          <el-dropdown trigger="click">
            <span class="el-dropdown-link">
              更多功能<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item icon="el-icon-folder">移动文章</el-dropdown-item>
              <el-dropdown-item icon="el-icon-arrow-up">置顶文章</el-dropdown-item>
              <el-dropdown-item icon="el-icon-check">允许评论</el-dropdown-item>
              <el-dropdown-item icon="el-icon-check">允许转载</el-dropdown-item>
              <el-dropdown-item divided icon="el-icon-delete">删除文章</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div> -->
        
        <div class="d-flex justify-content-between py-2" ref="titleBox">
          <input
            class="editorTitle"
            type="text"
            v-model="curentArticle.postInfo_postID.title"
            :placeholder="$t('message').editor.title_ph"
          />
        </div>

          <!-- 编辑器 -->
          
          <!-- api-key="kslxtlgbsr246by5yerx9t5glaje0cgp5hwaqf2aphdo3aaw" -->
        <editor 
          :init="editorConfig"
          v-model="curentArticle.postInfo_postID.msgbody"
        />
        
      </div>
      <div class="col-md-3" style="padding-top:75px">
        <div ref="saving" style="font-size:13px;padding-left:8px;">
          <span v-if="flags.autosaving">{{$t('message').editor.autosaving}}</span> 
          <span v-if="flags.autosaved" class="text-success">{{$t('message').editor.autosaved}}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span>文章所属目录：</span><el-button type="text" href="javascript:void(0)" @click="openDialog(0)">+ 新建目录</el-button></div>
        <el-select v-if="wenjiList.length>0" v-model="curentArticle.categoryID" placeholder="请选择">
          <el-option
            v-for="item in wenjiList"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        
        <div class="py-5">
          <span>文章标签：</span>
          <el-tag
            class="mr-2 mb-2"
            v-for="(item,index) in curentArticle.postInfo_postID.tags" :key="item.id"
            closable
            :disable-transitions="false"
            @close="removetag(index)">
            {{item.name}}
          </el-tag>
          <el-input
            class="input-new-tag"
            v-if="inputVisible"
            v-model="tag"
            ref="saveTagInput"
            size="small"
            @keyup.enter.native="handleInputConfirm"
            @blur="handleInputConfirm"
          >
          </el-input>
          <el-button v-else class="button-new-tag" size="small" @click="showInput">+ 添加新标签</el-button>
        </div>
        <div class="pb-5">
          可否评论：
          <el-radio v-model="curentArticle.is_comment" :label="1">是</el-radio>
          <el-radio v-model="curentArticle.is_comment" :label="0">否</el-radio>
        </div>
      </div>
      <div class="col-12">
        <div class="mt-2 text-muted">注：发表博客文章时请不要提供广告信息或不友好信息，本站保留拒绝的权利。</div>
        <!-- <textarea id="editorText"> -->
        <!-- </textarea>发布按钮显示条件(curentArticle.visible!==1&&curentArticle.postInfo_postID.title!='') -->
        <div ref="saveBox" class="m-2">
          <n-button v-if="curentArticle.postInfo_postID.title!=''"
            type="primary"
            round
            simple
            @click.native="modals.publish=true"
            class="editbtn"
          >
            {{curentArticle.visible==-1?'发布文章':'更新文章'}}
          </n-button>
          <n-button
            v-if="false"
            type="default"
            link
            @click.native="save"
            class="editbtn"
          >
            <icon-x :style="{ fill: 'gray' }"></icon-x>取消发布
          </n-button>
        </div>
          <!-- <n-button 
            type="primary" 
            round 
            simple 
            @click="save"
            class="editbtn"
            >
              <icon-plus class="editicon"></icon-plus>保存
            </n-button> -->
      </div>
    </div>
  </div>
  <el-dialog width="350px" :title="'新建博文目录'" :visible.sync="dialogFormVisible">
    <el-form :model="categoryForm" :rules="rules" ref="categoryForm">
        <el-form-item label="" prop="name">
            <el-input 
            v-model="categoryForm.name" 
            autocomplete="off" 
            maxlength="16" 
            show-word-limit 
            placeholder="输入博文目录名">
            </el-input>
        </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="addCategory">确 定</el-button>
    </div>
  </el-dialog>
    <!-- Publish Modal -->
    <modal :show.sync="modals.publish" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        发布文章
      </h4>
      <p>
        您可以添加一些适合的标签，能方便分类检索。<br>文章也更容易让其他用户看到。
      </p>
      <!-- <p>
        <el-tag
      class="mr-2"
  v-for="(item,index) in curentArticle.postInfo_postID.tags" :key="item.id"
  closable
  :disable-transitions="false"
  @close="removetag(index)">
  {{item.name}}
</el-tag>
      <el-input
  class="input-new-tag"
  v-if="inputVisible"
  v-model="tag"
  ref="saveTagInput"
  size="small"
  @keyup.enter.native="handleInputConfirm"
  @blur="handleInputConfirm"
>
</el-input>
<el-button v-else class="button-new-tag" size="small" @click="showInput">+ 添加新标签</el-button></p> -->
      <!-- <input v-model="tag" type="text"> <n-button class="ml-2" @click="pushtag(tag)" type="default">添加</n-button> -->
      <template slot="footer">
        <n-button
          class="mr-3"
          type="default"
          link
          @click.native="modals.publish = false"
        >
          取消
        </n-button>
        <n-button v-if="curentArticle.visible==-1" type="primary" round simple @click="publish()" :disabled="flags.publish">
          发布
        </n-button>
        <n-button v-if="curentArticle.visible!=-1" type="primary" round simple @click="article_update()" :disabled="flags.publish">
          发布更新
        </n-button>
      </template>
    </modal>

  </div>
</template>
<script>
import Editor from '@tinymce/tinymce-vue'
// import CategoryList from "./components/CategoryList.vue";
// import CategoryArticleList from "./components/CategoryArticleList";
import MiniNavbar from "../../../../layout/MiniNavbar";
import { Button, Modal, FormGroupInput } from "@/components";
import { Collapse, CollapseItem, Tag, Select, Option, Radio} from "element-ui";
import {
  IconX,
} from "@/components/Icons";
import HaiwaiIcons from "@/components/Icons/Icons";
import blog from "../../blog.service";
import tinymce from './tinymce/tinymce.min'
import 'tinymce/themes/silver'
import 'tinymce/icons/default/icons.js'
import './tinymce/plugins/image'
import './tinymce/plugins/media'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/print'
import 'tinymce/plugins/emoticons'
import 'tinymce/plugins/emoticons/js/emojis.js'
import 'tinymce/plugins/fullscreen'
// import './tinymce/plugins/fontsizeselect'

import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/code'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/table'
import 'tinymce/plugins/help'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/paste'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/preview'
import './langs/zh_CN.js'
import './langs/zh_TW.js'
import "tinymce/skins/ui/oxide/skin.css"
import "tinymce/skins/ui/oxide/content.css"
import "tinymce/skins/content/default/content.css"

export default {
  name:"my-editor",
  components: {
    MiniNavbar,
    // CategoryList,
    // CategoryArticleList,
    [Button.name]: Button,
    // DropDown,
    Modal,
    [FormGroupInput.name]: FormGroupInput,
    [Collapse.name]: Collapse,
    [CollapseItem.name]: CollapseItem,
    [Tag.name]:Tag,
    [Select.name]:Select,
    [Option.name]:Option,
    [Radio.name]:Radio,
    // HaiwaiLogoWhite,
    IconX,
    'editor': Editor,
  },
  watch:{
    'curentArticle.postInfo_postID.tags':function(val){
      this.tags = []
      this.curentArticle.postInfo_postID.tags.forEach(item=>{
        this.tags.push(item.name)
      })
      console.log(val)
    },
    'curentArticle.postInfo_postID.title':function(val){
      this.watchModify(val)
    },
    'curentArticle.postInfo_postID.msgbody':function(val){
      this.watchModify(val)
    }
  },
  methods: {
    watchModify(val){
      this.watchCount+=1;
      if(this.curentArticle.visible==1 && this.watchCount>2){
        // 将已发布文章转为草稿
        this.article_to_draft_by_postID();

      }
      if(this.curentArticle.visible!==1 && this.watchCount>2){
        this.autoSave()
      }
    },
    // async fetchData() {
    //   let postid = 0;
    //   if (this.$route.query.postid != undefined) {
    //     postid = this.$route.query.postid;
    //     // this.article = await blog.getArticle(blogid);
    //     this.article = (
    //       await blog.article_view(postid)
    //     ).data.postInfo_postID;
    //     console.log(this.article);
    //     this.setEditorContent(this.article.msgbody)
    //     // $("#summernote").summernote("code", this.article.msgbody);
    //   }
    // },

    //for editor
    toggleEditorDisabled() {
      this.editorDisabled = !this.editorDisabled;
    },
    onEditorReady(editor) {
      console.log("Editor is ready.", { editor });
      //console.log( Array.from( editor.ui.componentFactory.names() ) );
    },
    onEditorFocus(event, editor) {
      console.log("Editor focused.", { event, editor });
    },
    onEditorBlur(event, editor) {
      console.log("Editor blurred.", { event, editor });
    },
    onEditorInput(data, event, editor) {
      console.log("Editor data input.", { event, editor, data });
    },
    onEditorDestroy(editor) {
      console.log("Editor destroyed.", { editor });
    },
    destroyApp() {
      app.$destroy();
    },
    uploadImage(blobInfo, success, failure, progress){
      this.uploadFile("image", blobInfo.base64(), success, failure, progress);
    },

    // addArticle() {
    //   var newarticle = {
    //     articleId: 9089,
    //     title: "新建的文章",
    //     wordCount: 0,
    //     isPublished: false,
    //   };
    //   this.articleList.unshift(newarticle);
    //   this.changeMenu(this.wenjiActiveId, newarticle.articleId);
    // },
    // changeMenu(wid, aid) {
    //   this.wenjiActiveId = wid;
    //   this.articleActiveId = aid;
    // },

    uploadFile(fileType, file, success, failure, progress){
      if(fileType == 'media'){
        blog.uploadMedia(file).then(rs=>{
          success(rs.data);
        }).catch(error=>{
          
        })
      }
      else {
        blog.uploadImage(file).then(rs=>{
          success(rs.data);
        }).catch(error=>{

        })
      }
    },
    addCategory(){
      this.$refs['categoryForm'].validate((valid) => {
        if (valid) {
          this.btnDisable = true;
          blog.category_add(this.categoryForm.name).then(res=>{
            if(res.status){
              blog.category_list(this.user.userinfo.bloggerID).then(res=>{
                this.wenjiList = res.status?res.data:[];
                this.curentArticle.categoryID = this.wenjiList[0].id
                this.dialogFormVisible = false
              })
            }
          })
        }
      })
    },
    openDialog(item){
        this.categoryForm.name = item?item.name:''
        this.dialogFormVisible = true
        console.log()
    },
    setWJid(id){
      this.wenjiActiveId = id;
      this.articleActiveId = 0;
    },
    setArtid(e){
      this.articleActiveId = e.id;
      this.getContent(e);
      this.watchCount = 0;
      // if(this.timer){
        clearTimeout(this.timer);
      // }
      this.flags.autosaved = false;
    },
    // 获取编辑器内容
    getContent(e){
      //visible=undefined 一定是已发布文章；
       e.visible = e.visible==undefined? 1 : e.visible
      if(e.visible==1){
      //   this.user.article_to_draft_by_postID(e.postID).then(res=>{
      //   console.log(res)
      //   if(res.status){
      //     this.curentArticle = res.data
      //   }
      // })
        this.user.article_view(e.postID).then(res=>{
          this.curentArticle=res.data;
          // this.autoSave();
          console.log(this.curentArticle)
        });
      }else{
        this.user.draft_view(e.id).then(res=>{
          this.curentArticle=res.data;
          console.log(this.curentArticle)
        })
      }
    },
    // 发布一篇新文章（草稿=>文章）
    publish(){
      let data={
          article_data:{
            title:this.curentArticle.postInfo_postID.title,
            msgbody:this.curentArticle.postInfo_postID.msgbody,
            tagname:[],
            typeID:1,
            draftID:this.curentArticle.id
          },
          module_data:{
            add:true,
            bloggerID:this.user.userinfo.bloggerID,
            categoryID:this.wenjiActiveId
          }
        };
        console.log(data);
        this.flags.publish = true;
        this.user.article_add(data).then(res=>{
          console.log(res);
          if(res.status){
            this.published(res);
          }
        })
    },
    // 更新已发布文章 
    article_update(){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:this.tags,
          postID:this.curentArticle.postID,
          typeID:1,
          draftID:this.curentArticle.id,
          is_comment:this.curentArticle.is_comment
        },
        module_data:{
          edit:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.wenjiActiveId
        }
      };
      console.log(this.curentArticle,data)
      this.flags.publish = true;
      this.user.article_update(data).then(res=>{
        console.log(res);
        if(res.status){
          this.published(res);
        }
      })
    },
    // 发布成后续动作
    published(res){
      this.$store.state.user.publidhed = res.data;
      this.$refs.articlelist.getArticleList();
      this.flags.publish = false;
      this.modals.publish = false;
      this.$router.push("/blog/success");
    },
    article_to_draft_by_postID(){
      this.user.article_to_draft_by_postID(this.curentArticle.postID).then(res=>{
        console.log(res)
        if(res.status){
          this.$refs.articlelist.getArticleList();
          this.curentArticle = res.data
        }
      })
    },
    draft_add(){
      let data={
        article_data:{
          title:this.$t('message').editor.title_ph,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:this.tags,
          typeID:1,
          is_comment:1
        },
        module_data:{
          add:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
        }
      };
      this.user.draft_add(data).then(res=>{
        console.log(res)
      })
    },
    draft_update(){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:this.tags,
          typeID:1,
          draftID:this.curentArticle.id
        },
        module_data:{
          edit:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
        }
      };
      console.log(this.curentArticle,data)
      this.flags.autosaving = true;
      this.flags.autosaved = false;
      this.user.draft_update(data).then(res=>{
        console.log(res);
        if(res.status){
          // this.$refs.articlelist.getArticleList();
          this.flags.autosaving = false;
          this.flags.autosaved = true;
        }
      })
    },
    draft_view(id){
      this.user.draft_view(id).then(res=>{
        if(res.status){
          this.curentArticle = res.data;
          console.log(this.curentArticle)
        }
      })
    },
    autoSave(){
      clearTimeout(this.timer);
      if(this.curentArticle.visible!==1){
        this.timer = setTimeout(()=>{
          console.log(this.curentArticle.id);
          this.draft_update();
        },3000)
      }
    },
// tag 相关
    showInput() {
      this.inputVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },

    handleInputConfirm() {
      let inputValue = this.tag;
      if (inputValue) {
        // this.dynamicTags.push(inputValue);
        this.pushtag(inputValue)
      }
      this.inputVisible = false;
      this.inputValue = '';
    },
    removetag(index){
      this.curentArticle.postInfo_postID.tags.splice(index,1);
    },
    pushtag(val){
      let o={name:val}
      this.curentArticle.postInfo_postID.tags.push(o);
      this.tag='';
    },
    beforeDestroy() {
      clearTimeout(this.timer);
    },
    filePicker:function(callback, value, meta) {
    // Provide file and text for the link dialog
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      if(meta.filetype == 'image'){
        input.setAttribute('accept', 'image/*');
      }
      else {
        input.setAttribute('accept', 'audio/*');
      }

      var that = this

      input.onchange = function () {
        var file = this.files[0];
        var fileType = file.name;
        var reader = new FileReader();
        reader.onload = function () {
        // var id = 'blobid' + (new Date()).getTime();
        // var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
          var base64 = reader.result.split(',')[1];
          console.log(reader.result.split(',')[0]);
          that.uploadFile(meta.filetype, reader.result, callback);
        }
        reader.readAsDataURL(file);
      };

      input.click();
    },
    initTabStatus(cats){
      cats.forEach(item=>{
        this.tabStatus[item.id]=0
      })
      console.log(this.tabStatus)
    },
    setTabStatus(cid,aid){
      this.tabStatus[cid]=aid
    }
  },

  beforeCreate() {
    this.$store.state.user.getUserStatus().then(r=>{
      if(r.data.bloggerID){
        blog.category_list(r.data.bloggerID).then(res=>{
          this.wenjiList = res.status?res.data:[];
          this.wenjiActiveId = this.wenjiList.length>0?this.wenjiList[0].id:0;
          console.log(this.wenjiList);
          // this.initTabStatus(this.wenjiList)
       
          if(this.$route.query.postid){
            this.getContent({postID:this.$route.query.id})
          }else if(this.$route.query.draftid){
            this.draft_view(this.$route.query.draftid)
          }else{
            this.curentArticle.categoryID = this.wenjiList[0].id;//草稿默认加到第一目录里
            this.draft_add();
          }
        });
      }else{
        this.$router.push('/blog_register');
      }
    });
  },

  created() {
    // this.fetchData();
  },
  mounted() {
    // this.initEditor();
    var source = new EventSource("/sse.php", { withCredentials: true });
    source.onopen = function (event) {
      console.log(event);
    };
    source.onclose = function (event){
      console.log(event);
    }
    source.onerror = function (event) {
      console.log(event);
    // handle error event
    };
    source.onmessage = function (message){
      console.log(message)
    };
    import('./langs/zh_CN.js');
  },

  data() {
    var checkNameSame = (rule, value, callback) =>{
        this.wenjiList.forEach(item=>{
            if(item.name === value){
                return callback(new Error('与现有目录名重复'))
            }
        })
        callback()
    };
    let lang = localStorage.lang ? (localStorage.lang == "cns" ? 'zh_CN' :'zh_TW') : 'zh_CN';
    return {
      user:this.$store.state.user,
      iconmore3v: HaiwaiIcons.iconmore3v,
      wenjiList:[],
      wenjiActiveId: 0,
      articleActiveId: 0,
      testID:'',
      activeName: "0",
      curentArticle:{postInfo_postID:{title:"",msgbody:""}},
      tabStatus:{},
      watchCount:0,
      tags:[],
      tag:'',
      inputVisible:false,
      modals: {
        addwenji: false,
        publish: false,
        schedule: false,
      },
      msgbody:'asd',
      articleList: [],
      loading: false,
      article: {},
      flags:{
        publish:false,
        autosaving:false,
        autosaved:false,
      },
      dialogFormVisible:false,
      categoryForm:{name:''},
      rules:{
        name:[
          {required: true, message: '请输入目录名称', trigger: 'blur'},
          { validator:checkNameSame, trigger: 'blur' }]
      },
      //TinyMCE
      editorConfig:{
        selector: '#editorText',
        browser_spellcheck: true, // 拼写检查
        branding: false, // 去水印
        elementpath: false,  //禁用编辑器底部的状态栏
        statusbar: false, // 隐藏编辑器底部的状态栏
        paste_data_images: false, // 允许粘贴图像
        menubar: false, 
        image_uploadtab: true,
        images_upload_handler: this.uploadImage,
        // language_url : './langs/zh_CN.js',
        plugins: [
           'advlist autolink lists link image charmap print preview anchor paste',
           'searchreplace visualblocks code fullscreen emoticons',
           'insertdatetime media table paste help wordcount fontsizeselect'
         ],
         toolbar_mode:"wrap",
         toolbar:
           'undo redo  bold italic underline strikethrough  paste pastetext  alignleft aligncenter alignright alignjustify  \
            image link media file emoticons\
           formatselect  backcolor forecolor  bullist numlist outdent indent  removeformat  searchreplace help code',
        language: lang,
        relative_urls : false,
        remove_script_host : true,
        image_dimensions:false,
        file_picker_callback:this.filePicker,
        fontsize_formats: 'x-Large Medium x-Small',
        media_dimensions:false,
        media_live_embeds:true,
        image_class_list : [
          {title: 'wide', value:'wide-img'},
          {title: 'medium', value:'medium-img'},
          {title: 'narrow', value:'narrow-img'},
        ],
        image_description:false,
        // media_poster:false,
        content_style: '.mce-content-body .mce-offscreen-selection {position: absolute;left: -9999999999px;max-width: 1000000px;} ' + ' body{font-size:medium;} .wide-img{width:100%; height:auto;} .narrow-img{width:30%; height:auto;} .medium-img{width:60%; height:auto;}',
        video_template_callback : function(data){
            return "";
        }
      }
    };
  },
};
</script>

<style>
body,
html,
#app,
.wrapper,
.publisher,
.editorbox {
  height: 100%;
}
body{
  margin:0 !important;
}
.editor{
  height:100%
}
.container{
  height: calc(100% - 175px);
}
.tox-tinymce{
  height: calc(100% - 70px) !important;
}
.publisher .header {
  background-color: #39b8eb;
  padding: 5px;
  text-align: center;
}
.publisher .header img {
  height: 36px;
}
.publisher .menu1 {
  background-color: #b8b8b8;
  height: 100%;
}
.publisher .menu2 {
  background-color: #ececec;
}
.publisher ul {
  list-style: none;
  padding-left: 0;
}
.publisher li {
  padding: 14px 16px;
  position: relative;
}
.publisher .wenjiItem {
  cursor: pointer;
  border-left: 6px #b8b8b8 solid;
}
.publisher .wenjiItem.active {
  border-left: 6px #39b8eb solid;
  background-color: #ececec;
}
.publisher .aritcleItem {
  cursor: pointer;
  border-left: 6px #ececec solid;
  color: gray;
}
.publisher .aritcleItem.active {
  border-left: 6px #39b8eb solid;
  background-color: white;
}
.publisher .aritcleItem.ispublished {
  color: black;
  font-weight: 700;
}
.publisher .editicon {
  fill: #32caf9;
  margin-right: 4px;
  height: 20px;
}
.publisher .editbtn {
  font-size: 1rem;
  font-weight: 700;
}

.mce-content-body .mce-offscreen-selection {
      position: absolute;
      left: -9999999999px;
      max-width: 1000000px;
    }

.publisher .editorTitle{
  font-size: 30px;
  padding: 5px 10px;
  /* border: 1px solid #d3d3d3; */
  border:0;
  width:100%;
  border-radius: 5px;
}
.publisher input.editorTitle:focus{
  color: #495057;
  outline: 0;
}


.publisher .dropdown .icon {
  margin-right: 5px;
  width: 20px;
  height: 20px;
}
.dropdown-item .icon svg{
  margin-right: 5px;
  width: 20px;
  height: 20px;
}
.dropdown .dropdown-toggle::after {
  display: none;
}
.haiwaiicon svg {
  width: 20px;
  height: 20px;
  fill: #14171a;
}
.wenjiItem .nav-link {
  padding: 0;
}
.submenu-item:hover > .dropdown-menu {
  display: block;
}
.submenu-item .dropdown-menu {
  display: none;
  margin-top: -45px;
  margin-right: -1px;
}
.submenu-item .dropdown-menu::before {
  top: 0;
}
.el-date-table td.disabled div {
  background-color: rgba(255, 255, 255, 0);
}
.el-date-table td.disabled div span {
  color: #ffffff8f !important;
}

.publisher h1, h2, h3, h4, h5, h6 {
  text-transform: uppercase;
  letter-spacing: 3px;
}

.publisher h1, .h1 {
  font-size: 2rem;
}
.publisher  h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
  margin-bottom: 0.5rem;
  font-weight: 600;
  line-height: 1.2;
  color: #1a1a1a;
}
.publisher .note-btn-group .note-btn{
  background-color: transparent;
  color: #55595c
}
.publisher a {
  color: #1a1a1a
 }       

.note-toolbar, .card-header{
  background-color: lightgray !important;
}
.tox-tinymce{
  height:80%
}
.input-new-tag {
    width: 120px;
    margin-left: 10px;
    vertical-align: bottom;
  }
  .el-dropdown-link {
    cursor: pointer;
    color: #39b8eb;
  }
@media (max-width: 575.98px){
  .publisher .menu1{
    height:auto;
  }
}
</style>
