import Vue from 'vue';

import VueRouter from 'vue-router';

import VueResource from 'vue-resource';

import Vuetify from 'vuetify';

import router from './router';

import PageHeader from './components/PageHeader.vue';

Vue.use(VueRouter);

Vue.use(VueResource);

Vue.use(Vuetify);

// set up jwt auth
window.token = localStorage.getItem('lumtify') || '';

Vue.http.interceptors.push((request, next) => {
    request.headers.set('authorization', 'bearer ' + window.token);

    next();
});

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
		this.checkAuth();
	},
	methods: {
		checkAuth () {
			window.token = localStorage.getItem('lumtify') || ''
			this.$http.get('/api/auth/user').then((res) => {
				var data = res.body

				if (data.success) {
					this.auth.isAuth = true;
					this.auth.user = data.user
				}
			}).catch((err) => {
				var e = err.body

				if (!e.success) {
					// this.resetToken()
					this.auth.isAuth = false
				}
			}).then(() => {
			})
		},
		resetToken () {
			localStorage.setItem('lumtify', '')
		}
	},
	watch: {
		'$route': 'checkAuth'
	}
}).$mount('#app');