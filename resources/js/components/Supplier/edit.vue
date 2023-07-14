<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'supplier_index' }" class="btn btn-primary col-md-4">
                Все поставщики</router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Редактировать поставщика</h1>
                                </div>
                                <form @submit.prevent="editSupplier" class="user" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="firstName" v-model="form.name" class="form-control"
                                                       placeholder="Введите полное ФИО"
                                                       type="text">
                                                <small v-if="errors.name" class="text-danger">{{
                                                        errors.name[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="email" v-model="form.email" class="form-control"
                                                       placeholder="Введите email"
                                                       type="email">
                                                <small v-if="errors.email" class="text-danger">{{
                                                        errors.email[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="address" v-model="form.address" class="form-control"
                                                       placeholder="Введите адрес"
                                                       type="text">
                                                <small v-if="errors.address" class="text-danger">{{
                                                        errors.address[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}"-->
                                                <input id="phone" v-model="form.phone" class="form-control"
                                                       type="tel">
                                                <small v-if="errors.phone"
                                                       class="text-danger">{{ errors.phone[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="salary" v-model="form.shopname" class="form-control"
                                                       placeholder="Название магазина"
                                                       type="text">
                                                <small v-if="errors.shopname" class="text-danger">{{
                                                        errors.salary[0]
                                                    }}</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="" for="customFile" style="margin-bottom: 0;">
                                                    Логотип поставщика</label>
                                                <input @change="onImageSelect" id="customFile" accept="image/*"
                                                       class="custom-file" type="file">
                                                <small v-if="errors.photo" class="text-danger">{{
                                                        errors.photo[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <img v-show="imagePath" id="previewImage"
                                                     :src="imagePath" alt="image"
                                                     style="max-height: 8em; max-width: 100%;"/>
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
            this.getSupplier()
        }
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                address: null,
                shopname: null,
                phone: null,
                photo: null,
            },
            imagePath: null,
            errors: {},
        }
    },
    methods: {
        getSupplier() {
            let id = this.$route.params.id

            axios.get('/api/supplier/'+id)
                .then( (resp) => {
                    let incoming_data = resp.data.data

                    if (incoming_data.photo != null) {
                        this.imagePath = window.location.origin + incoming_data.photo
                        delete incoming_data.photo
                    }

                    this.form = incoming_data
                })
                .catch( err => {
                    Helper.warn( err.response.data )
                })
        },
        editSupplier() {
            let id = this.$route.params.id
            let uploading_data = this.form

            if (uploading_data.photo === null) {
                delete uploading_data.photo
            }
            axios.put('/api/supplier/'+id, uploading_data)
                .then( res => {
                    if ( id == res.data.data.id ) {
                        Notification.success(res.data.message)
                        this.$router.push({name: 'supplier_index'})

                    } else {
                        Notification.warning()
                    }
                })
                .catch( err => {
                    this.errors = err.response.data.errors ?? {}
                    Helper.warn( err.response.data )
                })
        },
        onImageSelect(event) {
            const file = event.target.files[0];
            if (file.size > 1*1024*1024) {
                Notification.error("Фото должно быть меньше 1Мб.")

            } else {
                let reader = new FileReader()
                reader.onload = event => {
                    this.form.photo = event.target.result
                }
                reader.readAsDataURL(file)

                this.imagePath = URL.createObjectURL(file)
            }
        }
    }
}
</script>

<style scoped>

</style>
