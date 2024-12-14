<script setup>
import { inject, onMounted, ref } from 'vue';

const baseURL = inject("serverBaseUrl")

const props = defineProps({
    order: {
        type: Object
    }
})

const axios = inject('axios')
const orderItems = ref()
const itemName = ref()

function isEmpty(a) {
    console.log(a)
}

function getName(product_id) {
    // console.log(product_id)
    fetchItemName(product_id)
}

async function fetchOrderItems(item) {
    let response = await axios.get('order_items/' + item.id)
    orderItems.value = response.data.data;
}

async function fetchItemName(product_id) {
    let response = await axios.get('products/' + product_id)
    itemName.value = response.data.data.name
}

onMounted(() => {
    fetchOrderItems(props.order)
})
</script>

<template>
    <div class="cart">
        <button class="remove-item" @click.prevent="isEmpty(orderItems)">X</button>

        <div v-for="item in orderItems" :key="item" class="cart-item">
            <!-- <img class="item-img" :src="baseURL + '/storage/products/' + item.photo_url"
                alt="Prod.Image" /> -->

            <button class="remove-item" @click.prevent="fetchItemName(item.product_id)">X</button>
            <span class="item-name"> {{ getName(item.product_id) }} </span>
            <!--getName(item.product_id) -->
            <span class="item-price">{{ item.price }}€</span>
            <!-- <button class="remove-item" @click.prevent="cart.removeItem(item)">X</button> -->
        </div>
        <!-- <router-link class="checkout-btn" v-if="!isEmpty()" to="/checkout">Checkout</router-link> -->
    </div>
</template>

<style scoped>
.cart {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 400px;
    height: auto;
    background-color: #2f6f74;
    box-shadow: 0px 0px 10px rgba(73, 74, 78, 0.1);
    border-radius: 7px;
    box-sizing: border-box;
    padding: 1em .5em;
    position: absolute;
    z-index: 2;
}

.cart-item {
    width: 100%;
    height: 130px;
    background-color: #fff;
    box-sizing: border-box;
    border-radius: 5px;
    padding: 0 .5em;
    margin-top: .3em;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
}

.item-img {
    max-width: 90%;
    grid-column: 1/2;
    grid-row: 1/4;
    align-self: center;
}

.item-name {
    grid-column: 2/4;
    grid-row: 1/2;
    font-weight: bold;
}

.remove-item {
    font-weight: bolder;
    width: 50%;
    height: calc(100% + 10px);
    margin-left: 50px;
    margin-top: -15px;
    background-color: darkred;
    border-radius: 10px;
    border-style: none;
    color: white;
}

.checkout-btn {
    width: 75%;
    background-color: #49a1a8;
    border-radius: 10px;
    border-style: none;
    color: white;
    margin-top: .6em;
    font-size: large;
    font-weight: bold;
    padding: .5em;
    text-align: center;
    text-decoration: none;
}
</style>