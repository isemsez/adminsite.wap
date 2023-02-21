let login = require('./components/auth/login.vue').default
let register = require('./components/auth/register.vue').default
let forgot = require('./components/auth/forgot.vue').default
let logout = require('./components/auth/logout.vue').default
let home = require('./components/home.vue').default

let employee_create = require('./components/Employee/create.vue').default
let employee_index = require('./components/Employee/index.vue').default
let employee_edit = require('./components/Employee/edit.vue').default
let supplier_create = require('./components/Supplier/create.vue').default
let supplier_index = require('./components/Supplier/index.vue').default

export const routes = [
    { path: '/', component: login, name: '/' },
    { path: '/register', component: register, name: 'register' },
    { path: '/forgot', component: forgot, name: 'forgot' },
    { path: '/logout', component: logout, name: 'logout' },
    { path: '/home', component: home, name: 'home' },

    // Employees
    { path: '/employee-create', component: employee_create, name: 'employee_create'},
    { path: '/employee-list', component: employee_index, name: 'employee_index'},
    { path: '/employee-edit/:id', component: employee_edit, name: 'employee_edit'},

    { path: '/supplier-create', component: supplier_create, name: 'supplier_create'},
    { path: '/supplier-list', component: supplier_index, name: 'supplier_index'},
]
