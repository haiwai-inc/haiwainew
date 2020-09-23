<template>
  <navbar
    type="white"
    menu-classes="ml-auto"
  >
    <template>
      <router-link class="navbar-brand" to="/">
        海外博客
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
        >
        </fg-input>
      </div>
    </template>
    <template slot="navbar-menu">
      
      <profile-drop-down
              tag="li"
              username="这里是用户名"
              avatarurl="/img/eva.jpg"
              class="nav-item"
      >
        <nav-link to="/profile">
          <i class="now-ui-icons users_single-02"></i> 我的主页
        </nav-link>
        <nav-link to="/">
          <i class="now-ui-icons location_bookmark"></i> 我的收藏
        </nav-link>
        <nav-link to="/">
          <i class="now-ui-icons ui-1_settings-gear-63"></i> 个人设置
        </nav-link>
        <nav-link to="/login">
          <i class="now-ui-icons arrows-1_share-66" style="transform: rotate(-90deg);"></i> 退出登录
        </nav-link>
        
      </profile-drop-down>

      <li class="nav-item">
        <a
          class="nav-link"
          style="margin-top:3px;z-index:1000"
          rel="tooltip"
          title="Notices"
          data-placement="bottom"
          href="/notices"
        >
          <div class="noticealert"></div>
          <i class="now-ui-icons ui-1_bell-53"></i>
          <p class="d-lg-none d-xl-none">消息</p>
        </a>
        
      </li>
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
      
      <li class="nav-item"><div class="divider"></div>
        <n-button type="primary" round simple class="editbtn">
          <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 0 24 24" width="18" class="editicon"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>写博客
        </n-button>
      </li>
    </template>
    <template slot="after-menu">
      
    </template>
  </navbar>
</template>

<script>
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
    
  },
  data(){
    return {
      switches: {
        defaultOn: true,
        defaultOff: false
      }
    };
  }
};
</script>

<style scoped>
.editicon{
  fill:#32caf9;
  margin-right:4px;
  border-bottom: 1px solid #32caf9;
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
