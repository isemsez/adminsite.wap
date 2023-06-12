<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'category_index' }" class="btn btn-primary col-md-4">
                Все категории</router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Редактировать поставщика</h1>
                                </div>
                                <form @submit.prevent="editCategory" class="user" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="category_name" v-model="form.category_name" class="form-control"
                                                       placeholder="Название"
                                                       type="text">
                                                <small v-if="errors.name" class="text-danger">{{
                                                        errors.name[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block" type="submit">
                                            Сохранить</button>
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
            this.$router.push({ name: '/' })
        } else {
            this.getCategory()
        }
    },
    data() {
        return {
            form: {
                category_name: null,
            },
            errors: {},
        }
    },
    methods: {
        getCategory() {
            let id = this.$route.params.id
            axios.get('/api/category/'+id)
                .then( (resp) => {
                    this.form = resp.data.data
                })
                .catch( err => {
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
        editCategory() {
            const id = this.$route.params.id
            axios.put('/api/category/'+id, this.form)
                .then( (res) => {
                    Notification.success(res.data.message)
                    this.$router.push({ name: 'category_index' })
                })
                .catch(err => {
                    this.errors = err.response.data.errors ?? this.errors
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
