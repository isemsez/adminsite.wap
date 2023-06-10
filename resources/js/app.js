require('./bootstrap');

import Vue from 'vue';
import VueRouter from "vue-router";
import {routes} from "./routes";

Vue.use(VueRouter);
const router = new VueRouter({
    routes,
    mode: 'history'
})

import User from './Helpers/User';
window.User = User;

import Swal from "sweetalert2";
window.Swal = Swal

const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Toast = Toast

import Notification from "./Helpers/Notification";
window.Notification = Notification


const app = new Vue({
    el: '#app',
    router: router,
});
