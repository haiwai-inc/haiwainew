<template>
  <div class="row no-gutters publisher">
    <div class="col-md-2 menu1">
      <div class="header">
        <router-link class="navbar-brand" to="/">
          <haiwai-logo-white></haiwai-logo-white>
        </router-link>
      </div>
      <div class="d-sm-none"> <!-- for mobile -->
        <el-collapse v-model="activeName" accordion>
          <el-collapse-item title="日记本" name="1">
            <div>
              与现实生活一致：与现实生活的流程、逻辑保持一致，遵循用户习惯的语言和概念；
            </div>
            <div>
              在界面中一致：所有的元素和结构需保持一致，比如：设计样式、图标和文本、元素的位置等。
            </div>
          </el-collapse-item>
          <el-collapse-item title="飞鸟集" name="2">
            <div>
              控制反馈：通过界面样式和交互动效让用户可以清晰的感知自己的操作；
            </div>
            <div>页面反馈：操作后，通过页面元素的变化清晰地展现当前状态。</div>
          </el-collapse-item>
          <el-collapse-item title="飞猪集" name="3">
            <div>简化流程：设计简洁直观的操作流程；</div>
            <div>
              清晰明确：语言表达清晰且表意明确，让用户快速理解进而作出决策；
            </div>
            <div>
              帮助用户识别：界面简单直白，让用户快速识别而非回忆，减少用户记忆负担。
            </div>
          </el-collapse-item>
        </el-collapse>
      </div>
      <div class="d-none d-sm-block">
        <category-list @setwjid="setWJid" :wl="wenjiList" :wjid="wenjiActiveId"></category-list>
      </div>
    </div>
    <div class="col-md-3 menu2 d-none d-sm-block">
      <category-article-list 
      @setarticleid="setArtid" 
      :wjid="wenjiActiveId" 
      :cats="wenjiList"
      ref="articlelist"
      ></category-article-list>
      
    </div>
    <div class="col-md-7 editor" id="editor_container" ref="editorContainer">
      <div ref="saving" style="font-size:13px;padding-left:8px;">
        <span v-if="flags.autosaving">自动保存中...</span> 
        <span v-if="flags.autosaved" class="text-success">已自动保存</span>
      </div>
      <div class="d-flex justify-content-between py-2" ref="titleBox" @click="test()">
        <input
          class="editorTitle"
          type="text"
          v-model="curentArticle.postInfo_postID.title"
          placeholder="新建博文标题"
        />
      </div>

      <!-- 编辑器 -->
      
      <!-- <div id="summernote"></div> -->
      <!-- api-key="kslxtlgbsr246by5yerx9t5glaje0cgp5hwaqf2aphdo3aaw" -->
      <editor
       :init="editorConfig"
       v-model="curentArticle.postInfo_postID.msgbody"
     />
     <!-- <textarea id="editorText"> -->
     <!-- </textarea> -->
      <div ref="saveBox">
        <n-button
          type="primary"
          round
          simple
          @click.native="modals.publish=true"
          class="editbtn"
        >
          发布文章
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


    <!-- Publish Modal -->
    <modal :show.sync="modals.publish" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        发布文章
      </h4>
      <p>
        您可以选择一些适合的标签，能方便分类检索，文章也更容易让其他用户看到。
      </p>
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

    <!-- Schedule Modal -->
    <modal :show.sync="modals.schedule" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        设置定时发布时间
      </h4>

      <div class="datepicker-container d-flex justify-content-center">
        <fg-input>
          <el-date-picker
            type="date"
            popper-class="date-picker date-picker-primary"
            placeholder="选择要发布的日期"
            v-model="pickers.datePicker"
            :picker-options="pickers.expireTimeOption"
          >
          </el-date-picker>
        </fg-input>
        <fg-input class="ml-3">
          <el-time-picker
            v-model="timepicker"
            :picker-options="{
              selectableRange: '00:00:00 - 23:59:59',
            }"
            placeholder="选择发布时间"
          >
          </el-time-picker>
        </fg-input>
      </div>

      <template slot="footer">
        <n-button
          class="mr-3"
          type="default"
          link
          @click.native="modals.schedule = false"
        >
          取消
        </n-button>
        <n-button type="primary" round simple>
          定时发布
        </n-button>
      </template>
    </modal>
  </div>
</template>
<script>
import Editor from '@tinymce/tinymce-vue'
import CategoryList from "./components/CategoryList.vue";
import CategoryArticleList from "./components/CategoryArticleList";

import { Button, Modal, FormGroupInput } from "@/components";
import { DatePicker, TimePicker, Collapse, CollapseItem } from "element-ui";
import {
  HaiwaiLogoWhite,
  IconX,
} from "@/components/Icons";
import HaiwaiIcons from "@/components/Icons/Icons";
import blog from "../../blog.service";
import tinymce from 'tinymce/tinymce'
import 'tinymce/themes/silver'
import 'tinymce/icons/default/icons.js'
import 'tinymce/plugins/image'
import 'tinymce/plugins/media'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/print'
import 'tinymce/plugins/image'
import 'tinymce/plugins/emoticons'
import 'tinymce/plugins/emoticons/js/emojis.js'
import 'tinymce/plugins/fullscreen'


import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/code'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/table'
import 'tinymce/plugins/help'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/paste'

import 'tinymce/plugins/preview'
// import 'tinymce/langs/zh_CN.js'
// import 'tinymce/langs/zh_TW.js'
import "tinymce/skins/ui/oxide/skin.min.css"
import "tinymce/skins/ui/oxide/content.min.css"
import "tinymce/skins/content/default/content.min.css"

export default {
  name:"editor-page",
  components: {
    CategoryList,
    CategoryArticleList,
    [Button.name]: Button,
    // DropDown,
    Modal,
    [FormGroupInput.name]: FormGroupInput,
    [DatePicker.name]: DatePicker,
    [TimePicker.name]: TimePicker,
    [Collapse.name]: Collapse,
    [CollapseItem.name]: CollapseItem,
    HaiwaiLogoWhite,
    IconX,
    'editor': Editor,
  },
  watch:{
    'curentArticle.postInfo_postID.title':function(val){
      this.watchModify(val)
    },
    'curentArticle.postInfo_postID.msgbody':function(val){
      this.watchModify(val)
    }
  },
  methods: {
    test(val) {
      
    },
    watchModify(val){
      this.watchCount+=1;
      if(this.curentArticle.visible==1 && this.watchCount>2){
        // 将已发布文章转为草稿
        this.article_to_draft_by_postID();

        console.log("ok");
      }
      if(this.curentArticle.visible!==1 && this.watchCount>2){
        this.autoSave()
      }
      console.log(this.watchCount);
    },
    async fetchData() {
      let postid = 0;
      if (this.$route.query.postid != undefined) {
        postid = this.$route.query.postid;
        // this.article = await blog.getArticle(blogid);
        this.article = (
          await blog.article_view(postid)
        ).data.postInfo_postID;
        console.log(this.article);
        this.setEditorContent(this.article.msgbody)
        // $("#summernote").summernote("code", this.article.msgbody);
      }
    },

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
    changeMenu(wid, aid) {
      this.wenjiActiveId = wid;
      this.articleActiveId = aid;
    },

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
    
    setWJid(e){
      this.wenjiActiveId = e;
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
    getContent(e){
      if(e.visible==1){
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
          tagname:[],
          postID:this.curentArticle.postID,
          typeID:1,
          draftID:this.curentArticle.id
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
      this.user.article_to_draft_by_postID(this.curentArticle.id).then(res=>{
        console.log(res)
        if(res.status){
          this.$refs.articlelist.getArticleList();
          this.curentArticle = res.data
        }
      })
    },
    draft_update(){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:[],
          typeID:1,
          draftID:this.curentArticle.id
        },
        module_data:{
          edit:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.wenjiActiveId
        }
      };
      console.log(this.curentArticle,data)
      this.flags.publish = true;
      this.flags.autosaving = true;
      this.flags.autosaved = false;
      this.user.draft_update(data).then(res=>{
        console.log(res);
        if(res.status){
          this.$refs.articlelist.getArticleList();
          this.flags.autosaving = false;
          this.flags.autosaved = true;
        }
      })
    },
    autoSave(){
      clearTimeout(this.timer);
      if(this.curentArticle.visible!==1){
        this.timer = setTimeout(()=>{
          console.log(this.curentArticle.id);
          this.draft_update();
        },5000)
      }
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
  },

  beforeCreate() {
    this.$store.state.user.getUserStatus().then(r=>{console.log(r)
      if(r.data.bloggerID){
        blog.category_list(r.data.bloggerID).then(res=>{
          this.wenjiList = res.status?res.data:[];
          this.wenjiActiveId = this.wenjiList.length>0?this.wenjiList[0].id:0;
        });
      }else{
        this.$router.push('/blog_register');
      }
    });
  },

  created() {
    this.fetchData();
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
    return {
      user:this.$store.state.user,
      iconmore3v: HaiwaiIcons.iconmore3v,
      wenjiList:[],
      wenjiActiveId: 0,
      articleActiveId: 12345,
      activeName: "0",
      curentArticle:{postInfo_postID:{title:"",msgbody:""}},
      watchCount:0,
      
      modals: {
        addwenji: false,
        publish: false,
        schedule: false,
      },
      pickers: {
        datePicker: "",
        expireTimeOption: {
          disabledDate(date) {
            //disabledDate 文档上：设置禁用状态，参数为当前日期，要求返回 Boolean
            return date.getTime() < Date.now() - 24 * 60 * 60 * 1000;
          },
        },
      },
      timepicker: new Date(2016, 9, 10, 18, 40),
      msgbody:'asd',
      articleList: [],

      loading: false,
      article: {},
      flags:{
        publish:false,
        autosaving:false,
        autosaved:false,
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
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen emoticons',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | image media file emoticons|\
           bullist numlist outdent indent | removeformat | help',
        language: 'zh_CN',
        file_picker_callback:this.filePicker,
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
.publisher {
  height: 100%;
}
body{
  margin:0;
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
.publisher .editorTitle{
  font-size: 30px;
  padding: 10px;
  border: 0;
  width:100%;
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

@media (max-width: 575.98px){
  .publisher .menu1{
    height:auto;
  }
}
</style>
