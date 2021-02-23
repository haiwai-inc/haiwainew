<template>
    <div id="avatar">
        <router-link :to="'/blog/user/'+data.id">
          <div v-if="!data.avatar" class="avatar-word" :style="{height:imgHeight+'px',width:imgHeight+'px',lineHeight:imgHeight+'px',minWidth:imgHeight+'px'}">{{data.first_letter}}</div>
          <img 
            :style="{height:imgHeight+'px',minWidth:imgHeight+'px'}"
            v-if="data.avatar" 
            v-bind:alt="data.username" 
            class="rounded-circle" 
            v-bind:src="data.avatar" 
            >
        </router-link>
    </div>
</template>
<script>

export default {
  name: 'avatar',
  props: {
    type: {
      type: String,
      default: 'default',
      description: 'Alert type'
    },
    imgHeight:Number,
    data:{
        avatar:String,
        username:String,
        id:Number,
        description:String,
        first_letter:String,
        is_following:Boolean,
        is_hot_blogger:Boolean
    }
  },
  data(){
      return {
        imgCDN:"https://cdn.wenxuecity.com",
        avatar:{
            UserID:1,
            avatarUrl:'/img/ryan.jpg',
            name:'Ryan',
            description:'String',
            firstLetter:'R',
            isFollowed:false,
            isHot:false
        }
      }
  }
};

</script>
<style>
#avatar .avatar-word{
    border-radius: 50%;
    background-color: aliceblue;
    text-align: center;
    font-weight: 500;
}
@media (max-width: 575.98px){
    
}
</style>
