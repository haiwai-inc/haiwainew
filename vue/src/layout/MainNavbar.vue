<template>
  <navbar
    type="white"
    menu-classes="ml-auto"
  >
    <template>
      <router-link class="navbar-brand" to="/">
        <img src="/img/logo.png" alt="海外博客" height="32">
      </router-link>
      <el-popover
        ref="popover1"
        popper-class="popover"
        placement="bottom"
        width="200"
        trigger="hover"
      >
        <div class="popover-body">
          This is a tips or slogan
        </div>
      </el-popover>
    </template>
    
    <template slot="before-menu"> 
      <div style="max-width:500px;padding:0 18px;">
        <!-- <el-autocomplete
          class="inline-input"
          v-model="keyword"
          :fetch-suggestions="querySearch"
          placeholder="请输入内容"
          :trigger-on-focus="false"
          @select="handleSelect"
          @keyup.enter.native="onSubmit"
        ><i slot="prefix" class="el-input__icon el-icon-search"></i>
          
          <template slot-scope="{ item }">
            <div class="name">{{ item.name }}</div>
          </template>
        </el-autocomplete> -->
        <fg-input style="margin:0;"
          addon-left-icon="now-ui-icons ui-1_zoom-bold"
          placeholder="搜索文章/用户..."
          v-model="keyword"
          @keyup.enter.native="onSubmit"
        >
        </fg-input>
      </div>
    </template>
    <template slot="navbar-menu">     
      <drop-down
        class="nav-item dropdown"
        icon="now-ui-icons text_caps-small"
        style="margin-top:3px;"
      >
        <a class="dropdown-item" href="#" @click="setFontSize(0)">小</a>
        <a class="dropdown-item" href="#" @click="setFontSize(1)">中</a>
        <a class="dropdown-item" href="#" @click="setFontSize(2)">大</a>
        <!-- <div class="divider"></div> -->
      </drop-down>
      <n-switch
        class="switchbtn"
        style=""
        v-model="switches.defaultOff"
        on-text="简"
        off-text="繁"
      ></n-switch>
       <li class="nav-item" v-if="$store.state.user.userinfo.id">
        <a
          class="nav-link"
          style="margin-top:3px;z-index:1000"
          rel="tooltip"
          title="消息"
          data-placement="bottom"
          href="/notices">
          <div class="noticealert"></div>
          <i class="now-ui-icons ui-1_bell-53"></i>
          <p class="d-lg-none d-xl-none">消息</p>
        </a> 
      </li>
      <div style="padding-top:10px" v-if="!$store.state.user.userinfo.id"><router-link to="/login">登录/注册</router-link></div>
      <profile-drop-down
        v-if="$store.state.user.userinfo.id"
              tag="li"
              :data="$store.state.user.userinfo.userinfo_id"
              :username="$store.state.user.userinfo.userinfo_id.username"
              avatarurl="/img/eva.jpg"
              class="nav-item">
        <nav-link to="/blog/user/1">
          <i class="now-ui-icons users_single-02"></i> 我的主页
        </nav-link>
        <nav-link to="/bookmark">
          <i class="now-ui-icons location_bookmark"></i> 我的收藏
        </nav-link>
        <hr class="mb-1 mt-1">
        <nav-link to="/notices">
          <i class="now-ui-icons ui-2_chat-round"></i> 我收到的评论
        </nav-link>
        <nav-link to="/notices">
          <i class="now-ui-icons ui-1_email-85"></i> 我的悄悄话
        </nav-link>
         <nav-link to="/notices">
          <i class="now-ui-icons users_single-02"></i> 我的粉丝
        </nav-link>
         <nav-link to="/notices">
          <i class="now-ui-icons ui-2_favourite-28"></i> 我收到喜欢
        </nav-link>
        <hr class="mb-2 mt-1">
        <nav-link to="/profile">
          <i class="now-ui-icons ui-1_settings-gear-63"></i> 个人设置
        </nav-link>
        <hr class="mb-1 mt-1">
        <a href="javascript:void(0)" @click="logout" class="dropdown-item">
          <i class="now-ui-icons arrows-1_share-66" style="transform: rotate(-90deg);"></i> 退出登录
        </a>
        
      </profile-drop-down>
      <div class="divider"></div>
      <li class="nav-item ml-2">
        <n-button 
        type="primary" 
        round 
        simple 
        @click="$router.push('/blog/write')"
        class="editbtn">
          <icon-pen class="editicon"></icon-pen>写博客
        </n-button>
      </li>
    </template>
    <template slot="after-menu">
      
    </template>
  </navbar>
</template>

<script>
import { IconPen } from '@/components/Icons'
import { ProfileDropDown, DropDown, Navbar, NavLink, Switch, Button, FormGroupInput } from '@/components';
import { Popover, } from 'element-ui';
import blog from '../module/blog/blog.service.js';
import account from '../module/user/service/account';

export default {
  name: 'main-navbar',
  props: {
    transparent: Boolean,
    colorOnScroll: Number
  },
  components: {
    ProfileDropDown,
    DropDown,
    Navbar,
    NavLink,
    [Popover.name]: Popover,
    [Switch.name]: Switch,
    [FormGroupInput.name]: FormGroupInput,
    [Button.name]: Button,
    // [Autocomplete.name]: Autocomplete,
    IconPen,
  },
  data(){
    return {
      bodyclass:'',
      switches: {
        defaultOn: true,
        defaultOff: false
      },
      keyword: '',
      search:this.$store.state.search,
    };
  },
  methods:{
    async querySearch(queryString, cb) {
      let results = await this.search.getautocomplete(queryString);
      cb(results.data.data);// 调用 callback 返回建议列表的数据
    },
    
    handleSelect(item) {
      this.keyword = item.name;
      console.log(item);
      this.doSearch(this.keyword,item.id);
    },

    onSubmit(){
      this.doSearch(this.keyword,0);
    },

    async doSearch(k,tag){
      this.$router.push({path:'/search',query:{keyword:k,tag:tag}});
    },

    setFontSize(size){
      console.log(this.bodyclass);
      let cls = this.bodyclass + ' fontsize'+size;
      document.querySelector('body').setAttribute('class',cls);
    },
    logout(){
      account.logout().then(res=>{
        if(res.data.status==true){
          this.$store.state.user.userinfo = {};
        }
        console.log(res)
      })
    }
  },
  created: function () {
    this.bodyclass = document.querySelector('body').classList.value;
  },
  beforeCreate(){
  },
  mounted() {
  }
};
</script>

<style>
.el-input__inner{
  border-radius: 20px;
}
.editicon{
  fill:#32caf9;
  margin-right:4px;
  border-bottom: 1px solid #32caf9;
  height:20px;
}
.switchbtn{
  margin:12px 35px;
}
.noticealert{
  position: absolute;
  margin-left: 10px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #FF3636;
}
.editbtn{
   white-space: nowrap;
}
@media (max-width: 575.98px) { 
  .editbtn{
    color:white !important;
    border-color: white !important;
    margin: 1rem 2rem;
  }
  .editbtn .editicon{
    fill:white;
    border-bottom: 1px solid white;
  }
  .switchbtn{
    margin: 12px 30px;
  }
}

</style>
