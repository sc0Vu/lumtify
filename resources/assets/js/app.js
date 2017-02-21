import Vue from 'vue';

import VueRouter from 'vue-router';

import VueResource from 'vue-resource';

import Vuetify from 'vuetify';

import router from './router';

Vue.use(VueRouter);

Vue.use(VueResource);

Vue.use(Vuetify);

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