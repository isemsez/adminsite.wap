<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'expense_index' }" class="btn btn-primary col-md-4">
                Все расходы
            </router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Редактировать статью расхода</h1>
                                </div>
                                <form @submit.prevent="editExpense" class="user" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="details">Статья расходов</label>
                                                <input id="details" v-model="form.details"
                                                       class="form-control" type="text">
                                                <small v-if="errors.details" class="text-danger">{{
                                                        errors.details[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="amount">Сумма</label>
                                                <input id="amount" v-model="form.amount"
                                                       class="form-control" type="text">
                                                <small v-if="errors.amount" class="text-danger">{{
                                                        errors.amount[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expense_date">Дата</label>
                                                <input id="expense_date" v-model="form.expense_date"
                                                       class="form-control" type="date">
                                                <small v-if="errors.expense_date" class="text-danger">{{
                                                        errors.expense_date[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">
                                                Сохранить
                                            </button>
                                        </div>
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
            this.getExpense()
        }
    },
    data() {
        return {
            form: {
                details: null,
                amount: null,
                expense_date: null,
            },
            errors: {},
        }
    },
    methods: {
        getExpense() {
            let id = this.$route.params.id

            axios.get('/api/expense/' + id)
                .then((resp) => {
                    this.form = resp.data.data
                })
                .catch(err => {
                    this.errors = err.response.data.errors ?? this.errors
                    const warning = err.response.data.error ?? "Ошибка!";

                    Toast.fire({
                        icon: "error",
                        title: warning,
                        timer: 5000,
                    })
                    console.log('-', err.response.data)
                })
        },
        editExpense() {
            const id = this.$route.params.id
            axios.put('/api/expense/' + id, this.form)
                .then( res => {

                    if ( id == res.data.data.id ) {
                        Notification.success(res.data.message)
                        this.$router.push({name: 'expense_index'})

                    } else {
                        Notification.warning()
                    }
                })
                .catch(err => {
                    this.errors = err.response.data.errors ?? {}
                    const warning = err.response.data.message ?? "Ошибка!";

                    Toast.fire({
                        icon: "error",
                        title: warning,
                        timer: 5000,
                    })
                    console.log('-', err.response.data)
                })
        }
    }
}
</script>

<style scoped>

</style>
