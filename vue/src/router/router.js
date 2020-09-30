import Vue from 'vue';
import Router from 'vue-router';
import MainNavbar from '../layout/MainNavbar.vue';

Vue.use(Router);

const routes = [
  {
    path: '',redirect:{name:'index'},
  },{
    path:'*',components:{default: resolve => require (['../module/404.vue'],resolve), header: MainNavbar}
  }
];
const files = require.context('../module', true, /\.router.js$/);
files.keys().forEach(key => {
  routes.push(...files(key).default);
});

export default new Router({
  linkExactActiveClass: 'active',
  mode: 'history' ,
  routes: routes ,
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  }
});
