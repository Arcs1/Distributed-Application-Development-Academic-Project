<template>
    <ul class="menu">

        <router-link to="/"><img src="@/assets/img/logoFinal.png" class="logo"
                @click="sliderIndicator(1)"></router-link>
        <div class="menu-indicator" :style="{ left: positionToMove, width: sliderWidth }"></div>
        <li class="menu-item" v-for="link in links" :key="link.id" @click="sliderIndicator(link.id)"
            :ref="'menu-item_' + link.id">
            <router-link class="menu-link" :to="link.route"
                :class="link.id === selectedIndex ? 'active' : null">{{ link.text }}</router-link>
        </li>

        <ul>
            <li v-if="cartStore.itemCount == 0" class="cart-dropdown" @click="cartToggle = !cartToggle">Order</li>
            <li v-else class="cart-dropdown" @click="cartToggle = !cartToggle">Order
                ({{ cartStore.itemCount }})({{ cartStore.total }}€)</li>
            <Cart v-show="cartToggle" @hideCart="(cartToggle = false)" />
        </ul>

        <li class="menu-item" id="auth-item" v-show="!userStore.user">
            <router-link class="menu-link" to="/login">Login</router-link>
            <router-link class="menu-link" to="/register">Registar</router-link>
        </li>

        <li v-show="userStore.user" class="menu-item" id="auth-item">
            <img id="photo" :src="userStore.userPhotoUrl">
            <div class="dropdown">
                <a class="menu-link">{{ userStore.user?.name ?? 'Anonymous' }}&nbsp;&nbsp;<font-awesome-icon
                        icon="fa-solid fa-caret-down" /></a>
                <div class="dropdown-content">
                    <router-link class="menu-link" to="/profile"><font-awesome-icon
                            icon="fa-solid fa-user" />&nbsp;Profile</router-link>
                    <router-link v-show="userStore.user?.type != 'C'" class="menu-link"
                        to="/dashboard"><font-awesome-icon icon="fa-solid fa-gauge" />&nbsp;DashBoard</router-link>
                    <router-link v-show="userStore.user?.type == 'EM'" class="menu-link"
                        to="/statistics"><font-awesome-icon
                            icon="fa-solid fa-chart-line" />&nbsp;Statistics</router-link>
                    <!-- PASSWORD -->
                    <router-link class="menu-link" to="/changePassword"><font-awesome-icon
                            icon="fa-solid fa-key" />&nbsp;Change Password</router-link>
                    <a class="menu-link" @click.prevent="logout"><font-awesome-icon
                            icon="fa-solid fa-right-from-bracket" />&nbsp;Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <div class="content">
        <router-view></router-view>
    </div>
</template>

<script>
import { useCartStore } from '../stores/cart.js'
import { inject } from 'vue'
import { useUserStore } from '../stores/user.js';
import Cart from './Cart.vue';
import { useRouter, RouterLink, RouterView } from 'vue-router';
export default {

    data() {
        return {
            cartStore: useCartStore(),
            router: useRouter(),
            toast: inject('toast'),
            userStore: useUserStore(),
            cartToggle: false,
            sliderPos: 0,
            selectedElementWidth: 0,
            selectedIndex: 0,
            links: [{
                id: 1,
                icon: "",
                text: "Home",
                route: "/"
            },
            {
                id: 2,
                icon: "",
                text: "Menu",
                route: "/menu"
            },
            {
                id: 3,
                icon: "",
                text: "Quadro de Pedidos",
                route: "/orderBoard"
            },
            ]
        }
    },
    watch: {
        $route(to, from) {
            if ((from.name == 'home') && (to.name == 'menu')) {
                this.sliderIndicator(2)
            }
        }
    },
    methods: {
        sliderIndicator(id) {
            let el = this.$refs[`menu-item_${id}`][0];
            this.sliderPos = el.offsetLeft;
            this.selectedElementWidth = el.offsetWidth;
            this.selectedIndex = id;

        },
        async logout() {
            this.$router.push({ name: 'home' })
            if (await this.userStore.logout()) {
                this.$toast.success('User has logged out of the application.')
                this.sliderIndicator(1)
            } else {
                this.$toast('There was a problem logging out of the application')
            }
        },
        isLogged() {
            try {
                if (useUserStore().userType)
                    return true
            } catch (error) {
                return false
            }
        }
    },
    components: {
        Cart
    },
    computed: {
        positionToMove() {
            return this.sliderPos + "px";
        },
        sliderWidth() {
            return this.selectedElementWidth + "px";
        },
    }
}

</script>

<style scoped>
.dropdown-content router-link,
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: fixed;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

#auth-item {
    padding-top: 10px;
    position: absolute;
    right: 0;
    cursor: pointer;
    padding-right: 61.2px;

}

#photo {
    height: 50px;
    border-radius: 50%;
}

.logo {
    justify-content: center;
    align-items: center;
    width: 100px;
    height: 60px;
}

/* ul */
.menu {
    z-index: 2;
    top: 0;
    left: 0;
    padding: 0;
    margin: 0;
    position: sticky;
    position: absolute;
    background-color: #1d5357;
    display: inline-flex;
    list-style-type: none;
    overflow: visible;
    width: 100%;
}

/* li */
.menu-item {
    display: inline-flex;
    font-weight: bold;
}

.menu-link,
.menu-type {
    padding: 0.75rem 1rem;
    display: inline-flex;
    align-items: center;
    color: #f1faee;
    text-decoration: none;

}

/* slider */
.menu-indicator {
    position: absolute;
    height: 0.25rem;
    background-color: #ffee93;
    bottom: 0;
    left: 0;
    margin: auto;
    width: 3rem;
    transition: all ease 0.5s;
}

.menu-link:hover,
.menu-link:active {
    color: #ffee93;
    background-color: #132238;
}

.cart-dropdown {
    padding: 0.75rem 1rem;
    display: inline-flex;
    cursor: pointer;
    align-items: center;
    background-color: #f1faee;
    color: #1d5357;
    font-weight: bold;
    border-width: 5px;
    border-style: solid;
    border-radius: 10px;
    margin: 2px;
}

.cart {
    align-self: flex-start;
    color: black;
    text-decoration: none;
}
</style>