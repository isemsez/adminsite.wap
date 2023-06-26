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
let supplier_edit = require('./components/Supplier/edit.vue').default

let category_index = require('./components/Category/index.vue').default
let category_create = require('./components/Category/create.vue').default
let category_edit = require('./components/Category/edit.vue').default

let product_index = require('./components/Product/index.vue').default
let product_create = require('./components/Product/create.vue').default
let product_edit = require('./components/Product/edit.vue').default


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

    // Suppliers
    { path: '/supplier-create', component: supplier_create, name: 'supplier_create'},
    { path: '/supplier-list', component: supplier_index, name: 'supplier_index'},
    { path: '/supplier-edit/:id', component: supplier_edit, name: 'supplier_edit' },

    // Categories
    { path: '/category-list', component: category_index, name: 'category_index'},
    { path: '/category-create', component: category_create, name: 'category_create'},
    { path: '/category-edit/:id', component: category_edit, name: 'category_edit'},

    // Categories
    { path: '/product-list', component: product_index, name: 'product_index'},
    { path: '/product-create', component: product_create, name: 'product_create'},
    { path: '/product-edit/:id', component: product_edit, name: 'product_edit'},
]
