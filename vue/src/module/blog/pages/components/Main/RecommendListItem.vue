<template>
    <div class="recommend-list row no-gutters" id="author" @click="go(data.postID)">
      <!-- {{data.postInfo_postID.s_pic}} -->
      <div class="align-self-center right pr-2" :class="{'col-10':data.postInfo_postID.s_pic!=='','col-12':data.postInfo_postID.s_pic==''||type!=='default'}">
        <div class="d-flex align-self-center">
            <div class="name">{{data.postInfo_postID.title}}</div>
        </div>
        <div class="d-flex justify-content-between my-2" v-if="type==='default'">
            <div class="descrip">阅读 . {{data.countinfo_postID.count_read}}</div>
            <div class="descrip">{{data.create_date*1000 | formatDate}}</div>
        </div>
      </div>
      <div class="col-2" v-if="data.postInfo_postID.s_pic!=='' && type=='default'" >
        <div :style="{width:'50px',height:'50px',background: 'url('+data.postInfo_postID.s_pic+')',backgroundSize: 'cover',borderRadius:'5px'}"></div>
        <!-- <img alt="" v-bind:src="data.postInfo_postID.s_pic"  class="rounded"> -->
      </div>
    </div>
</template>
<script>
import {formatDate} from '@/directives/formatDate.js';
export default {
  name: 'recommend-list-item',
  props: {
    type: {
      type: String,
      default: 'default',
      description: 'Alert type'
    },
    data:Object
  },
  methods:{
    go(id){
      let url = '/blog/p/'+id
      this.$router.push(url);
      this.$router.go(url);
    }
  },
  filters: {
    formatDate(time) {
        var date = new Date(time);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  }
};

</script>
<style>

.recommend-list{
  cursor: pointer;
  padding: 12px 18px;
  border-top:1px solid #ddd;
}
.recommend-list:hover{
  background-color:#d9e9f740
}
.recommend-list .name {
  font-size: 1rem;
  font-weight: 600
}
.recommend-list .descrip{
  font-size:small;
  color: #9A9A9A;
  font-weight: 500
}

@media (max-width: 575.98px){
    
}
</style>
