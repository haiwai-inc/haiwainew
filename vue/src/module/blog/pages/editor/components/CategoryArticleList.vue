<template>
    <div class="aricle_list_box">
        <div style="text-align:center;border-bottom:1px #ddd solid;padding:24px 0;" >
            <n-button
            type="primary"
            round
            simple
            @click="addArticle()"
            class="editbtn"
            :disabled="btnDis.add"
            >
             <span v-html="icon_plus" style="fill:#39b8eb"></span>
             <span>新建文章</span>
            </n-button>
        </div>
        <div class="aricle_list">
          <ul  v-if="articleList.length!=0">
            <li
              v-for="(item, index) in articleList"
              :key="index"
              class="aritcleItem d-flex justify-content-between align-items-center"
              :class="{active: item.id==articleActiveId,ispublished: item.visible==1}"
            >
              <div
                class="flex-fill"
              @click="changeMenu(item)"
              >
                <icon-draft class="icon" v-if="item.visible!=1"></icon-draft>
                <icon-published class="icon" v-if="item.visible==1"></icon-published>
                {{ item.postInfo_postID.title }}
                <div>
                  <small v-if="item.visible==-2">已发布文章，再编辑中...</small>
                </div>
              </div>
              <drop-down
                class="nav-item dropdown"
                :haiwaiIcon="iconmore3v"
                haiwaiClass="haiwaiicon"
                style="padding:0;"
              >
                <a v-if="item.visible!==1"
                @click="draft_to_article_by_draftID(item.id)" class="dropdown-item" href="#"
                  ><icon-publish class="icon"></icon-publish>直接发布</a
                >
                <a  v-if="item.visible!==1"
                  class="dropdown-item pl-4"
                  href="javascript:void(0)"
                  @click="modals.schedule = true"
                >
                  <icon-schedule class="icon"></icon-schedule>定时发布
                </a>
                <a v-if="!item.is_sticky&&item.visible==1" class="dropdown-item pl-4" href="javascript:void(0)" @click="articleSticky(item,1)"
                  ><icon-top class="icon"></icon-top>置顶文章</a
                >
                <a v-if="item.is_sticky&&item.visible==1" class="dropdown-item pl-4" href="javascript:void(0)" @click="articleSticky(item,0)"
                  ><icon-top class="icon"></icon-top>取消置顶</a
                >
                <div class="submenu-item dropleft" v-if="cats.length>1">
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
                    <a class="dropdown-item" href="javascript:void(0)" v-for="(o,index) in cats.filter(e=>e.id!==wjid)" :key="index" @click="shiftCategory(item.postID,o.id)">{{o.name}}</a>
                  </div>
                </div>
                <a class="dropdown-item pl-4" href="javascript:void(0)" @click="article_publish(item)"
                  ><icon-private class="icon"></icon-private>{{item.is_publish==1?'设为私密':'设为公开'}}</a
                >
                <a class="dropdown-item pl-4" href="javascript:void(0)" @click="article_comment(item)"
                  ><icon-forbid class="icon"></icon-forbid>{{item.is_comment==1?'禁止评论':'允许评论'}}</a
                >
                <a class="dropdown-item pl-4" href="javascript:void(0)" @click="article_share(item)"
                  ><icon-forbid class="icon"></icon-forbid>{{item.is_share==1?'禁止转载':'允许转载'}}</a
                >
                <!-- <a class="dropdown-item pl-4" href="javascript:void(0)" @click="delArticle(item)"
                  ><icon-delete class="icon"></icon-delete>删除文章</a
                > -->
                <el-popconfirm
                  placement="top-end"
                  confirm-button-text='删除'
                  cancel-button-text='取消'
                  :title="'确定删除这篇文章吗？'"
                  :hide-icon="true"
                  @confirm="delArticle(item)"
                >
                  <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_delete" class="icon"></span>删除文章</a>
                </el-popconfirm>
              </drop-down>
            </li>
          </ul>

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

import { Button, DropDown, Modal, FormGroupInput  } from "@/components";
import HaiwaiIcons from "@/components/Icons/Icons";
import { DatePicker, TimePicker, } from "element-ui";
import blog from "../../../blog.service";
import {
  // IconPlus,
  // IconDelete,
  IconDraft,
  // IconEdit,
  IconForbid,
  IconFolder,
  IconPrivate,
  IconTop,
  IconSchedule,
  // IconX,
  IconPublish,
  IconPublished
} from "@/components/Icons";

export default {
    name: 'category-article-list',
    props:{
      wjid:Number,
      cats:Array,
      activeid:Number
    },
    watch:{
      wjid:function(v){
        this.getArticleList();console.log(v)
      }
    },
    components: {
      [Button.name]: Button,
      DropDown,
      Modal,
      [FormGroupInput.name]: FormGroupInput,
      [DatePicker.name]: DatePicker,
    [TimePicker.name]: TimePicker,
      IconDraft,
      // IconEdit,
      IconForbid,
      IconFolder,
      IconPrivate,
      IconPublish,
      IconPublished,
      IconSchedule,
      IconTop
      // [Dropdown.name]:Dropdown,
      // [DropdownMenu.name]:DropdownMenu,
      // [DropdownItem.name]:DropdownItem
      // [Popconfirm.name]:Popconfirm,
      // [Popover.name]:Popover
    },
    mounted() {
      console.log(this.$t('message').topnav.myindex)
    },
    data(){
      return{
        user:this.$store.state.user,
        iconmore3v: HaiwaiIcons.iconmore3v,
        icon_plus:HaiwaiIcons.icon_plus,
        icon_edit:HaiwaiIcons.icon_edit,
        icon_delete:HaiwaiIcons.icon_delete,
        wenjiActiveId: 100,
        wenjiList: [],
        articleActiveId:this.activeid,
        currentAaticle:{},
        articleList:[],
        modals: {
          addwenji: false,
          publish: false,
          schedule: false,
        },
        btnDis:{
          add:false
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
      }
    },
    methods:{
      changeMenu(e) {
        if(e){console.log(e)
          this.articleActiveId = e.visible!=1?e.id:e.postID;
          this.$emit('setarticleid',e)
        }
      },
      // 新建草稿
      addArticle(){
        let data={
          article_data:{
            title:'',
            msgbody:'',
            tagname:[],
            typeID:1
          },
          module_data:{
            add:true,
            bloggerID:this.$store.state.user.userinfo.bloggerID,
            categoryID:this.wjid
          }
        };
        this.btnDis.add = true;
        console.log(data)
        blog.draft_add(data).then(res=>{
          console.log(res);
          if(res.status){
            this.getArticleList('add');
            this.btnDis.add = false;
          }
        })
      },
      delArticle(item){
        if(item.visible==1 || item.visible==-2){
        console.log(item.visible);
          blog.article_delete(item.postID,0).then(res=>{
            if(res.status){
              this.getArticleList("del");
            }
          })
        }
        if(item.visible==-1){
          blog.draft_delete(item.id).then(res=>{
            if(res.status){
              this.getArticleList("del")
            }
          })
        }
      },
      getArticleList(type){
        blog.category_article_list(this.wjid,0).then(res=>{
          // 需要完善翻頁
          console.log(res);
          this.articleList = res.data.filter(obj=>obj.visible!=0);
          this.articleList.forEach(item=>{
            if(item.postInfo_postID.title==""){
              item.postInfo_postID.title = this.$t('message').editor.title_ph
            }
            if(item.postID==this.articleActiveId){
              this.changeMenu(item)
            }
          })
          if(type=="add"||type=="del"){
            this.changeMenu(this.articleList[0]);
          }
          // this.articleActiveId = res.data.length>0?this.articleList[0].id:0;
          // 
        })
      },
      setActiveID(item){
        if(item.visible=1){}
      },
      // 文章置顶、取消置顶
      articleSticky(item,type){
        blog.article_sticky(item.postID,type).then(res=>{console.log(res)
          if(res.status){
            this.getArticleList();
          }
        })
      },
      // 移动文章
      shiftCategory(postID,catID){
        blog.article_shift_category(postID,catID).then(res=>{
          if(res.status){console.log(postID,catID)
            this.getArticleList();
          }
        })
      },
      // 直接发布
      draft_to_article_by_draftID(id){
        this.user.draft_to_article_by_draftID(id).then(res=>{
          if(res.status){
            this.getArticleList();
        console.log(id)
          }
        })
      },
      // 设为隐私/公开
      article_publish(item){
        let isShare = 0
        if(item.is_publish==0){
          isShare = 1
        }
        this.user.article_publish(item.postID,isShare).then(res=>{
          if(res.status){
            this.getArticleList();
          }
        })
      },
      // 禁止/允许评论
      article_comment(item){
        let val = 0;
        if(item.is_comment==0){
          val = 1;
        }
        this.user.article_comment(item.postID,val).then(res=>{
          if(res.status){
            this.getArticleList();
          }
        })
      },
      // 禁止/允许转载
      article_share(item){
        let val = 0;
        if(item.is_share==0){
          val = 1
        }
        this.user.article_share(item.postID,val).then(res=>{
          if(res.status){
            this.getArticleList();
          }
        })
      }
    }
}
</script>
<style>
.aritcleItem .nav-link{
  padding-right:0
}
div.aricle_list{
  overflow-y:auto;
  overflow-x: hidden;
  height:calc(100vh - 100px);
}
div.aricle_list::-webkit-scrollbar{
  width:0
}
.aricle_list_box{
  height:calc(100vh)
}
</style>