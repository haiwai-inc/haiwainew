<template>
  <div class="container-sm">
    <div class="row">
      <div class="col-sm-4">
        <p @click="menuId=0">博客设置</p>
        <p @click="menuId=1">账号设置</p>
        <p @click="menuId=2">黑名单</p>
      </div>
      <div class="col-sm-8">
        <div v-if="menuId===0">博客设置内容</div>
        <div v-if="menuId===1">
          <div class="d-flex" style="border-bottom:#eee 1px solid;padding:1rem 0">
            <avatar :data="authorInfor" :imgHeight="100"></avatar>
            <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary">修改我的头像</button></div>
          </div>
          <div>
            <p class="pt-3"><b>修改密码</b></p>
            <fg-input
              addon-left-icon="now-ui-icons objects_key-25"
              placeholder="密码"
            >
            </fg-input>
            
            <fg-input
              addon-left-icon="now-ui-icons objects_key-25"
              placeholder="确认密码"
            >
            </fg-input>
            
          </div>
          <button class="btn btn-round btn-primary">保存</button>
        </div>
        <div v-if="menuId===2">
          黑名单设置
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from '../blog/pages/components/Main/Avatar';
import {
  FormGroupInput,
} from '@/components';

export default {
  name: 'profile',
  components: {
    Avatar,
    [FormGroupInput.name]: FormGroupInput,
  },
  data(){
    return{
      menuId:1,
      authorInfor : {
        avatarUrl:'/img/julie.jpg',
        isHot:true,
        authorHomepage:'',
        name:'用户名',
        firstLetter:'用',
        description:'简介简介简介简介',
        isFollowed:true,
      }
    }
  }
};
</script>
<style></style>
