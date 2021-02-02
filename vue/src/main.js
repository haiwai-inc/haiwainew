import Vue from 'vue';
import App from './App.vue';
import router from './router/router';
import NowUiKit from './plugins/now-ui-kit';
import API from './service/api';
import Vuex from 'vuex';
import User from './service/user';
import Search from './module/search/search.service.js';
import jquery from 'jquery'
import VueSocialSharing from 'vue-social-sharing';
import vuexRouterInterceptor from './plugins/vuex-router-interceptor';

Vue.config.productionTip = false;

Vue.prototype.$api = new API();

Vue.use(NowUiKit);
Vue.use(Vuex);

Vue.use(VueSocialSharing);

const store = new Vuex.Store({
  state: {
    user:new User(),
    search:new Search(),
  },
  mutations: {
    increment (state) {
      state.count++
    }
  },
  // plugins:[vuexRouterInterceptor()]
})

new Vue({
  store: store,
  router,
  render: h => h(App),
}).$mount('#app');
