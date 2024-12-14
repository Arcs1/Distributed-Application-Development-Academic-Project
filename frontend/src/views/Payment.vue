<template>
    <div class="content">
        <div class="processing" v-show="processing">
            <p>Aguarde...</p>
        </div>
        <div class="form-content">
            <h1 class="label-background">Método de Pagamento</h1>
            <form>
                <div class="form-group background">
                    <label>Método: </label>
                    <select class="form-control" id="selectedItem" name="selectedItem" v-model="selectedOption">
                        <option v-for="item in options" :key="item" :value="item.value"
                            :selected="(item.value == selectedOption)">
                            {{ item.option }}</option>
                    </select>
                </div>
                <div class="form-group background" v-show="selectedOption == 'visa'">
                    <label>Número do Cartão: </label>
                    <input class="form-control" type="text" v-model="paymentRef">
                </div>
                <div class="form-group background" v-show="selectedOption == 'mbway'">
                    <label>Telemóvel: </label>
                    <input class="form-control" type="text" v-model="paymentRef">
                </div>
                <div class="form-group background" v-show="selectedOption == 'paypal'">
                    <label>Email: </label>
                    <input class="form-control" type="text" v-model="paymentRef">
                </div>
                <div class="form-group background" v-if="userPoints > 10">
                    <label>Pontos: </label>
                    <select class="form-control" id="selectedItem" name="selectedItem" v-model="selectedPoints">
                        <option v-for="(n, i) in Math.trunc(userPoints / 10) + 1" :key="i" :value="i * 10"
                            :selected="(i == selectedPoints)">
                            {{ i * 10 + '(€' + 5 * i + ')' }}</option>
                    </select>
                </div>
                <div class="complete-checkout">
                    <p class="label-background">A pagar: {{ (cart.total - pointValue).toFixed(2) }}€ ({{ pointValue }}€
                        pago
                        com pontos)</p>
                    <input class="checkout-btn" type="submit" value="Completar" @click.prevent="completeOrder">
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { useCartStore } from "../stores/cart.js";
import { useUserStore } from "../stores/user.js";
import { inject } from '@vue/runtime-core'
import router from '../router';

export default {
    data() {
        return {
            selectedOption: "visa",
            paymentRef: "",
            axios: inject('axios'),
            toast: inject('toast'),
            socket: inject("socket"),
            cart: useCartStore(),
            userStore: useUserStore(),
            selectedPoints: 0,
            processing: false
        }
    }, methods: {
        async completeOrder() {
            this.processing = true
            try {
                await this.processPayment()
                await this.createOrder()
                this.$toast.success('Order successfully requested')
                this.cart.clear()
                if (this.userStore.user != null) {
                    this.userStore.loadUser()
                }
                this.processing = false
                router.push({ name: 'home' })

            } catch (error) {
                console.log(error)
            }
            this.processing = false
        },

        async processPayment() {
            try {
                await this.axios.post('https://dad-202223-payments-api.vercel.app/api/payments', this.paymentInfo)
            } catch (error) {
                this.$toast.error('Error processing payment: ' + error.response.data.message)
                throw Error(error.response.data.message)
            }
        },
        async createOrder() {

            var products = []
            var hasHotDishes = false

            this.cart.items.forEach((element) => { products.push(element.id) })
            this.cart.items.forEach((element) => {
                if (element.type == 'hot dish') {
                    hasHotDishes = true
                }
            })

            const order = {
                'points_used_to_pay': this.selectedPoints,
                'payment_type': this.selectedOption,
                'payment_reference': this.paymentRef,
                'products': products
            }

            try {
                var e = await this.axios.post('orders/create', order)
                if (hasHotDishes) {
                    this.socket.emit('newOrder', e.data) //orderId
                }else {
                    this.socket.emit('orderIsReadyWithoutHotDish', e.data) //orderId
                }
                // console.log(e.data)
            } catch (error) {
                this.$toast.error('Error creating order: ' + error.response.data.message)
                await this.axios.post('https://dad-202223-payments-api.vercel.app/api/refunds', this.paymentInfo)
                throw new Error("Couldn't create order")
            }
        },
    },
    computed: {
        userPoints() {
            let points = this.userStore.customer?.points ?? 0
            if (points / 2 > this.cart.total) {
                points = Math.trunc(this.cart.total * 2)
            }
            return points
        },
        paymentInfo() {
            return {
                'type': this.selectedOption,
                'reference': this.paymentRef,
                'value': parseFloat((this.cart.total - this.pointValue).toFixed(2))
            }
        },
        pointValue() {
            return this.selectedPoints / 2
        },
        options() {
            return [
                {
                    value: 'visa',
                    option: 'Visa'
                },
                {
                    value: 'mbway',
                    option: 'MBWay'
                },
                {
                    value: 'paypal',
                    option: 'PayPal'
                },
            ];
        },
    }, mounted() {
        this.selectedOption = this.userStore.customer?.default_payment_type.toLowerCase() ?? 'visa'
        this.paymentRef = this.userStore.customer?.default_payment_reference ?? ''
    }
}
</script>

<style scope>
.form-control {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.background {
    background-color: #49a1a8;
    padding: 5px;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    margin: 5px;
    width: max-content;
}

.label-background {
    text-shadow:
        -3px -3px 0 #49a1a8,
        0 -3px 0 #49a1a8,
        3px -3px 0 #49a1a8,
        3px 0 0 #49a1a8,
        3px 3px 0 #49a1a8,
        0 3px 0 #49a1a8,
        -3px 3px 0 #49a1a8,
        -3px 0 0 #49a1a8;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    margin: 5px;
}

.processing {
    display: flex;
    position: absolute;
    width: 102%;
    height: 102%;
    background-color: black;
    color: white;
    opacity: 80%;
    z-index: 1;
    border-radius: 10px;
    align-items: center;
    justify-content: center;
    font-size: 25pt;
}
</style>