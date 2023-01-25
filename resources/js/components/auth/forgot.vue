<template>
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow-sm my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                 <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Forgot your passowrd?</h1>
                                </div>
    <form class="user" @submit.prevent="forgot">
        <div class="form-group">
            <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                   placeholder="Enter Your Existing Email" v-model="form.email">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Remind me</button>
        </div>
    </form>
                                <hr>
                                <div class="text-center">
                                    <router-link class="font-weight-bold small" to="/register">Create an Account</router-link>
                                </div>
                                <div class="text-center">
                                    <router-link class="font-weight-bold small" to="/">Back to Login</router-link>
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
    data() {
        return {
            form: {
                email: null,
            }
        }
    },
    methods: {
        forgot() {
            this.$router.push({ name: '/' })
        }
    }
}
</script>

<style scoped>

</style>
