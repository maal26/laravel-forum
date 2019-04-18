require('./bootstrap');

window.Vue = require('vue');

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('reply', require('./components/Reply.vue').default);

new Vue({
    el: '#app'
});
