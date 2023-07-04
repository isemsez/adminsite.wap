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
                                    <h1 class="h4 text-gray-900 mb-4">Новая статья расходов</h1>
                                </div>
                                <form class="user" enctype="multipart/form-data" @submit.prevent="createExpense">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input id="details" v-model="form.details" class="form-control"
                                                       placeholder="Описание статьи расхода"
                                                       type="text">
                                                <small v-if="errors.details" class="text-danger">{{
                                                        errors.details[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input id="amount" v-model="form.amount" class="form-control"
                                                       placeholder="Сумма"
                                                       type="text">
                                                <small v-if="errors.amount" class="text-danger">{{
                                                        errors.amount[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input id="expense_date" v-model="form.expense_date"
                                                       class="form-control"
                                                       placeholder="Дата"
                                                       type="date">
                                                <small v-if="errors.expense_date" class="text-danger">{{
                                                        errors.expense_date[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block" type="submit">
                                            Создать статью расхода
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
        createExpense() {
            axios.post('/api/expense', this.form)
                .then((res) => {

                    if (res.status === 201) {
                        this.$router.push({name: 'expense_index'})
                        Notification.success()

                    } else {
                        Notification.warning()
                        this.errors = {}
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
