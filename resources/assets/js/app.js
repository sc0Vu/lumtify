import Vue from 'vue'

import VueRouter from 'vue-router'

import VueResource from 'vue-resource'

import Vuetify from 'vuetify'

import Marked from 'marked'

import router from './router'

import PageHeader from './components/PageHeader.vue'

Vue.use(VueRouter)

Vue.use(VueResource)

Vue.use(Vuetify)

// set up jwt auth in vue http
Vue.http.interceptors.push((request, next) => {
	var token = localStorage.getItem('lumtify') || ''

    request.headers.set('authorization', 'bearer ' + token)

    next()
})

// set markdown editor options
Marked.setOptions({
    gfm: true,
    tables: true,
    breaks: false,
    pedantic: false,
    sanitize: true,
    smartLists: true,
    smartypants: false
})

// use window marked if want to marked in local component
// like 
// computed: {
//     markdown () {
//         return marked(content)
//     }
// }
// window.marked = Marked
// use like filter
// {{ content | marked }} => only text
// Vue.filter('marked', function (content) {
// 	return marked(content)
// })

Vue.directive('markdown', {
	bind (el, binding, vnode) {
		el.innerHTML = Marked(binding.value)
	},
	// inserted () {},
	update (el, binding, vnode) {
		el.innerHTML = Marked(binding.value)
	},
	// componentUpdated () {},
	// unbind () {}
})

var auth = {
	isAuth: false,
	user: {},
	roles: [],
}

router.beforeEach((to, from, next) => {
	Vue.http.get('/api/auth/user').then((res) => {
		var data = res.body

		if (data.success) {
			auth.isAuth = true
			auth.user = data.user
			auth.roles = data.roles
		}
	}).catch((err) => {
		var e = err.body

		if (!e.success) {
			localStorage.setItem('lumtify', '')
			auth.isAuth = false
		}
	}).then(() => {
		var routerAuth = null

		to.matched.some((record) => {
			if (record.meta.auth) {
				routerAuth = record.meta.auth
			}
		})

	    if (routerAuth && routerAuth.required) {
		    if (!auth.isAuth) {
		        next({
		            name: 'login'
		        })
		    } else {
		    	if (routerAuth.roles) {
		    		var isAuth = false
		    		var length = routerAuth.roles.length

		    		for (var i=0; i<length; i++) {
		                if (auth.roles.indexOf(routerAuth.roles[i]) >= 0) {
		                	isAuth = true
		                	break;
		                }
		            }
		            if (isAuth) {
		            	next()
		            } else {
		            	next({
				            name: 'home'
				        })
		            }
		    	} else {
		    		next()
		    	}
		    }
	    } else {
			next()
	    }
	})
})

const app = new Vue({
	router,
	components: {
		PageHeader
	},
	data () {
		return {
			auth: auth
		};
	}
}).$mount('#app')