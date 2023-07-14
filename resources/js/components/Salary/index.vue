<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'given_salary' }">Заплатить зарплату
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
                        <h6 class="m-0 font-weight-bold text-primary">Зарплаты по месяцам</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Месяц</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="salary in filtered" :key="salary.id">
                                <td>{{ salary.salary_month }}</td>
                                <td>
                                    <router-link :to="{ name: 'view_salary', params: { id: salary.salary_month } }" class="btn btn-sm btn-primary">
                                        Смотреть</router-link>
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
            this.getSalaries()
    },
    data() {
        return {
            salaries: [],
            searchBox: '',
        }
    },
    computed: {
        filtered() {
            let search_str = this.searchBox
            if (!search_str) {
                return this.salaries
            }

            return this.salaries.filter( (salary) => {
                for (const prop in salary) {
                    if (prop === 'id') {
                        continue
                    }
                    if (salary[prop] && salary[prop].toString().toUpperCase()
                        .match(search_str.toUpperCase()) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getSalaries() {
            axios.get('/api/salary')
                .then( res => this.salaries = res.data.data )
                .catch( err => Helper.warn( err.response.data ) )
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
                    axios.delete('/api/salary/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удален!',
                                res.data.message,
                                'success'
                            )
                            this.salaries = this.salaries.filter( (salary) => {
                                return salary.id !== id
                            })
                        })
                        .catch( err => Helper.warn( err.response.data ) )
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

