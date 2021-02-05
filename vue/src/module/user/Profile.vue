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
            <div v-if="menuId===1 && authorInfor">
               <h6 class="border-bottom pb-3">账号设置</h6>
               <div class="d-flex" style="border-bottom:#eee 1px solid;padding:1rem 0">
                  <!-- <avatar :data="authorInfor" :imgHeight="100"></avatar> -->
                  <span><div v-if="!authorInfor.avatar" class="rounded-circle avatar" style="text-transform: uppercase;background-color:aliceblue;display: inline-block;height:100px;width:100px;text-align:center;font-size:46px;line-height:100px"><b>{{authorInfor.first_letter}}</b></div></span>
                  <img style="width:100px;height:100px;border-radius:50%" :src="authorInfor.avatar" v-if="authorInfor.avatar">
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="toggleShow">修改我的头像</button></div>
                  <!-- <avatar-upload field="img"
                     @crop-success="cropSuccess"
                     @crop-upload-success="cropUploadSuccess"
                     @crop-upload-fail="cropUploadFail"
                     v-model="show"
                     :width="300"
                     :height="300"
                     url="/upload"
                     :params="params"
                     :headers="headers"
                     img-format="png"></avatar-upload> -->
<br>

                  
               </div>
               <div v-show = "show" v-if="show"> 
                  
                  <input
      ref="input"
      type="file"
      name="image"
      accept="image/*"
      @change="setImage"
    />
                  <VueCropper v-show="imgSrc" ref="cropper"  :src="imgSrc" alt="Source Image" preview=".preview" aspectRatio="1"></VueCropper>
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="saveImage">确认修改</button></div>
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="toggleShow">取消</button></div>
                <div class="preview" />
        <div class="cropped-image">
          <img
            v-if="croppedImage"
            :src="croppedImage"
            alt="Cropped Image"
          />
          <div v-else class="crop-placeholder" />
        </div>
               </div>
               <div>
                  <p class="pt-3"><b>笔名</b></p>
                  <fg-input
                     placeholder="笔名"
                     v-model="authorInfor.username">
                  </fg-input>
                  <p class="pt-3"><b>个人简介</b></p>
                  <fg-input
                     placeholder="写点什么介绍你自己吧"
                     v-model="authorInfor.description"
                     >
                  </fg-input>
                  <p class="pt-3"><b>登录账号</b> : <span>{{authorInfor.email}}</span></p>
                  <hr class="mb-4">
                  <p class="pt-3"><b>修改密码</b></p>
                  <el-form :model="signupForm" :rules="rules" ref="signupForm" label-width="10px">
          
                     <el-form-item label="" prop="password">
                        <el-input type="password" placeholder="密码" v-model="signupForm.password" autocomplete="off"></el-input>
                     </el-form-item>
                     <el-form-item label="" prop="checkPassword">
                        <el-input type="password" placeholder="确认密码" v-model="signupForm.checkPassword" autocomplete="off"></el-input>
                     </el-form-item>
                     
                  </el-form>
                  <!-- <fg-input
                     addon-left-icon="now-ui-icons objects_key-25"
                     placeholder="密码"
                     >
                  </fg-input>
                  <fg-input
                     addon-left-icon="now-ui-icons objects_key-25"
                     placeholder="确认密码"
                     >
                  </fg-input> -->
               </div>
               <button class="btn btn-round btn-primary" :disabled="signupForm.submitDisable" @click="submitForm('signupForm')">修改密码</button>
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
import Croppa from 'vue-croppa';
// import Avatar from '../blog/pages/components/Main/Avatar';
import {
  FormGroupInput,
} from '@/components';
import avatarUpload from 'vue-image-crop-upload';
import EleUploadImage from "vue-ele-upload-image";
import account from './service/account';
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
  name: 'profile',
  components: {
   //   'avatar-upload': avatarUpload,
   //  Avatar,
    MainMenu,
    [FormGroupInput.name]: FormGroupInput,IconAccountSet,IconBlogSet,IconBlackList,IconDelete,
    VueCropper, 
   //  'el-upload':EleUpload
   //  EleUploadImage,
   //  Croppa 
  },
   created(){
      account.get_user_profile().then(res=>{
         if(res.status){
            this.authorInfor = res.data
            console.log(this.authorInfor)
         }else{

         }
      })
   },
  data(){
     var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if (this.signupForm.checkPassword !== '') {
          this.$refs.signupForm.validateField('checkPassword');
        }
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.signupForm.password) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };
    return{
      menuId:1,
      authorInfor : null,
      show: false,
      params: {
         token: '123456798',
         name: 'avatar'
      },
      headers: {
         smail: '*_~'
      },
      
      signupForm:{
        password:'',
        checkPassword:'',
        submitDisable:false
      },
      rules: {
        password: [
          { required: true, validator: validatePass, trigger: 'blur' },
          { min: 6, max: 24, message: '长度在 6 到 24 个字符', trigger: 'blur' },
        ],
        checkPassword: [
          { required: true, validator: validatePass2, trigger: 'blur' }
        ],
      },
      imgDataUrl: '/img/julie.jpg', // the datebase64 url of created image,
      image:false,
      imgSrc:false,
      myCroppa:{}
    }
  },
  methods:{
     toggleShow() {
            this.show = !this.show;
      },
      initForm(){
         this.signupForm={
            password:'',
            checkPassword:'',
         }
      },
      submitForm(formName) {
         this.$refs[formName].validate((valid) => {
         if (valid) {
            this.signupForm.submitDisable = true;
            account.user_password_update(this.signupForm).then(res=>{
               console.log(res);
               if(res.status){
               this.initForm();
               }else{
               this.signErr = res.error;
               this.signupForm.submitDisable = true;
               }
            })
         } else {
            console.log('error submit!!');
            this.signupForm.submitDisable = false;
            return false;
         }
         });
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
      },

      uploadImage(){
         console.log(this.myCroppa);
         this.myCroppa.generateBlob(
        blob => {
           console.log(blob);
          // write code to upload the cropped image file (a file is a blob)
        },
        'image/jpeg',
        0.8
      );
      },
      setImage(e) {
      const file = e.target.files[0];
      if (file.type.indexOf('image/') === -1) {
        alert('Please select an image file');
        return;
      }
      if (typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.imgSrc = event.target.result; 
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert('Sorry, FileReader API not supported');
      }
    },
    saveImage() {
      const userId = this.$route.params.user_id
      this.cropedImage = this.$refs.cropper.getCroppedCanvas().toDataURL()
      account.upload_avatar({avatar:this.cropedImage}).then(rs=>{

         }).catch(err=>{

         });
      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData()
        formData.append('avatar', blob, 'name.jpeg')
         
      //   axios
      //     .post('/api/user/' + userId + '/profile-photo', formData)
      //     .then((response) => {
      //     })
      //     .catch(function (error) {
      //       console.log(error)
      //     })
      }, this.mime_type)
    },


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
