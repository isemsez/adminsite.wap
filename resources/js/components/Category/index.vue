<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <router-link class="btn btn-primary col-sm-5 col-md-4 col-lg-2"
                             :to="{ name: 'category_create' }"> Создать категорию
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
                        <h6 class="m-0 font-weight-bold text-primary">Список категорий</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Название</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="category in filtered" :key="category.id">
                                <td>{{ category.category_name }}</td>
                                <td>
                                    <router-link :to="{ name: 'category_edit', params: { id: category.id } }" class="btn btn-sm btn-primary">
                                        Редактировать</router-link>
                                    <a @click="categoryDelete(category.id)" class="btn btn-sm btn-danger">Удалить</a>
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
            this.getCategories()
    },
    data() {
        return {
            categories: [],
            searchBox: '',
        }
    },
    computed: {
        filtered() {
            const search_str = this.searchBox;

            if (!search_str) {
                return this.categories
            }

            return this.categories.filter( (category) => {
                for (const key in category) {
                    if (key !== 'id' && category[key]
                        && category[key].toString().toUpperCase().match(search_str.toUpperCase()) ) {
                        return true
                    }
                }
                return false
            })
        },
    },
    methods: {
        getCategories() {
            axios.get('api/category')
                .then(res => {
                    const tmp = res.data.data
                    if ( tmp && typeof tmp==='object' ) {
                        this.categories = res.data.data
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
        categoryDelete(id) {
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
                    axios.delete('/api/category/' + id)
                        .then( (res) => {
                            Swal.fire(
                                'Удалена!',
                                res.data.message,
                                'success'
                            )
                            this.categories = this.categories.filter( (category) => {
                                return category.id !== id
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

</style>
