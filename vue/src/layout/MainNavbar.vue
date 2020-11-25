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
        <fg-input style="margin:0;"
          addon-left-icon="now-ui-icons ui-1_zoom-bold"
          placeholder="搜索文章/用户..."
          @keyup.enter.native="submit"
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
        <a class="dropdown-item" href="#">小</a>
        <a class="dropdown-item" href="#">中</a>
        <a class="dropdown-item" href="#">大</a>
        <!-- <div class="divider"></div> -->
      </drop-down>
      <n-switch
        class="switchbtn"
        style=""
        v-model="switches.defaultOff"
        on-text="简"
        off-text="繁"
      ></n-switch>
       <li class="nav-item">
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
      <profile-drop-down
              tag="li"
              username="用户名"
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
        <nav-link to="/login">
          <i class="now-ui-icons arrows-1_share-66" style="transform: rotate(-90deg);"></i> 退出登录
        </nav-link>
        
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
import { ProfileDropDown, DropDown, Navbar, NavLink, Switch, FormGroupInput, Button,  } from '@/components';
import { Popover } from 'element-ui';
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
    IconPen
  },
  data(){
    return {
      switches: {
        defaultOn: true,
        defaultOff: false
      }
    };
  },
  methods:{
    submit(){
      this.$router.push('/search')
    }
  }
};
</script>

<style scoped>
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
