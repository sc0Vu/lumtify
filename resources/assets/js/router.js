/**
 * This is lumtify router.
 */

import VueRouter from 'vue-router';

const Home = resolve => require(['./components/Home.vue'], resolve);
const About = resolve => require(['./components/About.vue'], resolve);
const Article = resolve => require(['./components/Article.vue'], resolve);
const Login = resolve => require(['./components/Login.vue'], resolve)
const Register = resolve => require(['./components/Register.vue'], resolve)

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { name: 'home', path: '/', component: Home },
        { name: 'about', path: '/about', component: About },
        { name: 'article', path: '/article/:link', component: Article },
        { name: 'login', path: '/login', component: Login },
        { name: 'register', path: '/register', component: Register },
    ],
    linkActiveClass: 'active'
});