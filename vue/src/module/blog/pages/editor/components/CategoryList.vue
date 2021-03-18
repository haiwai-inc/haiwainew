<template>
    <div class="category_box">
        <button class="btn btn-link m-3" @click="modals.addwenji = true">
          <span v-html="icon_plus"></span>
          <span style="font-size:1rem;color:#14171A">{{$t('message').editor.wenji_new_btn}}</span>
        </button>
        <ul>
          <li
            class="wenjiItem d-flex justify-content-between align-items-center"
            v-for="(item, index) in wenjiList"
            :key="index"
            :class="{ active: wenjiActiveId==item.id }"
          >
            <span class="flex-fill" @click="changeMenu(item.id)">
              {{ item.name }} ({{ item.count_article }})
            </span>
            <drop-down
              class="nav-item dropdown"
              :haiwaiIcon="iconmore3v"
              haiwaiClass="haiwaiicon"
              style="padding:0;"
              tag="div"
            >
              <el-popover 
              placement="bottom-start"
              width="350" 
              @show="setName(item)"
              :ref="`popover-`+item.id"
              trigger="click">
                <el-form :model="catForm" :rules="WJrules" ref="catupForm" label-width="0px">
                  <el-form-item
                    prop="name"
                    label=""
                  >
                    <el-input v-model="catForm.name"></el-input>
                  </el-form-item>
                </el-form>
                <n-button 
                  type="primary"
                  round 
                  simple
                  :disabled="btnDisable"
                  @click="category_update(item)"
                    >{{$t('message').editor.wenji_update_btn}}</n-button
                  >
                <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_edit" class="icon"></span>{{$t('message').editor.wenji_update_menu}}</a>
              </el-popover>
              <el-popconfirm
                v-if="wenjiList.length>1"
                placement="top-end"
                confirm-button-text="刪除"
                cancel-button-text='取消'
                :title="$t('message').editor.wenji_comfirm_title+item.name+$t('message').editor.wenji_comfirm_title1"
                :hide-icon="true"
                @confirm="category_delete(item)"
              >
                <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_delete" class="icon"></span>{{$t('message').editor.wenji_delet_menu}}</a>
              </el-popconfirm>
            </drop-down>

          </li>
        </ul>

    <!-- Add Wenji Modal -->
    <modal :show.sync="modals.addwenji" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        {{$t('message').editor.wenji_new_title}}
      </h4>
      <el-form :model="catForm" :rules="WJrules" ref="catForm" label-width="0px">
        <el-form-item
          prop="name"
          label=""
        >
          <el-input v-model="catForm.name"></el-input>
        </el-form-item>
      </el-form>
      <p>
        <!-- <fg-input placeholder="文集名" v-model="categories.name"></fg-input> -->
      </p>
      <template slot="footer">
        <n-button
          class="mr-3"
          type="default"
          link
          @click.native="modals.addwenji = false"
        >
          {{$t('message').editor.wenji_new_cancel}}
        </n-button>
        <n-button type="primary" round simple @click="categoryAdd()" :disabled="btnDisable">{{$t('message').editor.wenji_new_save}}</n-button>
      </template>
    </modal>

    </div>
</template>
<script>
// import {Popconfirm,Popover} from 'element-ui';
// import { Dropdown,DropdownMenu,DropdownItem, } from 'element-ui';
import { Button, DropDown, Modal, FormGroupInput  } from "@/components";
import HaiwaiIcons from "@/components/Icons/Icons";
import blog from "../../../blog.service";

export default {
    name: 'category-list',
    props:{
      wl:Array,
      wjid:Number
    },
    watch:{
      wjid:function(v){
        this.changeMenu(this.wjid);
      },
      wl:function(){console.log(this.wl)
        this.wenjiList=this.wl
      }
    },
    components: {
      [Button.name]: Button,
      DropDown,
      Modal,
      [FormGroupInput.name]: FormGroupInput,
      // [Dropdown.name]:Dropdown,
      // [DropdownMenu.name]:DropdownMenu,
      // [DropdownItem.name]:DropdownItem
      // [Popconfirm.name]:Popconfirm,
      // [Popover.name]:Popover
    },
    data(){
      var validateWJName =(rule,value,callback)=>{
        if(value===''){
          callback(new Error('请输入用户名'));
        }else{
          if(this.checkName(value)){
            callback(new Error('此文集名已存在，换个其它的吧'));
          }else{
            callback();
          }
        }
      };
      return{
        userID:this.$store.state.user.userinfo.bloggerID,
        iconmore3v: HaiwaiIcons.iconmore3v,
        icon_plus:HaiwaiIcons.icon_plus,
        icon_edit:HaiwaiIcons.icon_edit,
        icon_delete:HaiwaiIcons.icon_delete,
        wenjiActiveId: this.wjid,
        wenjiList:this.wl,
        modals: {
          addwenji: false,
          publish: false,
          schedule: false,
        },
        categories:{
          name:'',
          list:''
        },
        catForm:{
          name:'',
        },
        WJrules:{
          name:[
            { required: true, validator: validateWJName, trigger: 'blur' },
          ],
        },
        btnDisable:false
      }
    },
    mounted() {
      
    },
    methods:{
      changeMenu(wid) {
        this.wenjiActiveId = wid;
        this.$emit('setwjid',wid);
        // this.articleActiveId = aid;
      },
      categoryAdd(){
        this.$refs['catForm'].validate((valid) => {console.log(valid)
          if (valid) {console.log(valid);
            this.btnDisable = true;
            blog.category_add(this.catForm.name).then(res=>{
              console.log(res);
              if(res.status){
                this.getCategories(this.userID);
              }
            })
          }
        })
      },
      setName(item){
        this.catForm.name = item.name;
        console.log(this.catForm.name)
      },
      category_update(item){
        this.$refs['catForm'].validate((valid) => {
          if (valid) {
            this.btnDisable = true;
            blog.category_update(this.catForm.name,item.id).then(res=>{
              if(res.status){
                this.getCategories(this.userID);
                this.modals.addwenji=false;
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
      getCategories(id){
        blog.category_list(id).then(res=>{
          console.log(res);
          if(res.status){
            this.wenjiList = res.data;
          }
          this.btnDisable = false;
          this.catForm.name = '';
        })
      },
      checkName(value){
        let i=0
        this.wenjiList.forEach(item=>{
          if(item.name==value){
            i++
          }
        });
        return i>0?true:false;
      },
      test(){
        // console.log(this.$store.state.user.userinfo);
        // this.getCategories(this.userID);
      }
    }
}
</script>
<style>
.category_box{
  height:calc(100vh - 55px);
  overflow-y: auto;
  overflow-x: hidden;
}
div.category_box::-webkit-scrollbar{
  width:0
}
</style>