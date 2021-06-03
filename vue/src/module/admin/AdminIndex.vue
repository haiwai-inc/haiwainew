<template>
    <div class="container pt-4">
        <el-tabs tab-position="left">
            <el-tab-pane label="博客管理"><blog-articles></blog-articles></el-tab-pane>
            <el-tab-pane label="用户管理"><user-management></user-management></el-tab-pane>
        </el-tabs>
    </div>
</template>
<script>
import BlogArticles from './blog/BlogArticles.vue';
import UserManagement from './blog/UserManagement';

import { Tabs,
  TabPane} from 'element-ui';

export default {
    name:"admin-index",
    components:{
        BlogArticles,
        UserManagement,
        [Tabs.name]:Tabs,
        [TabPane.name]:TabPane
    }
}
</script>
<style>

</style>