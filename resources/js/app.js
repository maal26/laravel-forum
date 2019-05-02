require('./bootstrap');

window.Vue = require('vue');

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('thread-view', require('./components/Thread/ThreadView.vue').default);

new Vue({
    el: '#app'
});
