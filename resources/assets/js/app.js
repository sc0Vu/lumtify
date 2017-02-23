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
    request.headers.set('authorization', 'bearer ' + token);

    next();
});

import PageHeader from './components/PageHeader.vue';

const app = new Vue({
	router,
	components: {
		PageHeader
	},
	data () {
		return {
			auth: {
				isAuth: false,
				user: {}
			}
		};
	},
	created () {
		this.auth.token = localStorage.getItem('lumtify') || '';
		this.checkAuth();
	},
	methods: {
		checkAuth () {
			this.$http.get('/api/auth/user').then((res) => {
				var data = res.body

				if (data.success) {
					this.auth.isAuth = true;
					this.auth.user = data.user
				}
			}).catch((err) => {
				var e = err.body

				if (!e.success) {
					this.resetToken();
					this.auth.isAuth = false;
				}
			}).then(() => {
				// console.log('Finished')
			})
		},
		resetToken () {
			localStorage.setItem('lumtify', '');
		}
	}
}).$mount('#app');