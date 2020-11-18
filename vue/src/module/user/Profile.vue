<template>
   <div class="container-sm">
      <div class="row">
         <div class="col-sm-4 left-top-nav">
            <div class="left-nav-item d-flex justify-content-between">
               <div class="name"  @click="menuId=0" >
                  <icon-blog-set></icon-blog-set>
                  博客设置
               </div>
            </div>
            <div class="left-nav-item d-flex justify-content-between">
               <div class="name" @click="menuId=1">
                  <icon-account-set></icon-account-set>
                  账号设置
               </div>
            </div>
            <div class="left-nav-item d-flex justify-content-between">
               <div class="name" @click="menuId=2">
                  <icon-black-list></icon-black-list>
                  黑名单
               </div>
            </div>
         </div>
         <div class="col-sm-8 col-12">
            <div v-if="menuId===0">
               <h6 class="border-bottom pb-3">博客设置</h6>
               <div class="blog-user-index">
                  <div class="user-bg" style="background-image: url(/img/bg11.jpg);">
                     <div class="user-bgup"></div>
                  </div>
               </div>
               <div class="float-right align-items-center ml-3"><button class="btn btn-simple btn-round  btn-primary">修改博客头像背景</button></div>
               <div>
                  <p class="pt-3"><b>博客名</b></p>
                  <fg-input
                     placeholder="博客名"
                     >
                  </fg-input>
                  <p class="pt-3"><b>博客简介</b></p>
                  <fg-input
                     placeholder="博客简介"
                     >
                  </fg-input>
               </div>
               <button class="btn btn-round btn-primary">保存</button>
            </div>
            <div v-if="menuId===1">
               <h6 class="border-bottom pb-3">账号设置</h6>
               <div class="d-flex" style="border-bottom:#eee 1px solid;padding:1rem 0">
                  <avatar :data="authorInfor" :imgHeight="100"></avatar>
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary">修改我的头像</button></div>
               </div>
               <div>
                  <p class="pt-3"><b>笔名</b></p>
                  <fg-input
                     placeholder="笔名"
                     >
                  </fg-input>
                  <p class="pt-3"><b>个人简介</b></p>
                  <fg-input
                     placeholder="个人简介"
                     >
                  </fg-input>
                  <p class="pt-3"><b>登录账号</b> : <span>fwe998</span></p>
                  <hr class="mb-4">
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
               <h6 class="border-bottom pb-3">黑名单</h6>
               <div class="box my-3">
                  <div class=" blacklist align-self-center col-12 no-gutters">
                     <div class="d-flex align-self-center">
                        <div class="small-name">
                           <a href="" class="text-black">慎始敬终</a>
                        </div>
                        <div class="ml-auto" style="width: 175px;">
                           <a class="m-0  btn btn-link text-default w-100">
                              <div class="d-flex text-gray  justify-content-end align-items-end">
                                 <icon-delete></icon-delete>
                                 从黑名单中移除
                              </div>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>
<script>
import {IconAccountSet,IconBlogSet,IconBlackList,IconDelete} from '@/components/Icons';
import Avatar from '../blog/pages/components/Main/Avatar';
import {
  FormGroupInput,
} from '@/components';

export default {
  name: 'profile',
  components: {
    Avatar,
    [FormGroupInput.name]: FormGroupInput,IconAccountSet,IconBlogSet,IconBlackList,IconDelete
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

.blacklist {
        padding: 12px 18px;
        border-top: 1px solid #ddd;
}


<style>

.collection-box {
        background-color: #f0f8ff;
        border-radius: 16px;
        padding: 18px 0;
}
.left-nav-item {
        padding: 12px 18px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
}
.left-nav-item .name {
  font-size: 1rem;
  font-weight: 600
}
.left-nav-item:hover{color: #39b8eb;
        fill: #39b8eb
}

.userset-t {
        padding: 10px 0 0;
}
.blacklist {
        padding: 12px 0;
        border-bottom: 1px solid #ddd;
}
.user-bg {
        background-size: cover;
        background-position-y: center;
}
 .user-bgup {
        height: 100px;
}
</style>
