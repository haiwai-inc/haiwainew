<template>
  <div class="row no-gutters publisher">
    <div class="col-md-2 menu1">
      <div class="header">
        <router-link class="navbar-brand" to="/">
          <haiwai-logo-white></haiwai-logo-white>
        </router-link>
      </div>
      <div style="padding:10px 22px"><icon-plus></icon-plus>新建文集</div>
      <ul>
        <li 
        class="wenjiItem" 
        v-for="(item,index) in wenjiList" 
        :key="index"
        :class="{active:wenjiActiveId==item.id}">{{item.name}} ({{item.count}})</li>
      </ul>
    </div>
    <div class="col-md-3 menu2">
      <div style="text-align:center;border-bottom:1px #ddd solid;padding:24px 0;">
        <n-button 
        type="primary" 
        round 
        simple 
        @click="$router.push('/blog/write')"
        class="editbtn">
          <icon-plus class="editicon"></icon-plus>新建文章
        </n-button>
      </div>
      <ul>
        <li 
        v-for="(item,index) in articleList" 
        :key="index"
        class="aritcleItem"
        :class="{active:item.articleId==articleActiveId,ispublished:item.isPublished}">
          {{item.title}}
          <div><small>字数：{{item.wordCount}}</small></div></li>
      </ul>
    </div>
    <div class="col-md-7 editor">
      <input class="editorTitle" type="text" value="这里是标题">
      
      <!-- 编辑器放这里 -->
    </div>
  </div>
</template>
<script>
import { Button } from '@/components'
import {HaiwaiLogoWhite,IconPlus} from '@/components/Icons'
export default {
  name: 'profile',
  components: {
    HaiwaiLogoWhite,
    [Button.name]: Button,
    IconPlus
  },
  data(){
    return{
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
          title:"标题3",
          wordCount:200,
          isPublished:false
        }
      ]
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
  padding:10px 16px ;
  position: relative;
}
.publisher .wenjiItem{
  border-left: 6px #b8b8b8 solid;
}
.publisher .wenjiItem.active{
  border-left: 6px #39b8eb solid;
  background-color: #ececec;
}
.publisher .aritcleItem{
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
</style>
