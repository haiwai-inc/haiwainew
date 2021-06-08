<template>
  <div class="publisher">
    <mini-navbar title="发博文"></mini-navbar>
    <div class="container">
    <div class="row editorbox">
      <div class="col-md-9 editor mx-auto col-12" id="editor_container" ref="editorContainer">
        <div class="d-flex justify-content-between py-2" ref="titleBox">
          <input
            ref="editor_title"
            class="editorTitle"
            type="text"
            autofocus
            v-model="curentArticle.postInfo_postID.title"
            :placeholder="$t('message').editor.title_ph"
            @keyup="watchModify"
          />
        </div>
          <!-- 编辑器 -->
          <!-- api-key="kslxtlgbsr246by5yerx9t5glaje0cgp5hwaqf2aphdo3aaw" -->
          <el-popover
            placement="bottom"
            ref="blog_pic"
            v-model="bubbles.editor_pic"
            width="300"
            popper-class="bubble"
            >
            <p><svg width="24" height="24" fill="white"><path d="M5 15.7l3.3-3.2c.3-.3.7-.3 1 0L12 15l4.1-4c.3-.4.8-.4 1 0l2 1.9V5H5v10.7zM5 18V19h3l2.8-2.9-2-2L5 17.9zm14-3l-2.5-2.4-6.4 6.5H19v-4zM4 3h16c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1H4a1 1 0 01-1-1V4c0-.6.4-1 1-1zm6 8a2 2 0 100-4 2 2 0 000 4z" fill-rule="nonzero"></path></svg> 
            {{user.userinfo.bubble.instruction.editor_pic}} <br>
            <svg width="24" height="17"><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="youtobe" fill="white" fill-rule="nonzero"><g id="Group" transform="translate(0.500000, 0.000000)"><path d="M10.5,0 C7.44140651,0 4.73437535,0.316406512 3.08789163,0.5625 C1.74023442,0.761719535 0.667970233,1.81640651 0.421875349,3.1699214 C0.210937674,4.35351628 0,6.08203256 0,8.25 C0,10.4179688 0.210937674,12.1464837 0.421875349,13.3300786 C0.667970233,14.6835949 1.74023442,15.7324214 3.08789163,15.9375 C4.74023442,16.1835949 7.45898512,16.5 10.5,16.5 C13.5410163,16.5 16.2539065,16.1835949 17.9062507,15.9375 L17.9121098,15.9375 C19.259767,15.7382819 20.3320326,14.6835949 20.578126,13.3300786 C20.7890637,12.1406247 21,10.4121098 21,8.25 C21,6.08203256 20.7890637,4.35351628 20.572267,3.1699214 C20.3320326,1.81640651 19.259767,0.761719535 17.9121098,0.5625 C16.265626,0.316406512 13.5585949,0 10.5,0 L10.5,0 Z M10.5000014,1.5 C13.4531247,1.5 16.0957033,1.80468698 17.689454,2.0449214 C18.3925786,2.15039023 18.9609377,2.70117209 19.0957047,3.43359488 C19.2949228,4.54101628 19.5000014,6.18164093 19.5000014,8.25 C19.5000014,10.3183591 19.2949228,11.9589837 19.1015637,13.060546 C18.9667981,13.7988279 18.3984391,14.3496084 17.6894553,14.4550786 C16.0839865,14.689454 13.4355488,15 10.5000014,15 C7.56445535,15 4.9101586,14.689454 3.31054744,14.4550786 C2.60742279,14.3496098 2.03320465,13.7988293 1.89843907,13.060546 C1.70508,11.9531247 1.5000014,10.3183591 1.5000014,8.25 C1.5000014,6.17578186 1.70508,4.54101628 1.89843907,3.43359488 C2.03320465,2.70117209 2.60156372,2.15039163 3.30468977,2.05078186 L3.31054884,2.05078186 C4.90429953,1.81054605 7.54687674,1.5 10.5000014,1.5 L10.5000014,1.5 Z M7.5,4.5 L7.5,12 L15,8.25 L7.5,4.5 Z" id="Shape"></path></g></g></g></svg>
            {{user.userinfo.bubble.instruction.editor_video}}
            </p>
             <div style="text-align: right; margin: 0">
               2/4
                <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('editor_pic')">知道了</el-button>
              </div>
            <div class="w-100 bubble-pic"
          slot="reference"></div>
          </el-popover>
        <editor 
          :init="editorConfig"
          v-model="curentArticle.postInfo_postID.msgbody"
        />
        
      </div>
      <div class="col-md-3" style="padding-top:20px">
        <div class="mb-3">
          <el-popover
            placement="bottom"
            ref="blog_manage"
            v-model="bubbles.blog_manage"
            width="300"
            popper-class="bubble"
            trigger="manual"
            >
            <p>{{user.userinfo.bubble.instruction.blog_manage}}</p>
             <div style="text-align: right; margin: 0">
               1/4
                <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('blog_manage')">知道了</el-button>
              </div>
            <el-button plain round type="primary" link @click="$router.push('/blog/my/')" slot="reference"><i class="el-icon-notebook-2"></i> 博文管理</el-button>
          </el-popover>
        </div>
        <el-popover
            placement="bottom"
            ref="blog_category"
            v-model="bubbles.blog_category"
            width="300"
            popper-class="bubble"
            trigger="manual"
            >
            <p>{{user.userinfo.bubble.instruction.blog_category}}</p>
             <div style="text-align: right; margin: 0">
               3/4
                <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('blog_category')">知道了</el-button>
              </div>
          <div class="d-flex justify-content-between align-items-center" slot="reference">
            <span>文章所属目录：</span><el-button type="text" href="javascript:void(0)" @click="openDialog(0)">+ 新建目录</el-button>
          </div>
        </el-popover>
            <el-select v-if="categoryList.length>0" v-model="curentArticle.categoryID" placeholder="请选择" @change="changeCategory">
              <el-option
                v-for="item in categoryList"
                :key="item.id"
                :label="item.is_publish?item.name:item.name+' (隐)'"
                :value="item.id">
              </el-option>
            </el-select>
        
        <div class="py-4">
          <div class="mb-2">
            <span>博文标签：</span>
            <!-- <span class="text-muted" style="font-size:0.85rem">（可多选）</span> -->
          </div>
          <el-tag
            class="mr-2 mb-2"
            v-for="(item,index) in curentArticle.postInfo_postID.tags" :key="item.id"
            closable
            effect="plain"
            :disable-transitions="false"
            @close="removetag(index)">
            {{item.name}}
          </el-tag>
          <el-autocomplete
            class="input-new-tag"
            v-if="inputVisible"
            v-model="tag"
            ref="saveTagInput"
            placeholder="请输入标签"
            :fetch-suggestions="tagSuggestion"
            @keyup.enter.native="handleInputConfirm"
            @select="handleSelect"
          >
          <template slot-scope="{ item }">
            <div class="name">{{ item.name }}</div>
          </template>
          <el-button slot="append" round icon="el-icon-plus" :disabled="tag==''" @click="handleInputConfirm"></el-button>
          </el-autocomplete>
          <el-popover
            placement="bottom"
            ref="editor_tag"
            v-model="bubbles.editor_tag"
            width="300"
            popper-class="bubble" v-else
            >
            <p>{{user.userinfo.bubble.instruction.editor_tag}}</p>
             <div style="text-align: right; margin: 0">
               4/4
                <el-button class="ml-3" type="primary" round size="mini" @click="removeBubble('editor_tag')">知道了</el-button>
              </div>
            <el-button slot="reference" round class="button-new-tag"  @click="showInput">+ 添加和内容相关的关键词</el-button>
          </el-popover>
        </div>
        <div class="pb-3">
          <span >当前博文可否评论：</span><br>
          <el-radio v-model="curentArticle.is_comment" :label="1" @change="watchModify">是</el-radio>
          <el-radio v-model="curentArticle.is_comment" :label="0" @change="watchModify">否</el-radio>
        </div>
      </div>
      <div class="col-12">
        <div class="mt-2 text-muted">注：发表博客文章时请不要提供广告信息或不友好信息，本站保留拒绝的权利。</div>
        <div ref="saveBox" class="m-2" v-if="curentArticle.isDraft">
          <el-button v-if="curentArticle.postID==0" type="primary" round simple @click="publish()" :disabled="flags.publish || curentArticle.postInfo_postID.title==''">
            发布文章
          </el-button>
          <el-button v-if="curentArticle.postID!==0" type="primary" round simple @click="article_update()" :disabled="flags.publish || curentArticle.postInfo_postID.title==''">
            发布更新
          </el-button>
          <el-popconfirm v-if="curentArticle.postID!==0"
            placement="top-end"
            confirm-button-text="放弃"
            cancel-button-text='取消'
            :title="curentArticle.visible==-1?'您还没有发布这篇文章，放弃草稿将彻底删除此文章。是否放弃？':'放弃编辑，将退回到您上次发布此文章的版本！是否放弃？'"
            :hide-icon="true"
            @confirm="draft_delete(curentArticle)"
            >
            <el-button class="ml-3" round icon="el-icon-delete" slot="reference">{{'放弃编辑'}}</el-button>
          </el-popconfirm>
          <el-button v-if="curentArticle.postID==0" round @click="draft_update">
              保存草稿
          </el-button>
          <div ref="saving" style="font-size:13px;padding-left:8px;display:inline">
            <span v-if="flags.autosaving" class="text-muted">{{$t('message').editor.autosaving}}</span> 
            <span v-if="flags.autosaved" class="text-success">{{$t('message').editor.autosaved}}</span>
          </div>
          <el-popconfirm v-if="curentArticle.postID==0"
            placement="top-end"
            confirm-button-text="清空"
            cancel-button-text='取消'
            :title="'您要清空草稿的标题和内容吗？'"
            :hide-icon="true"
            @confirm="draft_refresh()"
            >
            <el-button round type="text"  slot="reference">
              清空草稿内容
            </el-button>
          </el-popconfirm>
        </div>
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
        <el-radio v-model="categoryForm.is_publish" :label="1">公开目录</el-radio>
        <el-radio v-model="categoryForm.is_publish" :label="0">隐藏目录</el-radio>
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
import { Collapse, CollapseItem, Tag, Radio, Autocomplete} from "element-ui";
// import {
//   IconX,
// } from "@/components/Icons";
import HaiwaiIcons from "@/components/Icons/Icons";
import blog from "../../blog.service";
import tinymce from './tinymce/tinymce.min'
import 'tinymce/themes/silver'
import './tinymce/icons/default/icons.js'
import './tinymce/plugins/image/plugin.js'
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
    // [Select.name]:Select,
    // [Option.name]:Option,
    [Radio.name]:Radio,
    [Autocomplete.name]: Autocomplete,
    // HaiwaiLogoWhite,
    // IconX,
    'editor': Editor,
  },
  watch:{},
  methods: {
    watchModify(val){
      // this.watchCount+=1;
      if(this.curentArticle.isDraft){
        console.log("草稿",val)
        this.autoSave()
      };
      if(!this.curentArticle.isDraft){
        console.log("非草稿",val)
        if(val.level)
          this.draft_add(val.level.content)
        else 
          this.draft_add(this.curentArticle.postInfo_postID.msgbody)
      };
    },
    //for editor
    editor_change(e){
      this.watchModify(e);
    },
    
    uploadImage(blobInfo, success, failure, progress){
      this.uploadFile("image", blobInfo.base64(), success, failure, progress);
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
    addCategory(){
      this.$refs['categoryForm'].validate((valid) => {
        if (valid) {
          this.btnDisable = true;
          blog.category_add(this.categoryForm.name,this.categoryForm.is_publish).then(r=>{
            if(r.status){
              this.$store.state.user.category_list(this.user.userinfo.bloggerID).then(res=>{
                this.categoryList = res.status?res.data:[];
                this.curentArticle.categoryID = r.data
                this.dialogFormVisible = false
              })
            }
            console.log(r.data);
          })
        }
      })
    },
    changeCategory(val){
      this.setIsPublish(val);
      this.watchModify(val);
    },
    setIsPublish(val){
      this.categoryList.forEach(item=>{
        if(item.id==val){
          this.curentArticle.is_publish = item.is_publish;
        }
      });
    },
    openDialog(item){
        this.categoryForm.name = item?item.name:'';
        this.dialogFormVisible = true;
    },
    
    // 发布一篇新文章（草稿=>文章）
    publish(){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:this.tags,
          typeID:1,
          // draftID:this.curentArticle.id,
          postID:0,
          is_comment:this.curentArticle.is_comment,
          is_publish:this.curentArticle.is_publish
        },
        module_data:{
          add:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
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
          is_comment:this.curentArticle.is_comment,
          is_publish:this.curentArticle.is_publish
        },
        module_data:{
          edit:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
        }
      };
      console.log(this.curentArticle,data)
      this.flags.publish = true;
      this.user.article_update(data).then(res=>{
        if(res.status){
          this.published(res);
        }
      })
    },
    // 发布成后续动作
    published(res){
      // this.$store.state.user.publidhed = res.data;
      // this.$refs.articlelist.getArticleList();
      this.flags.publish = false;
      // this.modals.publish = false;
      this.$router.push("/blog/p/"+res.data);
    },
    draft_add(e){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          // msgbody:this.curentArticle.postInfo_postID.msgbody,
          msgbody:e,
          tagname:this.tags,
          postID:this.curentArticle.postID,
          typeID:1,
          is_comment:this.curentArticle.is_comment,
          is_publish:this.curentArticle.is_publish
        },
        module_data:{
          add:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
        }
      };
      this.user.draft_add(data).then(res=>{
        this.draft_view(res.data);
      })
    },
    draft_update(){
      let data={
        article_data:{
          title:this.curentArticle.postInfo_postID.title,
          msgbody:this.curentArticle.postInfo_postID.msgbody,
          tagname:this.tags,
          typeID:1,
          // draftID:this.curentArticle.id,
          postID:this.curentArticle.postID,
          is_comment:this.curentArticle.is_comment,
          is_publish:this.curentArticle.is_publish
        },
        module_data:{
          edit:true,
          bloggerID:this.user.userinfo.bloggerID,
          categoryID:this.curentArticle.categoryID
        }
      };
      this.flags.autosaving = true;
      this.flags.autosaved = false;
      this.user.draft_update(data).then(res=>{
        if(res.status){
          this.flags.autosaving = false;
          this.flags.autosaved = true;
        }
      })
    },
    draft_view(postid){
      let id = postid?postid:0
      this.user.draft_view(id).then(res=>{
        if(res.status){
          this.curentArticle = res.data;
          // this.curentArticle.draftID = res.data.id;
          this.curentArticle.categoryID = this.curentArticle.categoryID!=0?this.curentArticle.categoryID:this.categoryList[0].id;//草稿默认加到第一目录里
          this.curentArticle.isDraft = true;
          this.setIsPublish(this.curentArticle.categoryID);//初始化文章is_publish字段
          this.initTag();
        }else{
          this.curentArticle.isDraft = false;
          this.article_view(id);
        }
        this.$refs['editor_title'].focus();
      })
    },
    draft_delete(item){
      this.user.draft_delete(item.postID).then(res=>{
        if(res.status){
          this.$router.push('/blog/my/')
        }
      })
    },
    draft_refresh(){
      this.curentArticle.postInfo_postID.title='';
      this.curentArticle.postInfo_postID.msgbody='';
    },
    async article_view(id){
      let res = await this.user.article_view(id);
      if(res.status){
        this.curentArticle = res.data;
        this.setIsPublish(this.curentArticle.categoryID);//初始化文章is_publish字段
        this.initTag();
      }console.log(this.curentArticle)
    },
    autoSave(){
      clearTimeout(this.timer);
      if(this.curentArticle.visible!==1){
        this.timer = setTimeout(()=>{
          this.draft_update();
        },3000)
      }
    },
// tag 相关
    async tagSuggestion(queryString,cb){
      let results = await this.$store.state.search.get_tags(queryString);
      console.log(results.data)
      cb(results.data);
      // 调用 callback 返回建议列表的数据
    },
    showInput() {
      this.inputVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
    // tag
    initTag(){
      this.tags = []
      this.curentArticle.postInfo_postID.tags.forEach(item=>{
        this.tags.push(item.name)
      });
    },
    handleSelect(item) {
      this.tag = item.name;
      console.log(item);
      this.handleInputConfirm();
    },
    handleInputConfirm() {
      let inputValue = this.tag;
      if (inputValue) {
        this.pushtag(inputValue)
      }
      this.inputVisible = false;
      this.inputValue = '';
    },
    removetag(index){
      this.curentArticle.postInfo_postID.tags.splice(index,1);
      this.initTag();
      this.watchModify();
    },
    pushtag(val){
      let t={name:val}
      this.curentArticle.postInfo_postID.tags.push(t);
      this.tag='';
      this.initTag();
      this.watchModify(val)
    },
    showBubble(){
      var bubbles=['blog_manage','editor_pic','blog_category','editor_tag'];
      for(let i=0;i<bubbles.length;i++){
        let type = bubbles[i]
        if(this.user.userinfo.bubble.user[type]==1){
          this.bubbles[type]=true;
          return
        }else{
          this.bubbles[type]=false;
        }
      };
      console.log(this.bubbles);
      // if(this.user.userinfo.bubble.user.blog_manage){
      //   this.bubbles.blog_manage = true;
      //   return
      // }else{
      //   this.bubbles.blog_manage = false;
      // }
    },
    removeBubble(type){
      this.user.remove_bubble(type).then(res=>{
        if(res.status){
          this.user.userinfo.bubble = res.data;
          this.showBubble();
        }
      })
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
    this.$store.state.user.getUserStatus().then(r=>{
      if(r.data.bloggerID){
        this.$store.state.user.category_list(r.data.bloggerID).then(res=>{
          this.categoryList = res.status?res.data:[];
          this.curentArticle.categoryID = this.categoryList[0].id;
          this.draft_view(this.$route.query.postid);
          }
        );
      }else{// 如果没有开通博客
        this.$router.push('/blog_register');
      }
    });
  },

  created() {
  },
  mounted() {
    document.documentElement.setAttribute("class", "");
    // this.initEditor();
    console.log(this.user)
    this.showBubble();
  },

  beforeDestroy() {
    clearTimeout(this.timer);
  },
  data() {
    var editoronChange=(e)=>{
      this.editor_change(e);console.log(e);
    };
    var checkNameSame = (rule, value, callback) =>{
      this.categoryList.forEach(item=>{
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
      categoryList:[],
      wenjiActiveId: 0,
      articleActiveId: 0,
      activeName: "0",
      curentArticle:{
        isDraft:true,
        postID:0,
        categoryID:0,
        postInfo_postID:{
          title:"",
          msgbody:"",
          tags:[]},
        is_comment:1,
        is_publish:1
      },
      tabStatus:{},
      watchCount:0,
      tags:[],
      tag:'',
      hotTags:[],
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
      bubbles:{
        blog_manage:false,
        blog_category:false,
        editor_pic:false,
        editor_tag:false
      },
      dialogFormVisible:false,
      categoryForm:{name:'',is_publish:1},
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
            image media link file emoticons\
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
          // {title: 'wide', value:'wide-img'},
          // {title: 'medium', value:'medium-img'},
          // {title: 'narrow', value:'narrow-img'},
          {title: 'origin', value:'origin-img'}
        ],
        setup: function(editor){
          editor.on('change', function(e){
            editoronChange(e);
          })
        },
        image_description:false,
        // media_poster:false,
        content_style: '.mce-content-body .mce-offscreen-selection {position: absolute;left: -9999999999px;max-width: 1000000px;} ' + ' body{font-size:medium;} .wide-img{width:100%; height:auto;} .narrow-img{width:30%; height:auto;} .medium-img{width:60%; height:auto;} .origin-img{max-width:100%; height:auto;}',
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
  border: 1px solid #d3d3d3;
  /* border:0; */
  width:100%;
  border-radius: 0px;
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
  /* text-transform: uppercase; */
  letter-spacing: 2px;
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
  width: 200px;
  vertical-align: bottom;
}
.input-new-tag .el-input-group__append {
  background-color: #fff;
  width:36px;
  border-radius: 20px;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.el-dropdown-link {
  cursor: pointer;
  color: #39b8eb;
}
.el-popover.bubble {
  background-color: #14171a;
  color:white
}
.el-popover.bubble .popper__arrow,
.el-popover.bubble .popper__arrow::after{
  border-top-color: #14171a;
  border-bottom-color: #14171a;
}
.bubble-pic{
  position: absolute;
  top:100px;
}
@media (max-width: 575.98px){
  .publisher .menu1{
    height:auto;
  }
  .bubble-pic{
  position: absolute;
  top:130px;
}
}
</style>
