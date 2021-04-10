
import Vue from "vue";

require('./bootstrap');

window.Vue = Vue;

Vue.component('product-index', require('./components/Products/Index').default);

const app = new Vue({
    el: '#app',
});
