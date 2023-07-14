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
                                    <h1 class="h4 text-gray-900 mb-4">Создать новую категорию</h1>
                                </div>
                                <form class="user" enctype="multipart/form-data" @submit.prevent="createCategory">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="category_name" v-model="form.category_name" class="form-control"
                                                       placeholder="Название категории"
                                                       type="text">
                                                <small v-if="errors.category_name" class="text-danger">{{
                                                        errors.category_name[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block" type="submit">
                                            Создать категорию</button>
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
        if (! User.loggedIn() ) {
            this.$router.push({ name: '/' })
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
        createCategory() {
            axios.post('/api/category', this.form)
                .then( () => {

                    if (res.status == 201) {
                        this.$router.push({name: 'category_index'})
                        Notification.success()

                    } else {
                        Notification.warning()
                        this.errors = {}
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
