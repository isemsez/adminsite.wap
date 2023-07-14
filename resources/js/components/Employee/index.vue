<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'employee_create' }"> Создать работника
                </router-link>
                <span class="px-3"></span>
                <input type="text" id="search" class="form-control-sm col-md-4"
                       v-model="searchBox" placeholder="Поиск">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Список работников</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Имя</th>
                                <th>Фото</th>
                                <th>Номер телефона</th>
                                <th>Почта</th>
                                <th>Зарплата</th>
                                <th>Устроился(ась) на работу</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="employee in filtered" :key="employee.id">
                                <td>{{ employee.name }}</td>
                                <td><img :src="employee.photo" class="photo" alt="photo"></td>
                                <td>{{ employee.phone }}</td>
                                <td>{{ employee.email }}</td>
                                <td>{{ employee.salary }}</td>
                                <td>{{ (employee.joining_date.split(' '))[0] }}</td>
                                <td>
                                    <router-link :to="{ name: 'employee_edit', params: { id: employee.id } }" class="btn btn-sm btn-primary">
                                        Редактировать</router-link>
                                    <a @click="employeeDelete(employee.id)" class="btn btn-sm btn-danger">Удалить</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    created() {
        if (!User.loggedIn()) {
            this.$router.push({name: '/'})
        } else
            this.getEmployees()
    },
    data() {
        return {
            employees: [],
            searchBox: '',
        }
    },
    computed: {
        filtered() {
            let search_str = this.searchBox
            if (!search_str) {
                return this.employees
            }

            return this.employees.filter( (employee) => {
                for (const prop in employee) {
                    if (prop === 'id' || prop === 'photo') {
                        continue
                    }
                    if (employee[prop] && employee[prop].toString().toUpperCase()
                        .match(search_str.toUpperCase()) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getEmployees() {
            axios.get('api/employee')
                .then( res => this.employees = res.data.data )
                .catch( err => Helper.warn( err.response.data) )
        },
        employeeDelete(id) {

            Swal.fire({
                title: 'Вы уверены?',
                text: "Вы не сможете отменить операцию!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить!',
            }).then( (result) => {

                if (result.isConfirmed) {
                    axios.delete('/api/employee/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удален!',
                                res.data.message,
                                'success'
                            )
                            this.employees = this.employees.filter( (employee) => {
                                return employee.id !== id
                            })
                        })
                        .catch( err => Helper.warn( err.response.data) )
                }
            })
        },
    }
}
</script>

<style scoped>
.photo {
    max-height: 3rem;
    max-width: 5rem;
}
</style>

