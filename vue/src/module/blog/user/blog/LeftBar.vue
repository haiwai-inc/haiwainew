<template>
    <ul>
        <li class="d-flex align-items-center li_item" 
        v-for="(item,index) in collectionList" 
        :key="index"
        :class="{active:item.id===currentTabId}"
        >
            <div class="flex-fill collections" @click="changeTab(item.id)">{{item.name}} ({{item.count_article}})<span class="ml-2 text-muted" v-if="!item.is_publish">-隐</span></div>
            <el-dropdown trigger="click">
                <span class="el-dropdown-link">
                    <span v-html="iconmore3v"></span>
                </span>
                <el-dropdown-menu slot="dropdown">
                    <span @click="openDialog(item)">
                        <el-dropdown-item icon="el-icon-setting">目录设置</el-dropdown-item>
                    </span>
                    <span @click="category_shift(index,index-1)" v-if="index!=0">
                        <el-dropdown-item icon="el-icon-arrow-up">向上移动</el-dropdown-item>
                    </span>
                    <span @click="category_shift(index,index+1)" v-if="index<collectionList.length-1">
                        <el-dropdown-item icon="el-icon-arrow-down">向下移动</el-dropdown-item>
                    </span>
<!--  删除确认  -->
                    <el-popconfirm
                        v-if="!item.is_default"
                        placement="top-end"
                        confirm-button-text="刪除"
                        cancel-button-text='取消'
                        :title="$t('message').editor.wenji_comfirm_title+item.name+$t('message').editor.wenji_comfirm_title1"
                        :hide-icon="true"
                        @confirm="category_delete(item)"
                    >
                        <a href="javascript:void(0)" slot="reference">
                            <el-dropdown-item divided icon="el-icon-delete">
                                {{$t('message').editor.wenji_delet_menu}}
                            </el-dropdown-item>
                        </a>
                    </el-popconfirm>
                </el-dropdown-menu>
            </el-dropdown>
        </li>
    </ul>
</template>
<script>

import HaiwaiIcons from "@/components/Icons/Icons";
export default {
    name: 'left-bar',
    props:{
        currentTabId:Number,
        collectionList:Array
    },
    watch:{
        'currentTabId':function(){
            
        }
    },
    components:{
        
    },
    mounted:function(){
    },
    methods:{
        changeTab(id){
            this.$emit('changetab',id)
        },
        openDialog(item){
            this.$emit('opendialog',item)
        },
        category_shift(form,to){
            this.$emit('shift',[form,to])
        },
        category_delete(item){
            this.$emit('delete',item)
        }
    },
    data() {
        return {
            iconmore3v: HaiwaiIcons.iconmore3v,
        };
    },
}
</script>
<style>

</style>