<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'product_create' }"> Создать товар
                </router-link>
                <span class="px-3"></span>
                <input type="text" id="search" class="form-control-sm col-md-4"
                       v-model="searchBox" placeholder="Поиск">
                <label for="search">{{ str_length }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Список товаров</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Наименование</th>
                                <th>Фото</th>
                                <th>Категория</th>
                                <th>Поставщик</th>
                                <th>Розничная цена</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in filtered" :key="product.id">
                                <td>{{ product.product_name }}</td>
                                <td><img :src="product.photo" id="photo" alt="photo"></td>
                                <td>{{ product.category_name }}</td>
                                <td>{{ product.name }}</td>
                                <td>{{ product.selling_price }}</td>
                                <td>
                                    <router-link :to="{ name: 'product_edit', params: { id: product.id } }" class="btn btn-sm btn-primary">
                                        Редактировать</router-link>
                                    <a @click="productDelete(product.id)" class="btn btn-sm btn-danger">Удалить</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    created() {
        if (! User.loggedIn() ) {
            this.$router.push({name: '/'})
        } else
            this.getProducts()
    },
    data() {
        return {
            products: [],
            searchBox: '',
        }
    },
    computed: {
        str_length() {
            const length = this.searchBox.length;
            return length ? length : ''
        },
        filtered() {
            const search_str = this.searchBox;

            if (!search_str) {
                return this.products
            }

            return this.products.filter( (product) => {
                for (const key in product) {
                    if (key !== 'id' && key !== 'photo' && product[key]
                        && product[key].toString().match(search_str) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getProducts() {
            axios.get('api/product')
                .then(res => {
                    const tmp = res.data.data
                    if ( tmp && typeof tmp==='object' ) {
                        this.products = res.data.data
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Пришел неверный ответ!",
                            timer: 5000,
                        })
                    }
                })
                .catch( err => console.log(err.response.data.error))
        },
        productDelete(id) {
            Swal.fire({
                title: 'Вы уверены?',
                text: "Вы не сможете отменить операцию!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить!',
            }).then( (res) => {
                if (res.isConfirmed) {
                    axios.delete('/api/product/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удален!',
                                res.data.message,
                                'success'
                            )
                            this.products = this.products.filter( (product) => {
                                return product.id !== id
                            })
                        })
                        .catch( (err) => {
                            const warning = err.response.data.message
                            Toast.fire({
                                icon: "error",
                                title: warning,
                                timer: 5000,
                            })
                            console.log(err.response.data)
                        })
                }
            })
        },
    }
}
</script>

<style scoped>
#photo {
    max-height: 3rem;
    max-width: 5rem;
}
</style>
