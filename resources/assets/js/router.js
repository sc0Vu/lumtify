/**
 * This is lumtify router.
 */

import VueRouter from 'vue-router'

const Home = resolve => require(['./components/Home.vue'], resolve)
const About = resolve => require(['./components/About.vue'], resolve)
const Article = resolve => require(['./components/Article.vue'], resolve)
const Articles = resolve => require(['./components/Articles.vue'], resolve)
const Login = resolve => require(['./components/Login.vue'], resolve)
const Register = resolve => require(['./components/Register.vue'], resolve)
const Profile = resolve => require(['./components/Profile.vue'], resolve)
const Setting = resolve => require(['./components/Setting.vue'], resolve)
const UpdateArticle = resolve => require(['./components/UpdateArticle.vue'], resolve)
const CreateArticle = resolve => require(['./components/CreateArticle.vue'], resolve)

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { 
            name: 'home', 
            path: '/', 
            component: Home 
        }, { 
            name: 'about', 
            path: '/about', 
            component: About 
        }, { 
            name: 'login', 
            path: '/login',
            component: Login 
        }, { 
            name: 'register', 
            path: '/register',
            component: Register 
        }, { 
            name: 'profile', 
            path: '/user/profile/:uid', 
            meta: { 
                auth: { required: true }
            }, 
            component: Profile 
        }, { 
            name: 'setting', 
            path: '/user/setting/:uid', 
            meta: { 
                auth: { required: true } 
            }, 
            component: Setting 
        }, { 
            name: 'articles', 
            path: '/articles', 
            component: Articles 
        }, { 
            name: 'article', 
            path: '/articles/read/:link', 
            component: Article 
        }, { 
            name: 'updateArticle', 
            path: '/articles/update/:link', 
            meta: { 
                auth: { 
                    required: true,
                    roles: ['admin', 'editor']
                },
            }, 
            component: UpdateArticle 
        }, { 
            name: 'createArticle', 
            path: '/articles/create', 
            meta: { 
                auth: { 
                    required: true,
                    roles: ['admin', 'editor'] 
                },
            }, 
            component: CreateArticle 
        },
    ],
    linkActiveClass: 'active'
})
