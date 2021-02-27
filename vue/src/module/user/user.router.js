import MainNavbar from '../../layout/MainNavbar.vue';


export default [
    {
        path: '/login',
        name: 'login',
        components: { default: resolve => require (['./login/Login.vue'],resolve)},
    },
    {
        path: '/profile',
        name: 'profile',
        components: { default: resolve => require (['./Profile.vue'],resolve), header: MainNavbar },
        meta:{requiresAuth:true}
    },
    {
        path: '/bookmark',
        name: 'bookmark',
        components: { default: resolve => require (['./BookMark.vue'],resolve), header: MainNavbar },
        meta:{requiresAuth:true}
    },
    {
        path: '/api_example',
        name: 'api_example',
        components: { default: resolve => require (['./API-example.vue'],resolve), header: MainNavbar },
    },
    {
        path:'/notices',
        name:'notices',
        components:{default: resolve => require (['./notices/Notices.vue'],resolve), header: MainNavbar},
        meta:{requiresAuth:true}
    }
]
