<script setup>
import { inject } from 'vue';
import { useCartStore } from '../stores/cart.js'
const cart = useCartStore()

const baseURL = inject("serverBaseUrl")

const emit = defineEmits(['hideCart'])

function isEmpty() {
  return cart.itemCount == 0 ? true : false
}

const hideCart = ()=>{emit('hideCart')}
</script>

<template>
  <div class="cart">
    <span v-if="isEmpty()" style="color:white">No products</span>
    <div v-for="(item, index) in cart.items" :key="index" class="cart-item">
      <img class="item-img" :src="baseURL + '/storage/products/' + item.photo_url"
        alt="Prod.Image" />
      <h3 class="item-name">{{ item.name }}</h3>
      <span class="item-price">{{ item.price }}€</span>
      <button class="remove-item" @click.prevent="cart.removeItem(item)">X</button>
    </div>
    <label class="cart-total" v-show="(!isEmpty())" >Total: {{cart.total}}€</label>
    <router-link class="checkout-btn" @click="hideCart" v-show="(!isEmpty())"  to="/checkout">Checkout</router-link>
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
  width:50%;
  height: calc(100% + 10px);
  margin-left: 50px;
  margin-top: -15px;
  background-color: darkred;
  border-radius: 10px;
  border-style: none;
  color:white;
}

.checkout-btn{
  width: 75%;
  background-color: #49a1a8;
  border-radius: 10px;
  border-style: none;
  color:white;
  margin-top: .6em;
  font-size: large;
  font-weight: bold;
  padding: .5em;
  text-align: center;
  text-decoration: none;
}

.cart-total{
  align-self: flex-start;
  color: #fff;
  font-size: 1.3em;
}
</style>