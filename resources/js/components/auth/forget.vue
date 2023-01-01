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
    <form class="user">
        <div class="form-group">
            <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                   placeholder="Enter Your Existing Email">
        </div>
        <div class="form-group">
            <a href="/" class="btn btn-primary btn-block">Remind me</a>
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

<script>
export default {
    name: "login"
}
</script>

<style scoped>

</style>
