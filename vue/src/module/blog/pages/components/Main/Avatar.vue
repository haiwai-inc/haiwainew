<template>
    <div id="avatar">
        <router-link :to="data.authorHomepage">
          <div v-if="!data.avatarUrl" class="avatar-word" :style="{height:imgHeight+'px',width:imgHeight+'px',lineHeight:imgHeight+'px'}">{{data.firstLetter}}</div>
          <img 
            :style="{height:imgHeight+'px'}"
            v-if="data.avatarUrl" 
            v-bind:alt="data.name" 
            class="rounded-circle" 
            v-bind:src="data.avatarUrl" 
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
        avatarUrl:String,
        name:String,
        authorHomepage:String,
        description:String,
        firstLetter:String,
        isFollowed:Boolean,
        isHot:Boolean
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
