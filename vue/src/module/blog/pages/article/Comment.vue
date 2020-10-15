<template>
  <div class="comment">
    <div v-for="(item, index) in data" :key="index" class="d-flex align-items-start mt-2">
        <avatar :data="item" :imgHeight="38" class="mr-2"></avatar>
        <div>
            <span class="replyName">{{item.name}}</span><span class="replyTime">{{item.time}}</span>
            <p class="replyContent" v-html="item.content"></p>
            <p class="replyFoot"><icon-like-outline :style="{stroke:'gray',height:'18px'}"></icon-like-outline> {{item.like!==0?item.like:''}} <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message><a href="#" style="color:gray">回复</a></p>
            <div v-if="item.replies.length>0">
                <div :id="index" v-show="item.showReply">
                    <div v-for="(r,idx) in item.replies" :key="'r'+idx" class="d-flex align-items-start mt-2">
                        <avatar :data="r" :imgHeight="32" class="mr-2"></avatar>
                        <div>
                            <span class="replyName">{{r.name}}</span><span class="replyTime">{{r.time}}</span>
                            <p class="replyContent" v-html="r.content"></p>
                            <p class="replyFoot"><icon-like-outline :style="{stroke:'gray',height:'18px'}"></icon-like-outline> {{r.like!==0?r.like:''}} <icon-message :style="{fill:'gray',height:'18px'}" class="ml-4"></icon-message><a href="#" style="color:gray">回复</a></p>
                        </div>
                    </div>
                </div>
                <button class="btn btn-link btn-info" style="padding-left:0px" v-on:click="item.showReply?item.showReply=false:item.showReply=true">{{item.showReply?'隐藏':'显示'}} {{item.replies.length}} 条回复</button>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
import {IconLikeOutline,IconMessage} from '@/components/Icons'
import Avatar from '../components/Main/Avatar'
export default {
  name: 'comment',
  props:{
      data:{}
  },
  components: {
      Avatar,
      IconLikeOutline,
      IconMessage
  },
  methods:{
      toggle(id){
          console.log(id)
          return data[id].showReply?data[id].showReply=false:data[id].showReply=true;
      }
  }
//   item.showReply?item.showReply==false:item.showReply==true
};
</script>
<style>
.comment .replyContent,.comment .replyFoot{
    font-size: 1rem;
    margin-bottom: 3px;
}
.comment .replyName{
    font-weight: 700;
}
.comment .replyTime{
    color:gray;
    font-size: .875rem;
    margin-left: 1rem;
}
@media (max-width: 575.98px){

}
</style>
