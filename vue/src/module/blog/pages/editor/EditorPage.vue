<template>
  <div class="row no-gutters publisher">
    <div class="col-md-2 menu1">
      <div class="header">
        <router-link class="navbar-brand" to="/">
          <haiwai-logo-white></haiwai-logo-white>
        </router-link>
      </div>
      <button class="btn btn-link m-3" ><icon-plus></icon-plus><span style="font-size:1rem;color:#14171A">新建文集</span></button>
      <ul>
        <li 
        class="wenjiItem d-flex justify-content-between align-items-center" 
        v-for="(item,index) in wenjiList" 
        :key="index"
        :class="{active:wenjiActiveId==item.id}"
        >
          <span 
          class="flex-fill"
          @click="changeMenu(item.id,articleActiveId)">
          {{item.name}} ({{item.count}})</span>

          <drop-down
          class="nav-item dropdown"
          :haiwaiIcon="iconmore3v"
          haiwaiClass="haiwaiicon"
          style="padding:0;"
          >
            <a class="dropdown-item" href="#">修改文件名称</a>
            <a class="dropdown-item" href="#">删除文集</a>
          </drop-down>
        </li>
      </ul>
    </div>
    <div class="col-md-3 menu2">
      <div style="text-align:center;border-bottom:1px #ddd solid;padding:24px 0;">
        <n-button 
        type="primary" 
        round 
        simple 
        @click="addArticle()"
        class="editbtn">
          <icon-plus class="editicon"></icon-plus>新建文章
        </n-button>
      </div>
      <ul>
        <li 
        v-for="(item,index) in articleList" 
        :key="index"
        class="aritcleItem d-flex justify-content-between align-items-center"
        :class="{active:item.articleId==articleActiveId,ispublished:item.isPublished}"
        >
          <div class="flex-fill" @click="changeMenu(wenjiActiveId,item.articleId)">
            {{item.title}}
            <div><small>字数：{{item.wordCount}}</small></div>
          </div>
          <drop-down
          class="nav-item dropdown"
          :haiwaiIcon="iconmore3v"
          haiwaiClass="haiwaiicon"
          style="padding:0;"
          >
            <a class="dropdown-item" href="#">直接发布文章</a>
            <a class="dropdown-item" href="#">定时发布</a>
            <a class="dropdown-item" href="#">置顶文章</a>
            <a class="dropdown-item" href="#">移动文章</a>
            <a class="dropdown-item" href="#">设为私密</a>
            <a class="dropdown-item" href="#">禁止评论</a>
            <a class="dropdown-item" href="#">禁止转载</a>
            <a class="dropdown-item" href="#">删除文章</a>
          </drop-down>
        </li>
      </ul>
    </div>
    <div class="col-md-7 editor">
      <input class="editorTitle" type="text" v-model="article.title" placeholder="新建博文标题">

      <!-- 编辑器 -->
      <ckeditor 
        :editor="editor"
        :config="editorConfig"
        :disabled="editorDisabled"

        tag-name="textarea"
        v-model="article.content"
        
        @ready="onEditorReady"
        @focus="onEditorFocus"
        @blur="onEditorBlur"
        @input="onEditorInput"
        @destroy="onEditorDestroy"></ckeditor>

        <n-button 
        type="primary" 
        round 
        simple 
        @click="save"
        class="editbtn">
          <icon-plus class="editicon"></icon-plus>保存
        </n-button>
    </div>
  </div>
</template>
<script>
import { Button, DropDown } from '@/components';
import {HaiwaiLogoWhite,IconPlus} from '@/components/Icons';
import HaiwaiIcons from '@/components/Icons/Icons'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import blog from '../../blog.service';

export default {
  name: 'profile',
  components: {
    [Button.name]: Button,
    DropDown,
    HaiwaiLogoWhite,
    IconPlus,
  },

  methods:{
    test(){
      console.log('hello edit vue');
      console.log(blog);
      blog.message();
    },
    
    async fetchData() {
      let blogid = 0;
      if (this.$route.params.blogid!=undefined) {
        blogid = this.$route.params.blogid;
        this.article = await blog.getArticle(blogid);
      }
    },

    save(){
      blog.update(1,this.article);
    },

    //for editor
    toggleEditorDisabled() {
      this.editorDisabled = !this.editorDisabled;
    },
    onEditorReady( editor ) {
      console.log( 'Editor is ready.', { editor } );
      //console.log( Array.from( editor.ui.componentFactory.names() ) );
      
    },
    onEditorFocus( event, editor ) {
      console.log( 'Editor focused.', { event, editor } );
    },
    onEditorBlur( event, editor ) {
      console.log( 'Editor blurred.', { event, editor } );
    },
    onEditorInput( data, event, editor ) {
      console.log( 'Editor data input.', { event, editor, data } );
    },
    onEditorDestroy( editor ) {
      console.log( 'Editor destroyed.', { editor } );
    },
    destroyApp() {
      app.$destroy();
    },
    addWenji(){
      var newwenji = {
        id:1000,
        name:'新建的文集',
        count:0
      }
      this.wenjiList.push(newwenji)
      this.changeMenu(newwenji.id,this.articleActiveId)
    },
    addArticle(){
      var newarticle = {
          articleId:9089,
          title:"新填的文章",
          wordCount:0,
          isPublished:false
        }
      this.articleList.unshift(newarticle)
      this.changeMenu(this.wenjiActiveId,newarticle.articleId)
    },
    changeMenu(wid,aid){
      this.wenjiActiveId = wid;
      this.articleActiveId = aid;
    }
  },

  beforeCreate(){
  },

  created () {
    this.fetchData()
  },

  data(){
    return{
      iconmore3v:HaiwaiIcons.iconmore3v,
      wenjiActiveId:100,
      articleActiveId:12345,
      wenjiList:[
        {
          id:100,
          name:'日记本',
          count:12,
        },{
          id:980,
          name:'飞鸟集',
          count:12,
        },{
          id:990,
          name:'飞猪集',
          count:120,
        }
      ],
      articleList:[
        {
          articleId:12345,
          title:"标题1",
          wordCount:100,
          isPublished:true
        },{
          articleId:2345,
          title:"标题2",
          wordCount:200,
          isPublished:true
        },{
          articleId:23456,
          title:"未发布的文章",
          wordCount:200,
          isPublished:false
        }
      ],
      
      loading: false,
      article: {},

      editor: ClassicEditor,
      editorData: '',
      editorConfig: {
          toolbar: [ 
            "selectAll","|","heading","undo","redo","bold","italic","blockQuote",
            "imageUpload","imageStyle:full","imageStyle:side","mediaEmbed","indent","outdent","link","numberedList","bulletedList",
            "ckfinder","imageTextAlternative","insertTable","tableColumn","tableRow","mergeTableCells"
          ],
      },
      editorDisabled: false
    }

  }
  
};


</script>

<style>
body,html,#app,.wrapper,.publisher{
  height:100%
}
.publisher .header{
  background-color: #39B8EB;
  padding:5px;
  text-align:center;
}
.publisher .header img{
  height:36px
}
.publisher .menu1{
  background-color: #b8b8b8;
  height:100%
}
.publisher .menu2{
  background-color: #ececec;
}
.publisher ul {
  list-style: none;
  padding-left: 0;
}
.publisher li {
  padding:14px 16px ;
  position: relative;
}
.publisher .wenjiItem{
  cursor: pointer;
  border-left: 6px #b8b8b8 solid;
}
.publisher .wenjiItem.active{
  border-left: 6px #39b8eb solid;
  background-color: #ececec;
}
.publisher .aritcleItem{
  cursor: pointer;
  border-left: 6px #ececec solid;
  color:gray;
}
.publisher .aritcleItem.active{
  border-left: 6px #39b8eb solid;
  background-color: white;
}
.publisher .aritcleItem.ispublished{
  color:black;
  font-weight: 700;
}
.publisher .editicon{
  fill:#32caf9;
  margin-right:4px;
  height:20px;
}
.publisher .editbtn{
  font-size: 1rem;
  font-weight: 700;
}
.publisher .editorTitle{
  font-size: 32px;
  border: 0;
}
.dropdown .dropdown-toggle::after {
    display:none;
}
.haiwaiicon svg{
  width:20px;
  height:20px;
  fill:#14171A;
}
.wenjiItem .nav-link{
  padding:0;
}
</style>
