import MainNavbar from '../../layout/MainNavbar.vue';
export default [
    {
        path: '/login',
        name: 'login',
        components: { default: resolve => require (['./Login.vue'],resolve)},
    },
    {
        path: '/profile',
        name: 'profile',
        components: { default: resolve => require (['./Profile.vue'],resolve), header: MainNavbar },
    },
    {
        path: '/api_example',
        name: 'api_example',
        components: { default: resolve => require (['./API-example.vue'],resolve), header: MainNavbar },
    },
    {
        path: '/my',
        name: 'userindex',
        components: { default: resolve => require (['./UserIndex.vue'],resolve), header: MainNavbar },
    },{
        path:'/notices',
        name:'notices',
        components:{default: resolve => require (['./Notices.vue'],resolve), header: MainNavbar}
    }
]
