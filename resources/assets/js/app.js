import Vue from 'vue';

import VueRouter from 'vue-router';

import VueResource from 'vue-resource';

import Vuetify from 'vuetify';

import router from './router';

Vue.use(VueRouter);

Vue.use(VueResource);

Vue.use(Vuetify);

// set up jwt auth
const token = localStorage.getItem('lumtify') || '';

Vue.http.interceptors.push((request, next) => {
    request.headers.set('Authorization', 'Bearer ' + token);

    next();
});

import PageHeader from './components/PageHeader.vue';

const app = new Vue({
	router,
	components: {
		PageHeader
	}
}).$mount('#app');