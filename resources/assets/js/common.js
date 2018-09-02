
//const _ = require('lodash');

// axiosにCSRFトークンを取り込む
const axios = require('axios');
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

// Vueの登録
const Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue'));
const app = new Vue({
    el: '#app'
});
