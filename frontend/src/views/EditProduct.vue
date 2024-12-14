<script>
import { inject } from '@vue/runtime-core';
import createForm from '../components/CreateForm.vue'
export default {

    data() {
        return {
            axios: inject('axios'),
            product: [],
            baseURL: inject("serverBaseUrl"),
        }
    },
    components: {
        createForm
    },
    computed: {
        formItems() {
            return [
                {
                    label: 'Produto',
                    value: this.product.name,
                    type: 'text'
                },
                {
                    label: 'Preço',
                    value: this.product.price,
                    type: 'Number'
                }

            ];
        },
        textAreaItems() {
            return [
                {
                    label: 'Descrição',
                    value: this.product.description
                }

            ];
        },
        selectItems() {
            return [
                {
                    label: 'Tipo',
                    value: this.product.type
                },
            ];
        },
        options() {
            return [
                {
                    value: 'drink',
                    option: 'Drink'
                },
                {
                    value: 'dessert',
                    option: 'Dessert'
                },
                {
                    value: 'hot dish',
                    option: 'Hot Dish'
                },
                {
                    value: 'cold dish',
                    option: 'Cold Dish'
                },
            ];
        }
    },
    methods: {
        async getProduct() {
            let response = await this.axios.get('products/' + this.$route.params.id)
            this.product = response.data.data;
            console.log(response.data.data)
        }, async editProduct(array) {
            let productInfo = new FormData
            console.log(array)
            productInfo.append('name', array[0].value)
            productInfo.append('description', array[4][0].value)
            productInfo.append('price', array[1].value)
            productInfo.append('type', array[2].text)
            productInfo.append('photo', array[3])




            const data = await this.axios.post('products/' + this.$route.params.id + '/update', productInfo);
            console.log(data)
            this.$router.push('/dashboard');
        }

    },
    mounted() {
        this.getProduct();
    }
}


</script>


<template>
    <img v-show="this.product.photo_url" class="photo"
        :src="baseURL + '/storage/products/' + this.product.photo_url" alt="">
    <div class="content-form">
        <createForm :formItems="formItems" :isSelectedRequired="true" :selectItems="selectItems" :options="options"
            :isFileUploadRequired="true" :title="'Editar Produto'" :isTextAreaRequired="true"
            :textAreaItems="textAreaItems" @createItem="editProduct" :selected="this.product.type"></createForm>
    </div>
</template>


<style scoped>
.content-form {

    border-radius: 4px;
    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -300px;
    margin-left: -200px;
}

.photo {
    width: 150px;
    height: 150px;
    margin-left: 200px;
    margin-top: 100px;
    border-radius: 0%;
    border: 5px solid #555;
}
</style>