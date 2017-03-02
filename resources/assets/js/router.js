/**
 * This is lumtify router.
 */

import VueRouter from 'vue-router';

const Home = resolve => require(['./components/Home.vue'], resolve);
const About = resolve => require(['./components/About.vue'], resolve);
const Article = resolve => require(['./components/Article.vue'], resolve);
const Login = resolve => require(['./components/Login.vue'], resolve);
const Register = resolve => require(['./components/Register.vue'], resolve);
const Profile = resolve => require(['./components/Profile.vue'], resolve);
const Setting = resolve => require(['./components/Setting.vue'], resolve);
const UpdateArticle = resolve => require(['./components/UpdateArticle.vue'], resolve);
const CreateArticle = resolve => require(['./components/CreateArticle.vue'], resolve);

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { name: 'home', path: '/', component: Home },
        { name: 'about', path: '/about', component: About },
        { name: 'article', path: '/article/read/:link', component: Article },
        { name: 'login', path: '/login', component: Login },
        { name: 'register', path: '/register', component: Register },
        { name: 'profile', path: '/user/profile/:uid', component: Profile },
        { name: 'setting', path: '/user/setting/:uid', component: Setting },
        { name: 'updateArticle', path: '/article/update/:link', component: UpdateArticle },
        { name: 'createArticle', path: '/article/create', component: CreateArticle },
    ],
    linkActiveClass: 'active'
});