/*!

 =========================================================
 * Vue Now UI Kit - v1.1.0
 =========================================================

 * Product Page: https://www.creative-tim.com/product/now-ui-kit
 * Copyright 2019 Creative Tim (http://www.creative-tim.com)

 * Designed by www.invisionapp.com Coded by www.creative-tim.com

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 */
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import NowUiKit from './plugins/now-ui-kit';
import API from './api';
import Vuex from 'vuex';
import User from './service/user'

Vue.config.productionTip = false;

Vue.prototype.$api = new API()



Vue.use(NowUiKit);

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    user:new User()
  },
  mutations: {
    increment (state) {
      state.count++
    }
  }
})

new Vue({
  router,
  render: h => h(App),
  store: store,
}).$mount('#app');
