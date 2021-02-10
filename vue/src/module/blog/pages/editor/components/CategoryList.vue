<template>
    <div>
        <button class="btn btn-link m-3" @click="modals.addwenji = true">
          <span v-html="icon_plus"></span>
          <span style="font-size:1rem;color:#14171A">新建文集</span>
        </button>
        <ul>
          <li
            class="wenjiItem d-flex justify-content-between align-items-center"
            v-for="(item, index) in wenjiList"
            :key="index"
            :class="{ active: wenjiActiveId == item.id }"
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
                    >修改</n-button
                  >
                <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_edit"></span>修改文集名称</a>
              </el-popover>
              <el-popconfirm
                placement="top-end"
                confirm-button-text='删除'
                cancel-button-text='取消'
                :title="'确定删除文集 '+item.name+' 吗？'"
                :hide-icon="true"
                @confirm="category_delete(item)"
              >
                <a class="dropdown-item" href="javascript:void(0)" slot="reference"><span v-html="icon_delete"></span>删除文集</a>
              </el-popconfirm>
            </drop-down>

          </li>
        </ul>

    <!-- Add Wenji Modal -->
    <modal :show.sync="modals.addwenji" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px">
        请输入新文集名称
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
          取消
        </n-button>
        <n-button type="primary" round simple @click="categoryAdd()" :disabled="btnDisable">保存</n-button>
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
        }else{console.log(this.checkName(value))
          if(this.checkName(value)){
            callback(new Error('此文集名已存在，换个其它的吧'));
          }else{
            callback();
          }
        }
      };
        return{
          userID:this.$store.state.user.userinfo.UserID,
          iconmore3v: HaiwaiIcons.iconmore3v,
          icon_plus:HaiwaiIcons.icon_plus,
          icon_edit:HaiwaiIcons.icon_edit,
          icon_delete:HaiwaiIcons.icon_delete,
          wenjiActiveId: 100,
          wenjiList: [],
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
      blog.category_list(this.userID).then(res=>{
        this.wenjiList = res.data;
        this.wenjiActiveId = res.data.length>0?this.wenjiList[0].id:0;
        this.changeMenu(this.wenjiActiveId);
      })
    },
    methods:{
      changeMenu(wid) {
        this.wenjiActiveId = wid;
        this.$emit('setwjid',wid);
        // this.articleActiveId = aid;
      },
      categoryAdd(){
        this.$refs['catForm'].validate((valid) => {
          if (valid) {
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
                this.$refs[`popover-` + item.id].doClose()
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
          if(res.status)this.wenjiList = res.data;
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

</style>