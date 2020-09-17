import Vue from 'vue';
import Router from 'vue-router';
import Index from './pages/Index.vue';
import IndexHot from './pages/IndexHot.vue';
import ArticlePage from './pages/article/ArticlePage';
import Landing from './pages/Landing.vue';
import Login from './pages/Login.vue';
import Profile from './pages/Profile.vue';
import MainNavbar from './layout/MainNavbar.vue';
import MainFooter from './layout/MainFooter.vue';

Vue.use(Router);

export default new Router({
  linkExactActiveClass: 'active',
  routes: [
    {
      path: '/',
      name: 'index',
      components: { default: Index, header: MainNavbar, footer: MainFooter },
      
    },
    {
      path: '/hot',
      name: 'index-hot',
      components: { default: IndexHot, header: MainNavbar, footer: MainFooter },
      
    },
    {
      path: '/article',
      name: 'article-page',
      components: { default: ArticlePage, header: MainNavbar, footer: MainFooter },
      
    },
    {
      path: '/landing',
      name: 'landing',
      components: { default: Landing, header: MainNavbar, footer: MainFooter },
      
    },
    {
      path: '/login',
      name: 'login',
      components: { default: Login, header: MainNavbar },
      
    },
    {
      path: '/profile',
      name: 'profile',
      components: { default: Profile, header: MainNavbar, footer: MainFooter },
      
    }
  ],
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  }
});
