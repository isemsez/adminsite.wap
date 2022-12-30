
let login = require('./components/login/login.vue').default
let register = require('./components/login/register.vue').default

export const routes = [
    { path: '/', component: login, name: '/' },
    { path: '/register', component: register, name: 'register' },
]
