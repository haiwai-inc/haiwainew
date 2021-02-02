import Vue from 'vue';
import App from './App.vue';
import router from './router/router';
import NowUiKit from './plugins/now-ui-kit';
import API from './service/api';
import Vuex from 'vuex';
import User from './service/user';
import Search from './module/search/search.service.js';
import VueSocialSharing from 'vue-social-sharing'
import EleUploadImage from "vue-ele-upload-image";
import { Loading, Upload, Image, Dialog } from 'element-ui';
// import {ElementUI} from 'element-ui'
import Croppa from 'vue-croppa';

Vue.use(Croppa);

Vue.config.productionTip = false;

Vue.prototype.$api = new API();

Vue.use(NowUiKit);
Vue.use(Vuex);


Vue.use(VueSocialSharing);
// Vue.use(ElementUI);
Vue.use(Loading.directive);
Vue.use(Upload);
Vue.use(Image);
Vue.use(Dialog);
// Vue.component(EleUploadImage.name, EleUploadImage);
// Vue.component(EleUpload.name,EleUpload);

const store = new Vuex.Store({
  state: {
    user:new User(),
    search:new Search(),
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
