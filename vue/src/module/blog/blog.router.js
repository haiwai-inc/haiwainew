
import MainNavbar from '../../layout/MainNavbar.vue';
import MainFooter from '../../layout/MainFooter.vue';
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
        path: '/blog/new',
        name: 'new',
        components: { default:resolve => require (['./pages/IndexNewest.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/follows',
        name: 'follows',
        components: { default:resolve => require (['./pages/IndexFollows.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/p/:id',
        name: 'article',
        components: { default:resolve => require (['./pages/article/ArticlePage.vue'],resolve), header: MainNavbar},
      // },{
      //   path: '/blog/editor',
      //   name: 'editor',
      //   components: { default:resolve => require (['./pages/editor/EditorPage.vue'],resolve)},
      //   meta:{requiresAuth:true}
      },{
        path: '/blog/my/editor',
        name: 'myeditor',
        components: { default:resolve => require (['./pages/editor/Index.vue'],resolve)},
        meta:{requiresAuth:true}
      },{
        path: '/blog/editortest',
        name: 'editortest',
        components: { default:resolve => require (['./pages/editor/EditorTest.vue'],resolve)},
        meta:{requiresAuth:true}
      },{
        path: '/blog/user/:id',
        name: 'bloguserindex',
        components: { default:resolve => require (['./user/Index.vue'],resolve), header: MainNavbar},
      },{
        path: '/blog/my',
        name: 'myblog',
        components: { default:resolve => require (['./user/blog/Index.vue'],resolve), header: MainNavbar},
        meta:{requiresAuth:true}
      },{
        path: '/blog/c/:bloggerid/:catid',
        name: 'categorindex',
        components: { default:resolve => require (['./user/IndexCategory.vue'],resolve), header: MainNavbar},
      }
]
