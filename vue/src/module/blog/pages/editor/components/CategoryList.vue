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
              @change="test"
              tag="div"
            >
              <a class="dropdown-item" href="#" @click="modals.addwenji = true"
                ><span v-html="icon_edit"></span>修改文集名称</a
              >
              <a class="dropdown-item" href="#"
                ><span v-html="icon_delete"></span>删除文集</a
              >
            </drop-down>

          </li>
        </ul>

    <!-- Add Wenji Modal -->
    <modal :show.sync="modals.addwenji" headerClasses="justify-content-center">
      <h4 slot="header" class="title title-up" style="padding-top:5px" @click="test()">
        请输入新文集名称
      </h4>
      <el-form :model="catForm" ref="dynamicValidateForm" label-width="0px">
        <el-form-item
          prop="name"
          label=""
          :rules="[
            { required: true, message: '请输入新文集名称', trigger: 'blur' },
          ]"
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
        <n-button type="primary" round simple @click="categoryAdd()">保存</n-button>
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
    mounted() {
      blog.category_list(this.$store.state.user.userinfo.UserID).then(res=>{
        console.log(res);
        this.wenjiList = res.data;
        this.wenjiActiveId = res.data.length>0?this.wenjiList[0].id:0;
      })
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
            name:''
          }
        }
    },
    methods:{
      changeMenu(wid) {
        this.wenjiActiveId = wid;
        // this.articleActiveId = aid;
      },
      categoryAdd(){
        blog.category_add(this.catForm.name).then(res=>{
          // console.log(res);
          if(res.status)this.getCategories(this.userID);
        })
      },
      getCategories(id){
        blog.category_list(id).then(res=>{
          console.log(res);
          if(res.status)this.wenjiList = res.data;
        })
      },
      test(){
        console.log(this.$store.state.user.userinfo)
      }
    }
}
</script>
<style>

</style>