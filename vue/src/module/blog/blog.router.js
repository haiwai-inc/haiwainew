
import MainNavbar from '../../layout/MainNavbar.vue';
export default [
    {
        path: '/blog',
        name: 'index',
        components: { default:resolve => require (['./pages/Index.vue'],resolve), header: MainNavbar},
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
        components: { default:resolve => require (['./pages/IndexHot.vue'],resolve), header: MainNavbar},
        
      },{
        path: '/blog/p/:id',
        name: 'article',
        components: { default:resolve => require (['./pages/article/ArticlePage.vue'],resolve), header: MainNavbar},
        
      },{
        path: '/blog/write',
        name: 'editor',
        components: { default:resolve => require (['./pages/editor/EditorPage.vue'],resolve), header: MainNavbar},
        
      },
      {
          path: '/search',
          name: 'search',
          components: { default: resolve => require (['./pages/Search.vue'],resolve), header: MainNavbar },
      },
]
