<template>
    <div>
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
        <ul v-if="articleList.length!=0">
          <li
            v-for="(item, index) in articleList"
            :key="index"
            class="aritcleItem d-flex justify-content-between align-items-center"
            :class="{active: item.id==articleActiveId,ispublished: item.visible==1}"
            @click="changeMenu(item)"
          >
            <div
              class="flex-fill"
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
              <a v-if="!item.is_sticky" class="dropdown-item pl-4" href="javascript:void(0)" @click="articleSticky(item,1)"
                ><icon-top class="icon"></icon-top>置顶文章</a
              >
              <a v-if="item.is_sticky" class="dropdown-item pl-4" href="javascript:void(0)" @click="articleSticky(item,0)"
                ><icon-top class="icon"></icon-top>取消置顶</a
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
                  <a class="dropdown-item" href="javascript:void(0)" v-for="(o,index) in cats.filter(e=>e.id!==wjid)" :key="index" @click="shiftCategory(item.postID,o.id)">{{o.name}}</a>
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

    </div>
</template>
<script>

import { Button, DropDown, Modal, FormGroupInput  } from "@/components";
import HaiwaiIcons from "@/components/Icons/Icons";
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
      cats:Array
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
    },
    data(){
        return{
          userID:this.$store.state.user.userinfo.UserID,
          iconmore3v: HaiwaiIcons.iconmore3v,
          icon_plus:HaiwaiIcons.icon_plus,
          icon_edit:HaiwaiIcons.icon_edit,
          icon_delete:HaiwaiIcons.icon_delete,
          wenjiActiveId: 100,
          wenjiList: [],
          articleActiveId:0,
          articleList:[],
          modals: {
            addwenji: false,
            publish: false,
            schedule: false,
          },
          btnDis:{
            add:false
          }
        }
    },
    methods:{
      changeMenu(e) {
        this.articleActiveId = e.id;
        this.$emit('setarticleid',e)
      },
      addArticle(){
        let data={
          article_data:{
            title:'新建博文标题',
            msgbody:'',
            tagname:[],
            typeID:1
          },
          module_data:{
            add:true,
            bloggerID:this.userID,
            categoryID:this.wjid
          }
        };
        this.btnDis.add = true;
        blog.draft_add(data).then(res=>{
          console.log(res);
          if(res.status){
            this.getArticleList();
          }
        })
      },
      delArticle(item){
        if(item.visible==1 || item.visible==-2){
        console.log(item.visible);
          blog.article_delete(item.postID).then(res=>{
            if(res.status){
              this.getArticleList();
            }
          })
        }
        if(item.visible==-1){
          blog.draft_delete(item.id).then(res=>{
            if(res.status){
              this.getArticleList()
            }
          })
        }
      },
      getArticleList(){
        blog.category_article_list(this.wjid,0).then(res=>{
          console.log(res);
          this.articleList = res.data.filter(obj=>obj.visible!=0);
          // this.articleActiveId = res.data.length>0?this.articleList[0].id:0;
          this.changeMenu(this.articleList[0]);
          this.btnDis.add = false;
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
      test(e){
        console.log(e)
      }
    }
}
</script>
<style>

</style>