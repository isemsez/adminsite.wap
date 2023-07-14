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

let expense_index = require('./components/Expense/index.vue').default
let expense_create = require('./components/Expense/create.vue').default
let expense_edit = require('./components/Expense/edit.vue').default

let salary = require('./components/Salary/all_employee.vue').default
let pay_salary = require('./components/Salary/create.vue').default
let all_salary = require('./components/Salary/index.vue').default
let view_salary = require('./components/Salary/view.vue').default
let edit_salary = require('./components/Salary/edit.vue').default


export const routes = [
    {path: '/', component: login, name: '/'},
    {path: '/register', component: register, name: 'register'},
    {path: '/forgot', component: forgot, name: 'forgot'},
    {path: '/logout', component: logout, name: 'logout'},
    {path: '/home', component: home, name: 'home'},

    // Employees
    {path: '/employee-create', component: employee_create, name: 'employee_create'},
    {path: '/employee-list', component: employee_index, name: 'employee_index'},
    {path: '/employee-edit/:id', component: employee_edit, name: 'employee_edit'},

    // Suppliers
    {path: '/supplier-create', component: supplier_create, name: 'supplier_create'},
    {path: '/supplier-list', component: supplier_index, name: 'supplier_index'},
    {path: '/supplier-edit/:id', component: supplier_edit, name: 'supplier_edit'},

    // Categories
    {path: '/category-list', component: category_index, name: 'category_index'},
    {path: '/category-create', component: category_create, name: 'category_create'},
    {path: '/category-edit/:id', component: category_edit, name: 'category_edit'},

    // Products
    {path: '/product-list', component: product_index, name: 'product_index'},
    {path: '/product-create', component: product_create, name: 'product_create'},
    {path: '/product-edit/:id', component: product_edit, name: 'product_edit'},

    // Expenses
    {path: '/expense-list', component: expense_index, name: 'expense_index'},
    {path: '/expense-create', component: expense_create, name: 'expense_create'},
    {path: '/expense-edit/:id', component: expense_edit, name: 'expense_edit'},

    // Salaries
    {path: '/given-salary', component: salary, name: 'given_salary'},
    {path: '/pay-salary/:id', component: pay_salary, name: 'pay_salary'},

    {path: '/salary', component: all_salary, name: 'salary'},
    {path: '/view-salary/:id', component: view_salary, name: 'view_salary'},
    {path: '/edit-salary/:id', component: edit_salary, name: 'edit_salary'}
]
