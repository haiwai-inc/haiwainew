<template>
<div>
  <span class="d-none d-sm-block">
    <div class="main-category-bar d-flex">
      <span v-for="(item,index) of data" :key="index">
        <n-button v-if="index<6 && item.visible==1"
            :key="item.id"
            type="default" 
            link 
            @click="sendTagId(item.id)" 
            v-bind:class="{active:activeId==item.id}"
        >
            <span>{{item.name}}</span>
        </n-button>
        
      </span>
      <drop-down
      class="nav-item dropdown"
      title="其它分类"
      style="margin-top:3px;"
      tag="span">
        <span v-for="(item,index) of data" :key="index">
          <a 
          class="dropdown-item" 
          v-if="index>=6 && item.visible==1"
          :key="item.id" href="#" 
          @click="sendTagId(item.id)">{{item.name}}</a>
        </span>
      </drop-down>
    </div>
  </span>
  <span class="d-block d-sm-none">
    <div class="main-category-bar d-flex">
      <span v-for="(item,index) of data" :key="index">
        <n-button v-if="index<3 && item.visible==1"
            :key="item.id"
            type="default" 
            link 
            @click="sendTagId(item.id)"
            v-bind:class="{active:activeId==item.id}"
        >
            <span>{{item.name}}</span>
        </n-button>
      </span>
      <drop-down
      class="nav-item dropdown"
      title="其它分类"
      style="margin-top:3px;"
      tag="span">
        <span v-for="(item,index) of data" :key="index">
          <a 
          class="dropdown-item" 
          v-if="index>=3 && item.visible==1"
          :key="item.id" href="#"
          @click="sendTagId(item.id)">{{item.name}}</a>
        </span>
      </drop-down>
    </div>
  </span>
</div>
</template>
<script>
import {
  Button,
  DropDown
} from '@/components';
export default {
  name: 'main-category-bar',
  props: {
    activeId:Number,
    data:Array
  },
  components: {
    [Button.name]: Button,
    DropDown
  },
  data() {
    return {
     
    }
  },
  methods:{
    sendTagId(id){
      this.$emit('getTagId',id)
    }
  }
};
</script>
<style>
.main-category-bar span{
  font-size: 1rem;
  font-weight: 500;
  color: gray;
}
.main-category-bar button{
  background-color: #f5f5f5 !important;
  margin-right:1rem;
}
.main-category-bar .active ,.main-category-bar .active span{
  text-align: center;
    color:#32caf9 !important;
    background-color: aliceblue !important;
}
.main-category-bar .active{
    cursor:default !important;
}
</style>
