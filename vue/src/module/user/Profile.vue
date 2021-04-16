<template>
   <div class="container-sm">
    <div class="d-none d-sm-block">
      <main-menu type="-1"></main-menu>
    </div>
      <div class="row">
         <div class="col-sm-4 left-top-nav">
            <div  @click="menuId=0" class="left-nav-item d-flex justify-content-between"  :style="menuId===0?{color:'#39b8eb', fill: '#39b8eb'}:''">
               <div class="name">
                  <icon-blog-set></icon-blog-set>
                  {{$t('message').setting.menu_bolg}}
               </div>
            </div>
            <div @click="menuId=1" class="left-nav-item d-flex justify-content-between"
            :style="menuId===1?{color:'#39b8eb', fill: '#39b8eb'}:''">
               <div class="name">
                  <icon-account-set></icon-account-set>
                  {{$t('message').setting.menu_accout}}
               </div>
            </div>
            <div @click="menuId=2" class="left-nav-item d-flex justify-content-between"
            :style="menuId===2?{color:'#39b8eb', fill: '#39b8eb'}:''">
               <div class="name">
                  <icon-black-list></icon-black-list>
                  {{$t('message').setting.menu_blacklist}}
               </div>
            </div>
         </div>
         <div class="col-sm-8 col-12">
            <div v-if="menuId===0 && $store.state.user.bloggerID==0">
               <regist-blog></regist-blog>
            </div>
            <div v-if="menuId===0 && $store.state.user.bloggerID!=0">
               <h6 class="border-bottom pb-3">{{$t('message').setting.blog_title}}</h6>
               <div class="blog-user-index">
                  <div class="user-bg" :style="'background-image: url('+blogProfile.background+');'">
                     <div class="user-bgup"></div>
                  </div>
               </div>
               <input
                     ref="bgInput"
                     type="file"
                     name="image"
                     accept="image/*"
                     @change="setBackground"
                     style="display:none"
                  />
               <div v-show = "show" v-if="show"> 
                  <VueCropper v-if="imgSrc" ref="cropperbg"  :src="imgSrc" alt="Source Image" preview=".preview" :aspectRatio="4.5"></VueCropper>
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="saveBackground">确认修改</button></div>
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" @click="toggleShow">取消</button></div>
                <div class="preview" />
                </div>
               <div class="float-right align-items-center ml-3"><button class="btn btn-simple btn-round  btn-primary" v-on:click="clickInput('bgInput')">{{$t('message').setting.blog_bgimg_btn}}</button></div>
               <div>
                  <p class="pt-3"><b>{{$t('message').setting.blog_name}}</b></p>
                  <fg-input
                     :placeholder="$t('message').setting.blog_name_ph"
                     v-model="blogProfile.name"
                     >
                  </fg-input>
                  <span class="text-danger" v-if="err.bloggername.flag">{{err.bloggername.msg}}</span>
                  <p class="pt-3"><b>{{$t('message').setting.blog_descript}}</b></p>
                  <fg-input
                     :placeholder="$t('message').setting.blog_descript_ph"
                     v-model="blogProfile.description"
                     >
                  </fg-input>
               </div>
               <span v-if="flag.blog" class="text-success">{{$t('message').setting.blog_saved}}</span><br>
               <button class="btn btn-round btn-primary" @click="saveBlogProfile">{{$t('message').setting.blog_save_btn}}</button>
            </div>
            <div v-if="menuId===1 && authorInfor">
               <h6 class="border-bottom pb-3">{{$t('message').setting.accout_title}}</h6>
               <div class="d-flex" style="border-bottom:#eee 1px solid;padding:1rem 0">
                  <!-- <avatar :data="authorInfor" :imgHeight="100"></avatar> -->
                  <span><div v-if="!authorInfor.avatar" class="rounded-circle avatar" style="text-transform: uppercase;background-color:aliceblue;display: inline-block;height:150px;width:150px;text-align:center;font-size:46px;line-height:150px"><b>{{authorInfor.first_letter}}</b></div></span>
                  <img style="width:150px;height:150px;border-radius:50%" :src="authorInfor.avatar" v-if="authorInfor.avatar">
                  <input
                     ref="picInput"
                     type="file"
                     name="image"
                     accept="image/*"
                     @change="setImage"
                     style="display:none"
                  />
                  <div class="d-flex align-items-center ml-3"><button class="btn btn-simple btn-round btn-primary" v-on:click="clickInput()">
                     {{$t('message').setting.accout_avatar_btn}}
                  </button></div>
<br>
               </div>
               <div v-show = "show" v-if="show"> 
                  <VueCropper v-if="imgSrc" ref="cropper"  :src="imgSrc" alt="Source Image" preview=".preview" :aspectRatio="1"></VueCropper>
                  <div class="d-flex align-items-center">
                     <button class="btn btn-simple btn-round btn-primary" @click="saveImage">{{$t('message').setting.accout_avatar_save_btn}}</button>
                     <button class="btn btn-simple btn-round btn-default ml-3" @click="toggleShow">{{$t('message').setting.accout_cancel_btn}}</button>
                  </div>
               <div class="preview" />
        <!-- <div class="cropped-image">
          <img
            v-if="croppedImage"
            :src="croppedImage"
            alt="Cropped Image"
          />
          <div v-else class="crop-placeholder" />
        </div> -->
               </div>
               <div>
                  <p class="pt-3"><b>{{$t('message').setting.accout_name}}</b>
                  <span v-if="err.username.flag" class="ml-4 text-danger">{{err.username.msg}}</span></p>
                  <fg-input
                     :placeholder="$t('message').setting.accout_name_ph"
                     v-model="authorInfor.username">
                  </fg-input>
                  <p class="pt-3"><b>{{$t('message').setting.accout_descript}}</b></p>
                  <fg-input
                     :placeholder="$t('message').setting.accout_descript_ph"
                     v-model="authorInfor.description"
                     >
                  </fg-input>
                  <button class="btn btn-round btn-primary" @click="user_profile_update">{{$t('message').setting.blog_save_btn}}</button>
                  <p class="pt-3"><b>{{$t('message').setting.accout_accout}}</b> : <span>{{authorInfor.email}}</span></p>
                  <hr class="mb-4">
                  <p class="pt-3"><b>{{$t('message').setting.accout_pass}}</b></p>
                  <el-form :model="signupForm" :rules="rules" ref="signupForm" label-width="10px">
          
                     <el-form-item label="" prop="password">
                        <el-input type="password" :placeholder="$t('message').setting.accout_pass_ph" v-model="signupForm.password" autocomplete="off"></el-input>
                     </el-form-item>
                     <el-form-item label="" prop="checkPassword">
                        <el-input type="password" :placeholder="$t('message').setting.accout_comfirm_ph" v-model="signupForm.checkPassword" autocomplete="off"></el-input>
                     </el-form-item>
                     
                  </el-form>
                 
               </div>
               <button class="btn btn-round btn-primary" :disabled="signupForm.submitDisable" @click="submitForm('signupForm')">{{$t('message').setting.accout_pass_btn}}</button>
            </div>
            <div v-if="menuId===2">
               <h6 class="border-bottom pb-3">{{$t('message').setting.black_title}}</h6>
               <div class="box my-3">
                  <div v-for="(item,index) in blackList" :key="index" class=" blacklist align-self-center col-12 no-gutters">
                     <div class="d-flex align-self-center">
                        <div class="small-name">
                           <router-link :to="'blog/user/'+item.blockID" class="text-black">{{item.userinfo_blockID.username}}</router-link>
                        </div>
                        <div class="ml-auto" style="width: 175px;">
                           <a class="m-0  btn btn-link text-default w-100">
                              <div @click="blackListRemove(item)" class="d-flex text-gray  justify-content-end align-items-end">
                                 <icon-delete></icon-delete>
                                 {{$t('message').setting.black_remove}}
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
import RegistBlog from './login/RegistBlog';
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import Editor from '@tinymce/tinymce-vue'

export default {
  name: 'profile',
  components: {
   //   'avatar-upload': avatarUpload,
   //  Avatar,
    MainMenu,
    [FormGroupInput.name]: FormGroupInput,IconAccountSet,IconBlogSet,IconBlackList,IconDelete,
    RegistBlog,
    VueCropper, 
   //  'el-upload':EleUpload
   //  EleUploadImage,
   //  Croppa 
  },
   created(){
      this.user_profile();
      this.getBlackList(0);
      this.getBlogProfile();
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
      user:this.$store.state.user,
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
      blackList:[],
      blogProfile:{},
      err:{
         bloggername:{
            flag:false,
            msg:""
         },
         username:{
            flag:false,
            msg:""
         }
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
      myCroppa:{},
      flag:{
         blog:false
      }
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
         this.toggleShow();
         this.setImg(e);
    },
    setBackground(e) {
         this.toggleShow();
         this.setImg(e, "bgInput");
    },
    setImg(e, name = "picInput"){
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
            if(name == "picInput")
               this.$refs.cropper.replace(event.target.result);
            else 
               this.$refs.cropperbg.replace(event.target.result);
         };
         reader.readAsDataURL(file);
      } else {
        alert('Sorry, FileReader API not supported');
      }
      this.$refs[name].value = "";
    },
    saveImage() {
      const userId = this.$route.params.user_id
      this.cropedImage = this.$refs.cropper.getCroppedCanvas().toDataURL()
      account.upload_avatar({avatar:this.cropedImage}).then(rs=>{
         if(rs.status){
            this.authorInfor.avatar = rs.data;
            this.toggleShow();
         }
         else {

         }
            
         }).catch(err=>{

         });
    },

    saveBackground() {
       const userId = this.$route.params.user_id
      this.cropedImage = this.$refs.cropperbg.getCroppedCanvas().toDataURL()
      account.upload_background({background:this.cropedImage}).then(rs=>{
         if(rs.status){
            this.blogProfile.background = rs.data;
            this.toggleShow();
         }
         else {

         }
            
         }).catch(err=>{

         });
      },
      clickInput(name = "picInput"){
         this.$refs[name].click();
      },
      async user_profile(){
         let val = await this.user.user_profile();
         this.authorInfor = val.status?val.data:null;
         console.log(this.authorInfor)
      },
      async getBlackList(lastID){
         let v = await this.user.blacklist_list(lastID);
         if(v.status){
            this.blackList = v.data;console.log(v.data)
         }
      },
      blackListRemove(item){
         this.user.blacklist_delete(item.blockID).then(res=>{
            console.log(res);
            this.getBlackList(0);
         })
      },
      async getBlogProfile(){
         let v = await this.user.blogger_profile();
         if(v.status){
            this.blogProfile=v.data;
            console.log(v)
         }
      },
      saveBlogProfile(){
         this.user.blogger_profile_update(this.blogProfile.name,this.blogProfile.description).then(res=>{
            console.log(res)
            if(res.status){
               this.getBlogProfile();
               this.err.bloggername.flag = false
               this.flag.blog=true;
               setTimeout(()=>{
                  this.flag.blog=false;
               },3000)
            }else{
               this.err.bloggername.msg = res.error
               this.err.bloggername.flag = true
            }
            this.err.bloggername.msg = res.error
         })
      },
      user_profile_update(){
         if(this.authorInfor.username){
            this.err.username.flag=false;
            this.user.user_profile_update(this.authorInfor.username,this.authorInfor.description).then(res=>{
               console.log(res)
               if(!res.status){
                  this.err.username.flag=true;
                  this.err.username.msg = res.error
               }
            })
         }else{
            this.err.username.flag=true;
            this.err.username.msg = this.$t('message').setting.accout_name_err
         }
         
      }

  }

};
</script>




<style>
.blacklist {
        padding: 12px 18px;
        border-top: 1px solid #ddd;
}
.collection-box {
        background-color: #fbfbfb;
        border-radius: 16px;
        padding: 18px 0;
}
.left-nav-item {
        padding: 12px 18px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        color:#5b7083
}
.left-nav-item .name {
  font-size: 1.2rem;
  font-weight: 600;

}
.left-nav-item:hover{
        color: #39b8eb;
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
