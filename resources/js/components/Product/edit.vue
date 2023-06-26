<template>

    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <router-link :to="{ name: 'product_index' }" class="btn btn-primary col-md-4">
                Все товары</router-link>
            <div class="card shadow-sm mt-2 mb-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Редактировать товар</h1>
                                </div>
                                <form @submit.prevent="editProduct" class="user" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="product_name" class="form-label">Название товара</label>
                                                <input id="product_name" v-model="form.product_name" class="form-control"
                                                       placeholder="Название товара"
                                                       type="text">
                                                <small v-if="errors.product_name" class="text-danger">{{ errors.product_name[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="product-code" class="form-label">Код товара</label>
                                                <input id="product-code" v-model="form.product_code" class="form-control"
                                                       placeholder="Код товара"
                                                       type="text">
                                                <small v-if="errors.product_code" class="text-danger">{{ errors.product_code[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="category-dd" class="form-label">Категория</label>
                                                <select class="form-control" id="category-dd" v-model="form.category_id">
                                                    <option v-for="category in categories" :value="category.id">
                                                        {{ category.category_name }}
                                                    </option>
                                                </select>
                                                <small v-if="errors.category_id" class="text-danger">{{ errors.category_id[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="supplier-dd" class="form-label">Поставщик</label>
                                                <select class="form-control" id="category-dd" v-model="form.supplier_id">
                                                    <option v-for="supplier in suppliers" :value="supplier.id">
                                                        {{ supplier.shopname }}
                                                    </option>
                                                </select>
                                                <small v-if="errors.supplier_id" class="text-danger">{{ errors.supplier_id[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"><div class="row">
                                            <div class="col-md-4">
                                                <label for="root" class="form-label">Root</label>
                                                <input id="root" v-model="form.root" class="form-control"
                                                       placeholder="root"
                                                       type="text">
                                                <small v-if="errors.root" class="text-danger">{{ errors.root[0] }}</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="buying-price" class="form-label">Закупочная цена</label>
                                                <input id="buying-price" v-model="form.buying_price" class="form-control"
                                                       placeholder="Закупочная цена"
                                                       type="number">
                                                <small v-if="errors.buying_price" class="text-danger">{{ errors.buying_price[0] }}</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="selling-price" class="form-label">Розничная цена</label>
                                                <input id="selling-price" v-model="form.selling_price" class="form-control"
                                                       placeholder="Розничная цена"
                                                       type="number">
                                                <small v-if="errors.selling_price" class="text-danger">{{ errors.selling_price[0] }}</small>
                                            </div>
                                    </div></div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="buying-date" class="form-label">Дата закупки</label>
                                                <input id="buying-date" v-model="form.buying_date" class="form-control"
                                                       placeholder="Дата закупки"
                                                       type="date">
                                                <small v-if="errors.buying_date" class="text-danger">{{ errors.buying_date[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="product-quantity" class="form-label">Количество товара</label>
                                                <input id="product-quantity" v-model="form.product_quantity" class="form-control"
                                                       placeholder="Количество товара"
                                                       type="number">
                                                <small v-if="errors.product_quantity" class="text-danger">{{ errors.product_quantity[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label" for="customFile" >
                                                    Фото товара</label>
                                                <input @change="onImageSelect" id="customFile" accept="image/*"
                                                       class="custom-file" type="file">
                                                <small v-if="errors.photo" class="text-danger">{{ errors.photo[0] }}</small>
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
            this.getProduct()
        }
    },
    data() {
        return {
            form: {
                product_name: null,
                category_id: null,
                supplier_id: null,
                product_code: null,
                buying_price: null,
                selling_price: null,
                buying_date: null,
                buying_quantity: null,
                photo: null,
            },
            imagePath: null,
            errors: {},
            categories: {},
            suppliers: {},
        }
    },
    methods: {
        getProduct() {
            let id = this.$route.params.id
            axios.get('/api/product/'+id)
                .then( (resp) => {
                    let product_data = resp.data.data.product
                    if (product_data.photo != null) {
                        this.imagePath = window.location.origin + product_data.photo
                        delete product_data.photo
                    }
                    this.form = product_data

                    this.categories = resp.data.data.categories
                    this.suppliers = resp.data.data.suppliers
                })
                .catch( err => {
                    this.errors = err.response.data.errors ?? this.errors
                    const warning = err.response.data.message ?? "Ошибка!";
                    Toast.fire({
                        icon: "error",
                        title: warning,
                        timer: 5000,
                    })
                    console.log('-', err.response.data)
                })
        },
        editProduct() {
            let id = this.$route.params.id
            let uploading_data = this.form
            if (uploading_data.photo === null) {
                delete uploading_data.photo
            }
            axios.put('/api/product/'+id, uploading_data)
                .then( (res) => {
                    Notification.success(res.data.message)
                    this.$router.push({ name: 'product_index' })
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
