
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';
import 'font-awesome/css/font-awesome.min.css';
Vue.use(ElementUI);

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

Vue.component('example-component', require('./components/leadmin/ExampleComponent.vue'));
Vue.component('common-table', require('./components/leadmin/layouts/CommonTable.vue'));
Vue.component('common-form', require('./components/leadmin/layouts/CommonForm.vue'));
Vue.component('common-tree', require('./components/leadmin/layouts/CommonTree.vue'));
Vue.component('page-login', require('./components/leadmin/layouts/Login.vue'));
Vue.component('page-scaffold', require('./components/leadmin/layouts/Scaffold.vue'));

const app = new Vue({
    el: '#app'
});