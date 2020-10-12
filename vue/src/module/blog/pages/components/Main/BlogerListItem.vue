<template>
    <div class="hot-blogger row no-gutters" id="author" :class="{noborder:type=='small'}">
      <div class="col-2" :class="{'hot-blogger-avatar':type=='default',small:type=='small'}">
        <router-link to="/blog/user/1">
        <i class="now-ui-icons objects_diamond text-primary lable" v-if="data.isHot"></i>
          <div v-if="!data.avatarUrl" class="avatar-word">{{data.firstLetter}}</div>
          <img 
            v-if="data.avatarUrl" 
            v-bind:alt="data.name" 
            class="rounded-circle" 
            v-bind:src="data.avatarUrl" 
            >
        </router-link>
      </div>
      <div class='align-self-center right col-10'> 
          <div class="d-flex align-self-center">
              <div :class="{'blogger-name':type=='default','small-name':type=='small'}">
                <router-link to="/blog/user/1">{{data.name}}
                </router-link>
              </div>
              <div class='ml-auto' style="width: 75px;">
                  <a v-if="!data.isFollowed" class="btn btn-link text-primary w-100 btn-follow" >
                      <div class="d-flex justify-content-end align-items-end add">
                          <icon-plus></icon-plus>
                          关注
                      </div></a>
                  <a v-if="data.isFollowed" class="btn btn-link text-default w-100 cancel-follow">
                      <span class="cancel-text text-danger">
                          <div class="d-flex justify-content-end align-items-end">
                              <icon-x></icon-x>
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
          <div id="description" class="description" title="data.description">
            <router-link to="/blog/user/1">{{data.description}}
            </router-link>
          </div> 
      </div>
    </div>
</template>
<script>
import {
    IconPlus,
    IconCheck,
    IconX
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
      articleUrl:String,
      name:String,
      description:String,
      firstLetter:String,
      isFollowed:Boolean,
      isHot:Boolean
    }
  },
  components: {
    [Button.name]: Button,
    IconPlus,
    IconCheck,
    IconX
  }
  
};

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
  margin-left: 35px;
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
.hot-blogger-avatar img{
  width:48px;
}
.small img{
    width:36px;
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
  /* overflow:hidden; 
  text-overflow:ellipsis; 
  white-space: nowrap;
  display: block; */
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
