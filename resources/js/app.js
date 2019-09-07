
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//plugin
import router from './router';
import store from './vuex/store';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
import ToggleButton from 'vue-js-toggle-button'
import ImageUploader from 'vue-image-upload-resize'

//components
import App from './components/App';

//init toast
Vue.use(VueToast);
//init toggle switch
Vue.use(ToggleButton);
//init image uploader
Vue.use(ImageUploader);

//create Vue App
new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});