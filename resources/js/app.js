require('./bootstrap');

import Vue from 'vue';
import VueRouter from "vue-router";
Vue.use(VueRouter);

import {routes} from "./routes";

const router = new VueRouter({
    routes,
    mode: 'history'
})

import User from './Helpers/User';
window.User = User;


const app = new Vue({
    el: '#app',
    router: router,
});
