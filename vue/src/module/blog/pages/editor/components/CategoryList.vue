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
      blog.category_list(3).then(res=>{
          console.log(res);
          this.wenjiList = res.data.data;
          this.wenjiActiveId = this.wenjiList[0].id
      })
    },
    methods:{
      getCategories(id){
        blog.category_list(id).then(res=>{
          console.log(res);
          this.wenjiList = res.data.data;
        })
      },
    },
    data(){
        return{
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
        }
    },
    methods:{
      changeMenu(wid) {
        this.wenjiActiveId = wid;
        // this.articleActiveId = aid;
      },
      test(e){
        console.log(e)
      }
    }
}
</script>
<style>

</style>