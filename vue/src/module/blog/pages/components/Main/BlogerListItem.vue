<template>
    <div class="d-flex hot-blogger" id="author" :class="{noborder:type=='small'}">
      <div class="" :class="{'hot-blogger-avatar':type=='default','small':type=='small'}">
        <router-link to="/blog/user/1">
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
      <div class='align-self-center right pl-2'> 
          <div class="d-flex align-self-center">
              <div :class="{'blogger-name':type=='default','small-name':type=='small'}">
                <router-link to="/blog/user/1">{{data.userinfo_userID.username}}
                </router-link>
              </div>
              <div class='ml-auto' style="width: 75px;">
                  <a v-if="!data.userinfo_userID.is_follower" class="btn btn-link text-primary w-100 btn-follow" >
                      <div class="d-flex justify-content-end align-items-end add">
                          <icon-plus></icon-plus>
                          关注
                      </div></a>
                  <a v-if="data.userinfo_userID.is_follower" class="btn btn-link text-default w-100 cancel-follow">
                      <span class="cancel-text text-danger">
                          <div class="d-flex justify-content-end align-items-end">
                              <icon-x :style="{fill:'#FF3636'}"></icon-x>
                              取消关注
                          </div>
                      </span>
                      <span class="followed-text text-default">
                          <div class="d-flex justify-content-end align-items-end" style="fill:#999">
                              <icon-check></icon-check>
                              已关注
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
        is_follower:Number
      }
    }
  },
  components: {
    [Button.name]: Button,
    IconPlus,
    IconCheck,
    IconX,
    IconV
  },
  
}

</script>
<style>

.hot-blogger{
  padding: 12px 18px;
  border-top:1px solid #ddd;
}
.hot-blogger.noborder{
  border-top:0;
}
.hot-blogger a{
  color:#14171a;
  text-decoration: none;
}
.hot-blogger .description a{
  color:#657786
}
.hot-blogger-avatar .avatar-word, .small .avatar-word{
    border-radius: 50%;
    background-color: aliceblue;
    
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
  width:48px;
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
    fill:#35abbb;
}
.hot-blogger .right .btn:hover .add{
    fill:#236e85;
}
.hot-blogger .right .description{
  font-size: small;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
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
