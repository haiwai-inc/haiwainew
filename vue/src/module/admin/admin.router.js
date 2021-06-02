import MainNavbar from '../../layout/MainNavbar.vue';

export default [
    
    {
        path:'/backend',
        name:'admin',
        components:{default: resolve => require (['./AdminIndex.vue'],resolve), header: MainNavbar},
        meta:{requiresAuth:true}
    }
]
