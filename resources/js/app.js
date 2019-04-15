require('./bootstrap');

window.Vue = require('vue');

Vue.component('flash', require('./components/Flash.vue').default);

new Vue({
    el: '#app'
});
