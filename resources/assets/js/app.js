
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import ElementUI from 'element-ui';
import 'font-awesome/css/font-awesome.min.css';
import axios from 'axios';

Vue.use(ElementUI);

Vue.prototype.$host =  window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '');
Vue.prototype.$event = new Vue();
Vue.prototype.$href = window.location.href.split('?')[0];

axios.defaults.headers.common = {
    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};

Vue.prototype.$axios = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('common-table', require('./components/layouts/CommonTable.vue'));
Vue.component('common-form', require('./components/layouts/CommonForm.vue'));

const app = new Vue({
    el: '#app'
});
