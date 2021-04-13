<template>
  <div>
    <div class="d-flex hot-blogger" id="author" :class="{noborder:type=='small'}">
      <div class="" :class="{'hot-blogger-avatar':type=='default','small':type=='small'}">
        <router-link :to="'/blog/user/'+data.bloggerID">
        <icon-V class="text-primary lable" v-if="data.userinfo_userID.is_hot_blogger"></icon-V>
        <!-- <i class="now-ui-icons objects_diamond text-primary lable" v-if="data.isHot"></i>-->
          <div v-if="!data.userinfo_userID.avatar" class="avatar-word">{{data.userinfo_userID.first_letter}}</div>
          <img 
            v-if="data.userinfo_userID.avatar" 
            v-bind:alt="data.userinfo_userID.username" 
            class="rounded-circle" 
            v-bind:src="data.userinfo_userID.avatar" 
            >
        </router-link>
      </div>
      <div class='align-self-center right pl-2 flex-grow-1'> 
          <div class="d-flex align-self-center justify-content-between">
              <div :class="{'blogger-name':type=='default','small-name':type=='small'}">
                <router-link :to="'/blog/user/'+data.bloggerID">{{data.userinfo_userID.username}}
                </router-link>
              </div>
              <div>
                  <a v-if="!curent_data.userinfo_userID.is_following" class="btn btn-link text-primary w-100 btn-follow" @click="following_add(data.userID)">
                      <div class="d-flex justify-content-end align-items-end add">
                          <icon-plus></icon-plus>
                          {{$t('message').blog.blogger_follow}}
                      </div></a>
                  <a v-if="curent_data.userinfo_userID.is_following" class="btn btn-link text-default w-100 cancel-follow" @click="following_delete(data.userID)">
                      <span class="cancel-text text-danger">
                          <div class="d-flex justify-content-end align-items-end">
                            <icon-x :style="{fill:'#FF3636'}"></icon-x>
                            {{$t('message').blog.blogger_remove_follow}}
                          </div>
                      </span>
                      <span class="followed-text text-default">
                          <div class="d-flex justify-content-end align-items-end" style="fill:#999">
                              <icon-check></icon-check>
                              {{$t('message').blog.blogger_followed}}
                          </div>
                      </span>
                  </a>
              </div>
          </div>
          <div id="description" class="description" :title="data.description">
            {{data.description}}
          </div> 
      </div>
    </div>
    <login-dialog ref="dialog"></login-dialog>
  </div>
</template>
<script>
import {
    IconPlus,
    IconCheck,
    IconX,
    IconV
} from '@/components/Icons';
import {
  Button,
} from '@/components';
import account from '../../../../user/service/account';
import LoginDialog from '../../../../user/login/LoginDialog';

export default {
  name: 'bloger-list-item',
  props: {
    type: {
      type: String,
      default: 'default',
      description: 'Alert type'
    },
    data:{
      userID:Number,
      count_follower:Number,
      count_buzz:Number,
      count_article:Number,
      count_comment:Number,
      count_read:Number,
      description:String,
      userinfo_userID:{
        id:Number,
        username:String,
        avatar:String,
        description:String,
        verified:Number,
        status:Number,
        first_letter:String,
        is_hot_blogger:Number,
        is_following:Number
      }
    }
  },
  components: {
    [Button.name]: Button,
    IconPlus,
    IconCheck,
    IconX,
    IconV,
    LoginDialog
  },
  mounted: function () {
    let userinfor = this.$store.state.user.userinfo
    this.loginuserID = userinfor?userinfor.UserID:-1;
  },
  methods:{
    following_add(id){
      if(this.loginuserID!=-1){
        account.following_add(id).then(res=>{
          if(res.status) {
            this.curent_data.userinfo_userID.is_following = 1;
          }else{
            this.$message.error(res.error);
          }
        });
      }else{
        // this.$emit('opendialog')
        this.$refs.dialog.isLogin()
      }
    },
    following_delete(id){
      if(this.loginuserID!=-1){
        account.following_delete(id).then(res=>{
          if(res.data == true) {
            this.curent_data.userinfo_userID.is_following = 0;
          }else{
            this.$message.error(res.error);
          }
        });
      }
    },
  },
  data(){
    return {
      loginuserID:-1,
      error:'',
      curent_data:this.data
    }
  }
}

</script>
<style>

.hot-blogger{
  padding: 12px 18px;
  border-top:1px solid #ebeef0;
}
.hot-blogger.noborder{
  border-top:0;
}
.hot-blogger a{
  color:#14171a;
  text-decoration: none;
}
.hot-blogger a:hover{
color: #235592  
}
.hot-blogger .description a{
  color:#657786
}
.hot-blogger-avatar .avatar-word, .small .avatar-word{
    border-radius: 50%;
    background-color: #eeeeee;
    
    text-align: center;
    font-weight: 500;
}
.hot-blogger-avatar .lable{
  position: absolute;
  margin-left: 30px;
} 
.small .lable{
  position: absolute;
  margin-left: 25px;
}
.hot-blogger-avatar .avatar-word{
    width:48px;
    height:48px;
    line-height: 48px;
    font-size: 1.25rem;
}
.hot-blogger-avatar{
  width:48px;
}
.small{
  width:36px;
}
.hot-blogger-avatar img{
  min-width:48px;
  height:48px;
}
.small img{
    width:36px;
    height:36px;
}
.small .avatar-word{
    width:38px;
    height:38px;
    line-height: 38px;
    font-size: 1rem;
}
.blogger-name{
  font-size: 1rem;
  font-weight: 600;
}
.small-name{
    font-size: .95rem;
  font-weight: 600;

}
.cancel-follow span.cancel-text, .cancel-follow:hover span.followed-text{
    display: none;
}
.cancel-follow span.followed-text, .cancel-follow:hover span.cancel-text{
    display: block;
}
.cancel-follow, .btn-follow{
    padding: 0;
}

.hot-blogger .right a{
  padding:0;
}
.hot-blogger .right .btn{
    margin:0;
}
.hot-blogger .right .btn .add{
        fill: #35abbb;
        font-size: 15px;
        border: 1px solid #35abbb;
        border-radius: 20px;
        padding: 5px 15px;
}
.hot-blogger .right .btn:hover .add{
    fill:#236e85;
}
.hot-blogger .right .description{
  font-size: medium;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  margin-top: 3px;
}
@media (max-width: 575.98px){
    .list-itme .list-itme-image{
        margin:auto 0;
        margin-left: .5rem;
    }
    .imgspace{
        width:90px;
        height:90px;
    }
    .hot-blogger{
      padding: 12px 0;
    }
    .hot-blogger .right .description{
        display: none;
    }
    .cancel-follow span.cancel-text.text-danger{
        display: block;
        color:#888 !important;
    }
    .cancel-follow span.followed-text{
        display: none;
    }
}
</style>
