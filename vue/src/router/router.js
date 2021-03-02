import Vue from 'vue';
import Router from 'vue-router';
import MainNavbar from '../layout/MainNavbar.vue';
import User from '../service/user';

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
        // if(to.name=="editor" && res.data.bloggerID==0){
        //   next({path:'/blog_register'})
        // }
        next()
      }
    })
  }else{
    next()
  }
})
export default router;
