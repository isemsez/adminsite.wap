<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'expense_create' }">Добавить статью расхода
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
                        <h6 class="m-0 font-weight-bold text-primary">Список расходов</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Детали</th>
                                <th>Сумма</th>
                                <th>Дата</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="expense in filtered" :key="expense.id">
                                <td>{{ expense.details }}</td>
                                <td>{{ expense.amount }}</td>
                                <td>{{ expense.expense_date }}</td>
                                <td>
                                    <router-link :to="{ name: 'expense_edit', params: { id: expense.id } }" class="btn btn-sm btn-primary">
                                        Редактировать</router-link>
                                    <a @click="expenseDelete(expense.id)" class="btn btn-sm btn-danger">Удалить</a>
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
        if (! User.loggedIn() ) {
            this.$router.push({name: '/'})
        } else
            this.getExpenses()
    },
    data() {
        return {
            expenses: [],
            searchBox: '',
        }
    },
    computed: {
        filtered() {
            const search_str = this.searchBox;

            if (!search_str) {
                return this.expenses
            }

            return this.expenses.filter( (expense) => {
                for (const key in expense) {
                    if (key !== 'id' && expense[key]
                        && expense[key].toString().toUpperCase().match(search_str.toUpperCase()) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getExpenses() {
            axios.get('api/expense')
                .then(res => {
                    const tmp = res.data.data
                    if ( tmp && typeof tmp==='object' ) {
                        this.expenses = res.data.data
                    } else {
                        Toast.fire({ title: "Пришел неверный ответ!" })
                    }
                })
                .catch( err => Helper.warn( err.response.data ) )
        },
        expenseDelete(id) {
            Swal.fire({
                title: 'Вы уверены?',
                text: "Вы не сможете отменить операцию!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить!',
            }).then( (res) => {
                if (res.isConfirmed) {
                    axios.delete('/api/expense/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удалён!',
                                res.data.message,
                                'success'
                            )
                            this.expenses = this.expenses.filter( (expense) => {
                                return expense.id !== id
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

</style>
