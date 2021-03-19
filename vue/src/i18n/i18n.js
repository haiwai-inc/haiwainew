import Vue from 'vue';
import locale from 'element-ui/lib/locale';
import VueI18n from 'vue-i18n';
import messages from './langs';
Vue.use(VueI18n)
let browserLanguage = window.navigator.language.toLowerCase();console.log(browserLanguage)
if(!localStorage.lang){
    if (browserLanguage === 'zh-cn') {
        localStorage.lang = 'cns'
    } else if (browserLanguage === 'zh-tw' || browserLanguage === 'zh-hk') {
        localStorage.lang = 'cnt'
    } else {
        localStorage.lang = 'cns'
    }
}

const i18n = new VueI18n({
    locale:localStorage.lang ,
    messages,
})
locale.i18n((key,value)=> i18n.t(key,value));

export default i18n;