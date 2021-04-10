<template>
    <div class="container pb-5">
        <div class="row align-items-center justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Products</h4>

                        <form @submit.prevent="importCSV" enctype="multipart/form-data">
                            <label>
                                <span title="Change Image" class="btn btn-primary">
                                    Choose File
                                </span>
                                <input type="file" ref="selectedFile" @change="processFile" accept=".csv" hidden>
                            </label>

                            <button type="submit" class="btn btn-success">Upload</button>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="text-center" v-if="loading">Loading...</div>

                        <table class="table table-hover" v-else>
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Brand</th>
                                    <th scope="col">Group</th>
                                    <th scope="col">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="products.length > 0">
                                    <tr v-for="(product, index) in products" :key="index">
                                        <th scope="row">{{ index+1 }}</th>
                                        <td>{{ product.title }}</td>
                                        <td>{{ product.category.title }}</td>
                                        <td>{{ product.brand.title }}</td>
                                        <td>{{ product.group.title }}</td>
                                        <td>{{ product.type }}</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6" class="text-center">No data found</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <Pagination
                            v-if="pagination.last_page > 1"
                            :pagination="pagination"
                            :offset="10"
                            @paginate="getProducts()"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Pagination from "../Pagination";

    export default {
        name: "Index",
        components: {Pagination},
        data() {
            return {
                products: [],
                loading: false,
                pagination : {
                    current_page : 1,
                },

                csv_file: '',
            }
        },
        mounted() {
            this.getProducts()
        },

        methods: {
            getProducts() {
                this.loading = true

                axios.get(`products?page=${this.pagination.current_page}`)
                    .then(response => {
                    this.products = response.data.data
                    this.pagination = response.data.meta;

                    this.loading = false
                })
            },

            processFile() {
                this.csv_file = this.$refs.selectedFile.files[0];
            },

            importCSV() {
                let formData = new FormData();

                formData.append('products', this.csv_file);

                axios.post('products/import', formData).then(response => {
                          console.log(response)
                      })
            }
        }
    }
</script>
