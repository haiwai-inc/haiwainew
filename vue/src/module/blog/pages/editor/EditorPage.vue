<template>
  <div class="row no-gutters publisher">
    <div class="col-md-2 menu1">
      <div class="header">
        <router-link class="navbar-brand" to="/">
          <haiwai-logo-white></haiwai-logo-white>
        </router-link>
      </div>
      <div class="d-sm-none">
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
        <button class="btn btn-link m-3" @click="modals.addwenji = true">
          <icon-plus></icon-plus
          ><span style="font-size:1rem;color:#14171A">新建文集</span>
        </button>
        <ul>
          <li
            class="wenjiItem d-flex justify-content-between align-items-center"
            v-for="(item, index) in wenjiList"
            :key="index"
            :class="{ active: wenjiActiveId == item.id }"
          >
            <span
              class="flex-fill"
              @click="changeMenu(item.id, articleActiveId)"
            >
              {{ item.name }} ({{ item.count }})</span
            >

            <drop-down
              class="nav-item dropdown"
              :haiwaiIcon="iconmore3v"
              haiwaiClass="haiwaiicon"
              style="padding:0;"
            >
              <a class="dropdown-item" href="#" @click="modals.addwenji = true"
                ><icon-edit class="icon"></icon-edit>修改文集名称</a
              >
              <a class="dropdown-item" href="#"
                ><icon-delete class="icon"></icon-delete>删除文集</a
              >
            </drop-down>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-md-3 menu2 d-none d-sm-block">
      <div
        style="text-align:center;border-bottom:1px #ddd solid;padding:24px 0;"
      >
        <n-button
          type="primary"
          round
          simple
          @click="addArticle()"
          class="editbtn"
        >
          <icon-plus class="editicon"></icon-plus>新建文章
        </n-button>
      </div>
      <ul>
        <li
          v-for="(item, index) in articleList"
          :key="index"
          class="aritcleItem d-flex justify-content-between align-items-center"
          :class="{
            active: item.articleId == articleActiveId,
            ispublished: item.isPublished,
          }"
        >
          <div
            class="flex-fill"
            @click="changeMenu(wenjiActiveId, item.articleId)"
          >
            <icon-draft class="icon"></icon-draft>
            {{ item.title }}
            <div>
              <small>字数：{{ item.wordCount }}</small>
            </div>
          </div>
          <drop-down
            class="nav-item dropdown"
            :haiwaiIcon="iconmore3v"
            haiwaiClass="haiwaiicon"
            style="padding:0;"
          >
            <a class="dropdown-item" href="#"
              ><icon-publish class="icon"></icon-publish>直接发布</a
            >
            <a
              class="dropdown-item pl-4"
              href="#"
              @click="modals.schedule = true"
            >
              <icon-schedule class="icon"></icon-schedule>定时发布
            </a>
            <a class="dropdown-item pl-4" href="#"
              ><icon-top class="icon"></icon-top>置顶文章</a
            >
            <div class="submenu-item dropleft">
              <a
                class="dropdown-item dropdown-toggle pl-3"
                href="#"
                id="move"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <icon-folder class="icon"></icon-folder>移动文章
              </a>
              <div class="dropdown-menu" aria-labelledby="move">
                <a class="dropdown-item" href="#">文集1</a>
                <a class="dropdown-item" href="#">文集2</a>
              </div>
            </div>
            <a class="dropdown-item pl-4" href="#"
              ><icon-private class="icon"></icon-private>设为私密</a
            >
            <a class="dropdown-item pl-4" href="#"
              ><icon-forbid class="icon"></icon-forbid>禁止评论</a
            >
            <a class="dropdown-item pl-4" href="#"
              ><icon-forbid class="icon"></icon-forbid>禁止转载</a
            >
            <a class="dropdown-item pl-4" href="#"
              ><icon-delete class="icon"></icon-delete>删除文章</a
            >
          </drop-down>
        </li>
      </ul>
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
      <!-- <ckeditor 
        :editor="editor"
        :config="editorConfig"
        :disabled="editorDisabled"

        tag-name="textarea"
        v-model="article.content"
        
        @ready="onEditorReady"
        @focus="onEditorFocus"
        @blur="onEditorBlur"
        @input="onEditorInput"
        @destroy="onEditorDestroy"></ckeditor> -->
      <div id="summernote"></div>
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
    <modal :show.sync="modals.addwenji" headerClasses="justify-content-center">
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
    </modal>

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
import { Button, DropDown, Modal, FormGroupInput } from "@/components";
import { DatePicker, TimePicker, Collapse, CollapseItem } from "element-ui";
import {
  HaiwaiLogoWhite,
  IconPlus,
  IconDelete,
  IconDraft,
  IconEdit,
  IconForbid,
  IconFolder,
  IconPrivate,
  IconTop,
  IconSchedule,
  IconX,
  IconPublish,
} from "@/components/Icons";
import HaiwaiIcons from "@/components/Icons/Icons";
import "jquery/dist/jquery";
import $ from "jquery";
import jQuery from "jquery";
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap/dist/css/bootstrap-reboot.css';
// import 'bootstrap/dist/css/bootstrap-grid.css';
// import 'summernote/dist/summernote.min.css';
import "summernote/dist/summernote-bs4.css";
// import "bootswatch/dist/flatly/bootstrap.css";
import "summernote/dist/summernote-bs4.js";
import "./emoji/emoji.css";
import "./emoji/config.js";
// import "./emoji/summernote-ext-emoji-ajax.css";
// import "./emoji/summernote-ext-emoji-ajax.js";
import "./audio/summernote-audio.css";
import "./audio/summernote-audio";
// import "./emoji/tam-emoji.min.js";
import "bootstrap";
import lang from "./language";


// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import blog from "../../blog.service";

export default {
  name: "profile",
  components: {
    [Button.name]: Button,
    DropDown,
    Modal,
    [FormGroupInput.name]: FormGroupInput,
    [DatePicker.name]: DatePicker,
    [TimePicker.name]: TimePicker,
    [Collapse.name]: Collapse,
    [CollapseItem.name]: CollapseItem,
    HaiwaiLogoWhite,
    IconPlus,
    IconDelete,
    IconDraft,
    IconEdit,
    IconForbid,
    IconFolder,
    IconPrivate,
    IconPublish,
    IconSchedule,
    IconTop,
    IconX,
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
        ).data.data.postInfo_postID;
        console.log(this.article);
        $("#summernote").summernote("code", this.article.msgbody);
      }
    },

    save() {
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
        title: "新填的文章",
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
  },

  beforeCreate() {},

  created() {
    this.fetchData();
  },
  mounted() {
    this.initEditor();
  },

  data() {
    return {
      iconmore3v: HaiwaiIcons.iconmore3v,
      wenjiActiveId: 100,
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
      wenjiList: [
        {
          id: 100,
          name: "日记本",
          count: 12,
        },
        {
          id: 980,
          name: "飞鸟集",
          count: 12,
        },
        {
          id: 990,
          name: "飞猪集",
          count: 120,
        },
      ],
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

</style>
