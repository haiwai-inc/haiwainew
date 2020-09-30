import Vue from 'vue';
import App from './App.vue';
import router from './router/router';
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
