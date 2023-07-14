<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'supplier_create' }"> Создать поставщика
                </router-link>
                <span class="px-3"></span>
                <input type="text" id="search" class="form-control-sm col-md-4"
                       v-model="searchBox" placeholder="Поиск">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Список поставщиков</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Имя</th>
                                <th>Фото</th>
                                <th>Номер телефона</th>
                                <th>Название организации</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="supplier in filtered" :key="supplier.id">
                                <td>{{ supplier.name }}</td>
                                <td><img :src="supplier.photo" class="photo" alt="photo"></td>
                                <td>{{ supplier.phone }}</td>
                                <td>{{ supplier.shopname }}</td>
                                <td>
                                    <router-link :to="{ name: 'supplier_edit', params: { id: supplier.id } }" class="btn btn-sm btn-primary">
                                        Редактировать</router-link>
                                    <a @click="supplierDelete(supplier.id)" class="btn btn-sm btn-danger">Удалить</a>
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
            this.getSuppliers()
    },
    data() {
        return {
            suppliers: [],
            searchBox: '',
        }
    },
    computed: {
        filtered() {
            const search_str = this.searchBox;

            if (!search_str) {
                return this.suppliers
            }

            return this.suppliers.filter( (supplier) => {
                for (const key in supplier) {
                    if (key !== 'id' && key !== 'photo' && supplier[key]
                        && supplier[key].toString().toUpperCase().match(search_str.toUpperCase()) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getSuppliers() {
            axios.get('api/supplier')
                .then( res => {
                    const tmp = res.data.data
                    if ( tmp && typeof tmp==='object' ) {
                        this.suppliers = res.data.data
                    } else {
                        Toast.fire({ title: "Пришел неверный ответ!", })
                    }
                })
                .catch( err => {
                    Helper.warn( err.response.data )
                })
        },
        supplierDelete(id) {
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
                    axios.delete('/api/supplier/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удален!',
                                res.data.message,
                                'success'
                            )
                            this.suppliers = this.suppliers.filter( (supplier) => {
                                return supplier.id !== id
                            })
                        })
                        .catch( err => Helper.warn( err.response.data ) )
                }
            })
        },
    }
}
</script>

<style scoped>
.photo {
    max-height: 3rem;
    max-width: 5rem;
}
</style>
