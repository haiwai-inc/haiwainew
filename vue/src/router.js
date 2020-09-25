import Vue from 'vue';
import Router from 'vue-router';
import MainNavbar from './layout/MainNavbar.vue';

Vue.use(Router);

export default new Router({
  linkExactActiveClass: 'active',
  mode: 'history',
  routes: [
    {
    path: '',redirect:{name:'index'}
    
    },{
      path: '/blog',
      name: 'index',
      components: { default:resolve => require (['./module/blog/pages/Index.vue'],resolve), header: MainNavbar},
      // children:[
      //   {
      //     path:'hot',
      //     name:'hot',
      //     component:resolve => require (['./module/blog/pages/IndexHot.vue'],resolve)
      //   }
      // ]
    },{
      path: '/blog/hot',
      name: 'hot',
      components: { default:resolve => require (['./module/blog/pages/IndexHot.vue'],resolve), header: MainNavbar},
      
    },{
      path: '/blog/p/:id',
      name: 'article',
      components: { default:resolve => require (['./module/blog/pages/article/ArticlePage.vue'],resolve), header: MainNavbar},
      
    },{
      path: '/blog/write',
      name: 'editor',
      components: { default:resolve => require (['./module/blog/pages/editor/EditorPage.vue'],resolve), header: MainNavbar},
      
    },
    {
      path: '/login',
      name: 'login',
      components: { default: resolve => require (['./module/user/Login.vue'],resolve), header: MainNavbar },
    },
    {
      path: '/profile',
      name: 'profile',
      components: { default: resolve => require (['./module/user/Profile.vue'],resolve), header: MainNavbar },
      
    },
    {
      path: '/api_example',
      name: 'api_example',
      components: { default: resolve => require (['./module/user/API-example.vue'],resolve), header: MainNavbar },
      
    },
    {
      path: '/my',
      name: 'userindex',
      components: { default: resolve => require (['./module/user/UserIndex.vue'],resolve), header: MainNavbar },
      
    },
    {
      path: '/search',
      name: 'search',
      components: { default: resolve => require (['./module/blog/pages/Search.vue'],resolve), header: MainNavbar },
      
    },{
      path:'/notices',
      name:'notices',
      components:{default: resolve => require (['./module/user/Notices.vue'],resolve), header: MainNavbar}
    },
    {path:'*',components:{default: resolve => require (['./module/404.vue'],resolve), header: MainNavbar}}
  ],
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  }
});
