
import MainNavbar from '../../layout/MainNavbar.vue';
export default [
    {
      
      
        path: '/search',
        name: 'search',
        components: { default: resolve => require (['./pages/Search.vue'],resolve), header: MainNavbar },
    },
]
