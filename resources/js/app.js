/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueRouter from 'vue-router';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI)

import Profiles from './components/Profiles';
import CreateProfile from './components/CreateProfile';
import EditProfile from './components/EditProfile';
import store from './store/index';
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component('profiles', require('./components/Profiles.vue').default);

const routes = [
    {path: '/', component: Profiles, name: 'allProfiles'},
    {path: '/create', component: CreateProfile, name: 'createProfile'},
    {path: '/:id/edit', component: EditProfile, name: 'editProfile', props: true},
]

const router = new VueRouter({ routes })

const app = new Vue({
    store,
    router,
    el: '#app',
})

