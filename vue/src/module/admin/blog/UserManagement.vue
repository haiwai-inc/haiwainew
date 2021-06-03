<template>
    <div>
        <el-input placeholder="请输入用户名或邮箱" v-model="keyword" class="input-with-select p-3" @keyup.enter.native="search">
            <el-button slot="append" icon="el-icon-search" @click="search" ></el-button>
        </el-input>
        <div v-for="item in userList" :key="item.id">
            <div class="mx-3 d-flex">
                <el-avatar :size="48" :src="item.avatar" :fit="'cover'" @error="item.avatar!==''">
                    <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"/>
                </el-avatar>
                <div class="ml-3 flex-fill">
                    {{item.username}}<span v-if="!item.status" class="text-danger ml-3">已被封禁</span><br>{{item.email}}
                </div>
                <div>
                <el-button v-if="item.status" class="ml-3" type="danger" plain round size="mini" @click="block_user(item,0)">封禁</el-button>
                <el-button v-if="!item.status" class="ml-3" type="success" plain round size="mini" @click="block_user(item,1)">解封</el-button>
                </div>
            </div>
            <el-divider></el-divider>
        </div>
    </div>
</template>
<script>

import HaiwaiIcons from "@/components/Icons/Icons";
import { Avatar, Divider } from 'element-ui';

export default {
    name:"user-management",
    components:{
        [Avatar.name]:Avatar,
        [Divider.name]:Divider
    },
    data(){
        return {
            user:this.$store.state.user,
            keyword:'',
            userList:[]
        } 
    },
    mounted() {
    },
    methods:{
        search(){
            this.user.user_search(this.keyword).then(res=>{
                if(res.status){
                    this.userList = res.data;console.log(this.userList);
                }
            })
        },
        block_user(item,type) {
            this.user.user_delete(item.id,type).then(res=>{
                if(res.status){
                    item.status=item.status===1?0:1
                    this.$message.success(item.status===1?'成功解封！':'已将此用户封禁！')
                }
            })
        }
    }
}
</script>
<style>

</style>