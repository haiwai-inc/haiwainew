import Vue from 'vue';
import Router from 'vue-router';
import MainNavbar from '../layout/MainNavbar.vue';
import MainFooter from '../layout/MainFooter.vue';
import User from '../service/user';

Vue.use(Router);

const routes = [
  {
    path: '',redirect:{name:'index'},
  },{
    path:'*',
    name:'404',
    components:{default: resolve => require (['../module/pages/404.vue'],resolve), header: MainNavbar, footer: MainFooter}
  },
  {
      path: '/privacy',
      name: 'privacy',
      components: { default: resolve => require (['../module/pages/Privacy.vue'],resolve), header: MainNavbar, footer: MainFooter },
  },
  {
      path: '/tou',
      name: 'tou',
      components: { default: resolve => require (['../module/pages/Tou.vue'],resolve), header: MainNavbar, footer: MainFooter },
  },
    {
      path: '/about',
      name: 'about',
      components: { default: resolve => require (['../module/pages/About.vue'],resolve), header: MainNavbar, footer: MainFooter },
  }
];
const files = require.context('../module', true, /\.router.js$/);
files.keys().forEach(key => {
  routes.push(...files(key).default);
});
let router = new Router({
  linkExactActiveClass: 'active',
  mode: 'history' ,
  routes: routes ,
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  },
});
let user = new User();
router.beforeEach((to,from,next)=>{
  let status = false;
  if(to.matched.some(record=>record.meta.requiresAuth)){
    user.getUserStatus().then(res=>{
      status=res.status;
      if(!status){
        next({
          path: '/login',
          query: { redirect: to.fullPath }
        })
      }else {
        if(to.name=="admin" && res.data.auth_group!=2){
          next({path:'/'})
        }
        next()
      }
    })
  }else{
    next()
  }
})
export default router;
