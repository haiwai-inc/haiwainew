import 'bootstrap/dist/css/bootstrap.css';
import '@/assets/scss/now-ui-kit.scss';
import '@/assets/demo/demo.css';
import globalDirectives from './globalDirectives';
import globalMixins from './globalMixins';
import globalComponents from './globalComponents';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
import VueLazyload from 'vue-lazyload';
import infiniteScroll from "vue-infinite-scroll";
import {Message,Alert} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
// import "bootstrap/dist/js/bootstrap"

locale.use(lang);

export default {
  install(Vue) {
    Vue.use(globalDirectives);
    Vue.use(globalMixins);
    Vue.use(globalComponents);
    Vue.use(VueLazyload, {
      observer: true,
      // optional
      observerOptions: {
        rootMargin: '0px',
        threshold: 0.1
      }
    });
    Vue.use(infiniteScroll); 
    Vue.use(Alert);
    Vue.prototype.$message = Message;
  }
};
