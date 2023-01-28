<template>
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow-sm my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Register</h1>
                                </div>
    <form class="user" @submit.prevent="register">
        <div class="form-group">
            <input type="text" class="form-control" id="exampleInputFirstName" placeholder="Введите ваше полное ФИО" v-model="form.name">
            <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                   placeholder="Введите ваш email" v-model="form.email">
            <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="exampleInputPassword" placeholder="Придумайте пароль" v-model="form.password">
            <small class="text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="exampleInputPasswordRepeat"
                   placeholder="Подтверждение пароля" v-model="form.password_confirmation">
            <small class="text-danger" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Создать уч.запись</button>
        </div>
    </form>
                                <hr>
                                <div class="text-center">
                                    <router-link class="font-weight-bold small" to="/">Уже есть учётная запись?</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="application/javascript">
export default {
    created() {
        if ( User.loggedIn() ) {
            this.$router.push( {name: 'home'} )
        }
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            errors: {}
        }
    },
    methods: {
        register() {
            this.errors = {}
            axios.post('/api/auth/register', this.form)
                .then(res => {
                    User.responseAfterLogin(res)
                    Toast.fire({
                        icon: 'success',
                        title: 'Успешно зарегистрировались!'
                    })
                })
                .catch(err => {
                    this.errors = err.response.data.error
                    Toast.fire({
                        icon: 'error',
                        title: 'Ошибка авторизации!'
                    })
                })
        }
    }
}
</script>

<style scoped>

</style>
