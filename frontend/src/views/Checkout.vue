<template>
    <div class="content">
        <h1 class="label-background">Checkout</h1>
        <Table :items="cart.items" :filter="'Produtos'" :headers="getHeaders()" :isActionsNeeded="false" />
        <div class="complete-checkout">
            <span class="cart-total label-background">Total: {{ cart.total }}€</span>
            <router-link class="checkout-btn" to="Payment">Pagamento</router-link>
        </div>
    </div>
    
</template>

<script>
import { useCartStore } from '../stores/cart.js'
import Table from '../components/Table.vue'

export default {
    data() {
        return { 
            cart: useCartStore(),
        }
    }, methods: {
        getHeaders() {
            return [
                {
                    name: ''
                }, {
                    name: '#'
                }, {
                    name: 'Nome'
                }, {
                    name: 'Tipo'
                }, {
                    name: 'Descrição'
                }, {
                    name: 'Preço'
                },
            ]
        }
    },
    components: {
        Table,
   }
}

</script>

<style>

.complete-checkout {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    margin: 1em;
    align-items: center;
}

.cart-total {
    margin-right: 1em;
    padding: .5em;
    font-size: large;
    font-weight: bolder;
    text-decoration: underline
}

.checkout-btn {
    background-color: #49a1a8;
    border-radius: 10px;
    border-style: none;
    color: white;
    font-size: large;
    font-weight: bold;
    padding: .5em;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
}

</style>