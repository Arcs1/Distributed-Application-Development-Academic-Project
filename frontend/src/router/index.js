import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import QuadroPedidos from '../views/QuadroPedidos.vue'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import DashBoard from '../views/DashBoard.vue'
import CreateProduct from '../views/CreateProduct.vue'
import CreateUser from '../views/CreateUser.vue'
import EditUser from '../views/EditUser.vue'
import EditProduct from '../views/EditProduct.vue'
import { useUserStore } from '../stores/user'
import { useCartStore } from '../stores/cart'
import Checkout from '../views/Checkout.vue'
import RouteRedirector from '../views/RouteRedirector.vue'
import Profile from '../views/Profile.vue'
import ChangePassword from '../views/ChangePassword.vue'
import Payment from '../views/Payment.vue'
import Statistics from '../views/Statistics.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/menu',
      name: 'menu',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/MenuView.vue')
    },
    {
      path: '/orderBoard',
      name: 'quadropedidos',
      component: QuadroPedidos
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },{
      path:'/register',
      name:'register',
      component:Register
    },{
      path:'/dashboard',
      name:'dashboard',
      component:DashBoard
    },{
      path:'/createProduct',
      name:'createProduct',
      component:CreateProduct
    },
    {
      path:'/createUser',
      name:'createUser',
      component:CreateUser
    },
    {
      path:'/users/:id/edit',
      name:'editUser',
      component:EditUser
    },
    {
      path:'/products/:id/edit',
      name:'editProduct',
      component:EditProduct
    },
    {
      path: '/checkout',
      name: 'Checkout',
      component: Checkout,
      beforeEnter: (to, from,next) => {
        const cart = useCartStore()
        // reject the navigation
        if(cart.itemCount<1){
          next({name:'home'})
        }else{
          next()
        }
      }
    },
    {
      path: '/payment',
      name: 'Payment',
      component: Payment,
      beforeEnter: (to, from,next) => {
        // reject the navigation
        if(from.name!="Checkout"){
          next({name:'home'})
        }else{
          next()
        }
      }
    },
    {
      path:'/redirect/:redirectTo',
      name:'Redirect',
      component:RouteRedirector,
      props: route =>({ redirectTo: route.params.redirectTo})
    },
    {
      path:'/profile',
      name:'Profile',
      component:Profile
    },
    {
      path:'/changePassword',
      name: 'ChangePassword',
      component:ChangePassword
    },{
      path:'/statistics',
      name:'Statistics',
      component:Statistics
    }
  ]
})

let handlingFirstRoute = true

router.beforeEach((to,from,next)=>{

  if(handlingFirstRoute){
    handlingFirstRoute = false
    next({name: 'Redirect', params: {redirectTo: to.fullPath}})
    return
  }else if (to.name == 'Redirect'){
    next()
    return
  }

  const userStore = useUserStore()

  if((to.name == 'login') || (to.name == 'home') || (to.name == 'register') || (to.name == 'menu') || (to.name == 'quadropedidos') || (to.name == 'Checkout') || (to.name == 'Payment')){
    next()
    return
  }

  if(!userStore.user){
    next({ name: 'login' })
    return
  }

  if (to.name == 'dashboard') {
    // if (userStore.user.type != 'EM') {
    //   next({ name: 'home' })
    //   return
    // }
  }

  
next()
})

export default router
