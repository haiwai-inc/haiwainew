<template>
  <div class="container-sm">
    <div class="row">
      <div class="col-sm-4 left-top-nav">
        <div class="left-nav-item d-flex justify-content-between">
          <div class="name"  @click="menuId=0" ><icon-blog-set></icon-blog-set>博客设置
          </div>
        </div>
        <div class="left-nav-item d-flex justify-content-between">
          <div class="name" @click="menuId=1"><icon-account-set></icon-account-set>账号设置</div>
        </div>
        <div class="left-nav-item d-flex justify-content-between">
          <div class="name" @click="menuId=2"><icon-blacklist></icon-blacklist>黑名单</div>
        </div>
     </div>
      <div class="col-sm-8 col-12">
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
import {IconAccountSet,IconBlogSet,IconBlacklist} from '@/components/Icons';
import Avatar from '../blog/pages/components/Main/Avatar';
import {
  FormGroupInput,
} from '@/components';

export default {
  name: 'profile',
  components: {
    Avatar,
    [FormGroupInput.name]: FormGroupInput,IconAccountSet,IconBlogSet,IconBlacklist
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

<style>
.left-nav-item{
  padding: 12px 18px;
  border-bottom:1px solid #ddd;
  cursor: pointer;
}
.left-nav-item .name {
  font-size: 1rem;
  font-weight: 600
}
.left-nav-item .descrip{
  font-size:small;
  color: #888888;
  font-weight: 500;
  fill:#888888;
  line-height: 24px;
}
</style>
