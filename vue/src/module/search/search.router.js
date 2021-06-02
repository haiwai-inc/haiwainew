
import MainNavbar from '../../layout/MainNavbar.vue';
import MainFooter from '../../layout/MainFooter.vue';
export default [
    {
      
      
        path: '/search',
        name: 'search',
        components: { default: resolve => require (['./pages/Search.vue'],resolve), header: MainNavbar, footer: MainFooter  },
    },
]
