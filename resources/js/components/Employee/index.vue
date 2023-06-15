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
                <label for="search">{{ str_length }}</label>
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
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Phone number</th>
                                <th>Salary</th>
                                <th>Joining_date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="employee in filtered" :key="employee.id">
                                <td>{{ employee.name }}</td>
                                <td><img :src="employee.photo" id="emp_photo" alt="photo"></td>
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
        str_length() {
            const length = this.searchBox.length;
            return length ? length : ''
        },
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
                    if (employee[prop] && employee[prop].toString().match(search_str)) {
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

                .then( res => {
                    this.employees = res.data.data
                })

                .catch( err => {
                    const message = err.response.data.message;
                    Toast.fire({icon: "error", title: message, timer: 5000,} )

                    console.log(err.response.data.error);
                })
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
                        .catch( (err) => {
                            const warning = err.response.data.message
                            Toast.fire({icon: "error", title: warning, timer: 5000,} )

                            console.log(err.response.data)
                        })
                }
            })
        },
    }
}
</script>

<style scoped>
#emp_photo {
    max-height: 3rem;
    max-width: fit-content;
}
</style>

