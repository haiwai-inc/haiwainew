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
      <category-article-list :wjid="wenjiActiveId" :cats="wenjiList"></category-article-list>
      
    </div>
    <div class="col-md-7 editor" id="editor_container" ref="editorContainer">
      <div ref="saving">
        <span style="font-size:13px;padding-left:8px;"
          >自动保存中... 已保存</span
        >
      </div>
      <div class="d-flex justify-content-between py-2" ref="titleBox">
        <input
          class="editorTitle"
          type="text"
          v-model="article.title"
          placeholder="新建博文标题"
        />
      </div>

      <!-- 编辑器 -->
      
      <!-- <div id="summernote"></div> -->
      <editor
       api-key="kslxtlgbsr246by5yerx9t5glaje0cgp5hwaqf2aphdo3aaw"
       :init="editorConfig"
     />
     
      <div ref="saveBox">
        <n-button
          v-if="true"
          type="primary"
          round
          simple
          @click.native="modals.publish = true"
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

    <!-- Add Wenji Modal -->
    <!-- <modal :show.sync="modals.addwenji" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        请编辑新文集名称
      </h4>
      <p>
        <fg-input placeholder="文集名"></fg-input>
      </p>
      <template slot="footer">
        <n-button
          class="mr-3"
          type="default"
          link
          @click.native="modals.addwenji = false"
        >
          取消
        </n-button>
        <n-button type="primary" round simple>保存</n-button>
      </template>
    </modal> -->

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
        <n-button type="primary" round simple>
          发布
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
import "jquery/dist/jquery"; 
import $ from "jquery";

import "./emoji/emoji.css";
import "./emoji/config.js";

import lang from "./language";

import blog from "../../blog.service";

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

  methods: {
    test() {
      console.log("hello edit vue");
      console.log(blog);
      blog.message();
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

    setEditorContent(content){
      $("#summernote").summernote("code", content);
    },
    getEditorContent(){
      return $("#summernote").summernote("code");
    },

    save() {
      console.log(this.getEditorContent())
      this.article.msgbody = $("#summernote").summernote("code");
      blog.update(1, this.article);
    },

    uploadImage(files) {
      console.log("HHAHHA")
      let data = new FormData();
      data.append("file", files);
      blog.uploadImage(data).then(
        (rs) => {
          console.log(rs);
          if (rs["error"]) {
            alert("上传图片失败");
          } else {
            console.log(rs["data"]["data"]);
            $("#summernote").summernote(
              "insertImage",
              "http://japan.people.com.cn/NMediaFile/2018/0921/MAIN201809211240000389013678253.jpg",
              "filename"
            );
          }

          // editor.insertImage($editable, url);
        },
        (err) => {}
      );
      // $.ajax({
      //   data: data,
      //   type: "POST",
      //   url: "Your URL POST (php)",
      //   cache: false,
      //   contentType: false,
      //   processData: false,
      //   success: function(url) {
      //     editor.insertImage(welEditable, url);
      //   }});
    },

    initEditor() {
      // await import ("summernote/lang/summernote-zh-CN.js")
      this.editor_language();
      let height = this.$refs.editorContainer.clientHeight;
      // console.log(height);
      // height -=
      //   this.$refs.saving.clientHeight +
      //   this.$refs.titleBox.clientHeight +
      //   this.$refs.saveBox.clientHeight;
      // console.log(this.uploadImage)
      $("#summernote").summernote({
        lang: "zh-CN",
        height: height*0.6,
        toolbar: [
          ["font", ["bold", 'italic', "underline", "strikethrough"]],
          ["style", ["style"]],
          ["para", ["paragraph"]],
          ["insert", ["link", "picture", "video", "audio", "emoji"]],
          ["font",["fontsize"]],
          ["color", ["forecolor", "backcolor"]],
          ["table", ["table"]],
          ["para", ["ul", "ol"]],
          ["font", ["clear"]],
          ["view", ["fullscreen", "codeview", "help"]],
        ],
        callbacks: {
          onImageUpload: this.uploadImage,
          onAudioUpload: this.uploadAudio
        },
      });
    },

    uploadAudio(files) {
      // console.log(files)
      $("#summernote").summernote(
        "audio.insertAudio",
        "https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4",
        "filename"
      );
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
    addWenji() {
      var newwenji = {
        id: 1000,
        name: "新建的文集",
        count: 0,
      };
      this.wenjiList.push(newwenji);
      this.changeMenu(newwenji.id, this.articleActiveId);
    },
    addArticle() {
      var newarticle = {
        articleId: 9089,
        title: "新建的文章",
        wordCount: 0,
        isPublished: false,
      };
      this.articleList.unshift(newarticle);
      this.changeMenu(this.wenjiActiveId, newarticle.articleId);
    },
    changeMenu(wid, aid) {
      this.wenjiActiveId = wid;
      this.articleActiveId = aid;
    },
    editor_language() {
      $.extend($.summernote.lang, lang);
    },
    uploadImage2(blobInfo, success, failure, progress){
      blog.uploadImage(blobInfo.base64()).then(rs=>{
        success(rs.data);
      }
      ).catch(error=>{

      });
    },
    setWJid(e){
      this.wenjiActiveId = e;
      console.log(this.wenjiActiveId)
    }
  },

  beforeCreate() {
    this.$store.state.user.getUserStatus();
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
    blog.category_list(this.userID).then(res=>{
      this.wenjiList = res.data;
      this.wenjiActiveId = this.wenjiList[0].id
      console.log(this.wenjiList,this.wenjiActiveId)
    })
  },

  data() {
    return {
      userID:this.$store.state.user.userinfo.UserID,
      iconmore3v: HaiwaiIcons.iconmore3v,
      wenjiList:[],
      wenjiActiveId: 0,
      articleActiveId: 12345,
      activeName: "0",
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
      
      articleList: [
        {
          articleId: 12345,
          title: "标题1",
          wordCount: 100,
          isPublished: true,
        },
        {
          articleId: 2345,
          title: "标题2",
          wordCount: 200,
          isPublished: true,
        },
        {
          articleId: 23456,
          title: "未发布的文章",
          wordCount: 200,
          isPublished: false,
        },
      ],

      loading: false,
      article: {},
      editorConfig:{
         height: 500,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | image media|\
           bullist numlist outdent indent | removeformat | help',
          branding: false,
          language: 'zh_CN',
          image_uploadtab: true,
          images_upload_handler: this.uploadImage2,
          media_live_embeds: false,
          media_alt_source: false,
          media_dimensions: false,
          media_filter_html: false,
          media_poster: false
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
