import Vue from 'vue';

import VueRouter from 'vue-router';

import router from './router';

import Vuetify from 'vuetify';

Vue.use(VueRouter);

Vue.use(Vuetify);

require('vue-resource');

// const token = "1234";

// Vue.http.interceptors.push((request, next) => {
//     request.headers.set('Authorization', 'Bearer ' + token)

//     next();
// });

import PageHeader from './components/PageHeader.vue';

const app = new Vue({
	router,
	components: {
		PageHeader
	}
}).$mount('#app');