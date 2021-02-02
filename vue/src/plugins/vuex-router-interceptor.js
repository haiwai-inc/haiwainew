import router from '../router/router'

export default function vuexRouterInterceptor () {
  return store => {
      
    router.beforeEach((to, from, next) => {
        // console.log(auth,$route.meta.requireAuth)
        // if (to.matched.some($route => $route.meta.requireAuth)) {
        //     // 当前访问的路由需要做登录拦截
        //     if (auth) {
        //       // 读取store中的登录状态
        //       // 此时已经登录过了
        //       next()
        //     } else {
        //       // 未登录过，跳转登录页面
        //       next('/login')
        //     }
        //   }
    })
  }
}
