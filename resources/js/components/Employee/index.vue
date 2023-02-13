<template>
    <div>
        <div class="row">
            <router-link :to="{ name: 'employee_create' }" class="btn btn-primary col-md-4 mb-3">
                Создать работника
            </router-link>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">All Employees</h6>
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
                            <tr v-for="employee in employees" :key="employee.id">
                                <td>{{ employee.name }}</td>
                                <td> <img :src="employee.photo" id="emp_photo" alt="photo"> </td>
                                <td>{{ employee.phone }}</td>
                                <td>{{ employee.salary }}</td>
                                <td>{{ employee.joining_date }}</td>
                                <td><a href="" class="btn btn-sm btn-primary">Редактировать</a>
                                    <a href="" class="btn btn-sm btn-danger">Удалить</a> </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <!--Row-->

    </div>
</template>

<script>
export default {
    created() {
        if (!User.loggedIn()) {
            this.$router.push({name: 'login'})
        } else
            this.getEmployees()
    },
    data() {
        return {
            employees: [],
        }
    },
    methods: {
        getEmployees() {
            axios.get('api/employee')
                .then(res => {
                    this.employees = res.data.employees
                    console.log('+')
                    console.log(this.employees)
                })
                .catch(err => console.log(err.response.data.error))
        }
    }
}
</script>

<style scoped>
    #emp_photo{
        max-height: 3rem;
        max-width: fit-content;
    }
</style>
