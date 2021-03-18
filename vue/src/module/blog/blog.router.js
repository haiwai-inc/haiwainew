
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
        path: '/blog/follows',
        name: 'follows',
        components: { default:resolve => require (['./pages/IndexFollows.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/p/:id',
        name: 'article',
        components: { default:resolve => require (['./pages/article/ArticlePage.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/editor',
        name: 'editor',
        components: { default:resolve => require (['./pages/editor/EditorPage.vue'],resolve)},
        meta:{requiresAuth:true}
      },{
        path: '/blog/success',
        name: 'success',
        components: { default:resolve => require (['./pages/editor/Success.vue'],resolve), header: MainNavbar},
        meta:{requiresAuth:true}
      },{
        path: '/blog/user/:id',
        name: 'bloguserindex',
        components: { default:resolve => require (['./user/Index.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/c/:userid/:catid',
        name: 'categorindex',
        components: { default:resolve => require (['./user/IndexCategory.vue'],resolve), header: MainNavbar},
      }
]
