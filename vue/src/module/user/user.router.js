import MainNavbar from '../../layout/MainNavbar.vue';
import MainFooter from '../../layout/MainFooter.vue';


export default [
    {
        path: '/login',
        name: 'login',
        components: { default: resolve => require (['./login/Login.vue'],resolve)},
    },
    {
        path: '/resetpassword',
        name: 'resetpassword',
        components: { default: resolve => require (['./login/ResetPassword.vue'],resolve)},
    },
    {
        path: '/blog_register',
        name: 'blog_register',
        components: { default: resolve => require (['./login/RegistBlog.vue'],resolve), header: MainNavbar, footer: MainFooter },
    },
    {
        path: '/profile',
        name: 'profile',
        components: { default: resolve => require (['./Profile.vue'],resolve), header: MainNavbar , footer: MainFooter},
        meta:{requiresAuth:true}
    },
    {
        path: '/bookmark',
        name: 'bookmark',
        components: { default: resolve => require (['./BookMark.vue'],resolve), header: MainNavbar, footer: MainFooter },
        meta:{requiresAuth:true}
    },
    // {
    //     path: '/api_example',
    //     name: 'api_example',
    //     components: { default: resolve => require (['./API-example.vue'],resolve), header: MainNavbar, footer: MainFooter },
    // },
    {
        path:'/notices',
        name:'notices',
        components:{default: resolve => require (['./notices/Notices.vue'],resolve), header: MainNavbar, footer: MainFooter},
        meta:{requiresAuth:true}
    }
]
