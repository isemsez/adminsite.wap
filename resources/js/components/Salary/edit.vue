<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'salary' }" class="btn btn-primary col-md-4">
                Все зарплаты
            </router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Обновить запрлату</h1>
                                </div>
                                <form @submit.prevent="salary_update" class="user" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="firstName">Имя</label>
                                                <input id="firstName" v-model="form.name" class="form-control"
                                                       type="text" disabled>
                                                <small v-if="errors.name" class="text-danger">{{
                                                        errors.name[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email">Email</label>
                                                <input id="email" v-model="form.email" class="form-control"
                                                       type="email" disabled>
                                                <small v-if="errors.email" class="text-danger">{{
                                                        errors.email[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="salaryMonth">Выберите месяц</label>
                                                <select class="form-control" id="salaryMonth"
                                                        v-model="form.salary_month">
                                                    <option value="Январь">Январь</option>
                                                    <option value="Февраль">Февраль</option>
                                                    <option value="Март">Март</option>
                                                    <option value="Апрель">Апрель</option>
                                                    <option value="Май">Май</option>
                                                    <option value="Июнь">Июнь</option>
                                                    <option value="Июль">Июль</option>
                                                    <option value="Август">Август</option>
                                                    <option value="Сентябрь">Сентябрь</option>
                                                    <option value="Октябрь">Октябрь</option>
                                                    <option value="Ноябрь">Ноябрь</option>
                                                    <option value="Декабрь">Декабрь</option>
                                                </select>
                                                <small v-if="errors.salary_month" class="text-danger">{{
                                                        errors.salary_month[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="salary">Зарплата</label>
                                                <input id="salary" v-model="form.salary" class="form-control"
                                                       type="text">
                                                <small v-if="errors.salary" class="text-danger">{{
                                                        errors.salary[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block" type="submit">
                                            Внести изменения
                                        </button>
                                    </div>
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
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
        } else {
            this.getSalary()
        }
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                employee_id: null,
                salary_month: null,
                salary: null,
            },
            errors: {},
        }
    },
    methods: {
        getSalary() {
            let id = this.$route.params.id

            axios.get('/api/salary/edit/' + id)
                .then( res => {
                    let data = res.data.data
                    this.form.name = data.employee.name
                    this.form.email = data.employee.email
                    this.form.employee_id = data.employee_id
                    this.form.salary_month = data.salary_month
                    this.form.salary = data.amount
                })
                .catch( err => Helper.warn( err.response.data) )
        },
        salary_update() {
            let id = this.$route.params.id
            axios.patch('/api/salary/update/' + id, this.form)

                .then( res => {
                    if ( res.status === 202 ) {

                        Notification.success(res.data.message)
                        this.$router.push({name: 'salary'})

                    } else {
                        Notification.warning()
                    }
                })

                .catch( err => {
                    this.errors = err.response.data.errors ?? {}
                    Helper.warn( err.response.data )
                })
        }
    }
}
</script>

<style scoped>

</style>

