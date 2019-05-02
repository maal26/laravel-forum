require('./bootstrap');

window.Vue = require('vue');

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('thread-view', require('./components/Thread/ThreadView.vue').default);
Vue.component('new-reply', require('./components/NewReply.vue').default);

new Vue({
    el: '#app'
});
