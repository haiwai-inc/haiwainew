<template>
   <div class="container-sm">
    <div class="d-none d-sm-block">
      <main-menu type="-1"></main-menu>
    </div>
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
                  <!-- <avatar :data="authorInfor" :imgHeight="100"></avatar> -->
                  <img style="width:100px;height:100px;border-radius:50%" :src="imgDataUrl">
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="toggleShow">修改我的头像</button></div>
                  <avatar-upload field="img"
                     @crop-success="cropSuccess"
                     @crop-upload-success="cropUploadSuccess"
                     @crop-upload-fail="cropUploadFail"
                     v-model="show"
                     :width="300"
                     :height="300"
                     url="/upload"
                     :params="params"
                     :headers="headers"
                     img-format="png"></avatar-upload>

                     <ele-upload-image
                     crop
    action="/api/v1/"
    v-model="image"
    :responseFn="handleResponse"
  ></ele-upload-image>
  <!-- <el-upload
  class="upload-demo"
  action="https://jsonplaceholder.typicode.com/posts/"
  :on-preview="handlePreview"
  :on-remove="handleRemove"
  :before-remove="beforeRemove"
  multiple
  :limit="3"
  :on-exceed="handleExceed"
  :file-list="fileList">
  <el-button size="small" type="primary">Click to upload</el-button>
  <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
</el-upload> -->
                  
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
import MainMenu from "../blog/pages/components/Main/MainMenu.vue";
import {IconAccountSet,IconBlogSet,IconBlackList,IconDelete} from '@/components/Icons';
// import Avatar from '../blog/pages/components/Main/Avatar';
import {
  FormGroupInput,
} from '@/components';
import avatarUpload from 'vue-image-crop-upload';
import EleUpload from "element-ui";
import EleUploadImage from "vue-ele-upload-image";

export default {
  name: 'profile',
  components: {
     'avatar-upload': avatarUpload,
   //  Avatar,
    MainMenu,
    [FormGroupInput.name]: FormGroupInput,IconAccountSet,IconBlogSet,IconBlackList,IconDelete,
   //  'el-upload':EleUpload
    EleUploadImage
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
      },
      show: false,
      params: {
         token: '123456798',
         name: 'avatar'
      },
      headers: {
         smail: '*_~'
      },
      imgDataUrl: '/img/julie.jpg', // the datebase64 url of created image,
      image:"/img/julie.jpg"
    }
  },
  methods:{
     toggleShow() {
            this.show = !this.show;
      },
      /**
       * crop success
       *
       * [param] imgDataUrl
       * [param] field
       */
      cropSuccess(imgDataUrl, field){
            console.log('-------- crop success --------');
            this.imgDataUrl = imgDataUrl;
      },
      /**
       * upload success
       *
       * [param] jsonData   服务器返回数据，已进行json转码
       * [param] field
       */
      cropUploadSuccess(jsonData, field){
            console.log('-------- upload success --------');
            console.log(jsonData);
            console.log('field: ' + field);
      },
      /**
       * upload fail
       *
       * [param] status    server api return error status, like 500
       * [param] field
       */
      cropUploadFail(status, field){
            console.log('-------- upload fail --------');
            console.log(status);
            console.log('field: ' + field);
      },
      handleResponse(response, file, fileList) {
        // 根据响应结果, 设置 URL
        return "https://xxx.xxx.com/image/" + response.id;
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
