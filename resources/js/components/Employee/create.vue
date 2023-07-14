<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'employee_index' }" class="btn btn-primary col-md-4">
                Все работники</router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Создать нового работника</h1>
                                </div>
                                <form class="user" enctype="multipart/form-data" @submit.prevent="createEmployee">
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
                                                <!--                                                       pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}"-->
                                                <input id="phone" v-model="form.phone"
                                                       @input="tel_format"
                                                       class="form-control"
                                                       type="tel">
                                                <small v-if="errors.phone"
                                                       class="text-danger">{{ errors.phone[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="salary" v-model="form.salary" class="form-control"
                                                       placeholder="Введите зарплату"
                                                       type="text">
                                                <small v-if="errors.salary" class="text-danger">{{
                                                        errors.salary[0]
                                                    }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-0">
                                                    <input id="joiningDate" v-model="form.joining_date" class="form-control"
                                                           type="date">
                                                    <label for="joiningDate" style="margin-bottom: 0; font-size: .9rem;">
                                                        Когда устроился(ась) на работу</label>
                                                    <small v-if="errors.joining_date"
                                                           class="text-danger">{{
                                                            errors.joining_date[0]
                                                        }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                <div class="col-md-6">
                    <label for="customFile" style="margin-bottom: 0;">Фото работника</label>
                    <input id="customFile" @change="onImageSelect" accept="image/*"
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
                                        <button class="btn btn-primary btn-block" type="submit">Создать уч.запись
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
            this.$router.push({ name: '/' })
        }
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                address: null,
                salary: null,
                joining_date: null,
                phone: '+7(9..',
                photo: null,
            },
            imagePath: null,
            errors: {},
        }
    },
    methods: {
        createEmployee() {
            axios.post('/api/employee', this.form)
                .then( res => {

                    if ( res.status == 201 ) {
                        this.$router.push({ name: 'employee_index' })
                        Notification.success()

                    } else {
                        Notification.warning()
                        this.errors = {}
                    }
                })
                .catch(err => {
                    this.errors = err.response.data.errors ?? {}
                    Helper.warn( err.response.data )
                })
        },
        onImageSelect(event) {
            const file = event.target.files[0];

            if (file.size > 1048576) {
                Notification.error('Фото должно быть меньше 1Мб.')
            } else {
                let reader = new FileReader()
                reader.onload = event => {
                    this.form.photo = event.target.result
                }
                reader.readAsDataURL(file)

                this.imagePath = URL.createObjectURL(file)
            }
        },
        tel_format() {
            let x = this.form.phone
            x = x.replace(/\D/g,'').replace(/^7/,'')
                .match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/)
            x = !x[2] ? "("+x[1] + (x[1].length === 3 ? ')' : '')
                : "("+x[1]+")"+x[2] + (x[2].length===3 ? '-' : '')
                + (!x[3] ? "" : x[3] + (x[3].length===2 ? '-' : '')
                    + (!x[4] ? '' : x[4]) )
            this.form.phone = '+7' + x
        }
    }
}
</script>

<style scoped>

</style>
