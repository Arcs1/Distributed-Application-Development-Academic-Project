import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faPenToSquare, faTrash, faUserSecret, faCaretDown, faCaretSquareDown, faLock, faLockOpen, faPlus, faKey, faUser, faRightFromBracket, faGauge, faBagShopping, faUtensils,faChartLine } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faUserSecret, faTrash, faPenToSquare, faCaretDown, faCaretSquareDown, faLock, faLockOpen, faPlus, faKey, faUser, faRightFromBracket, faGauge, faBagShopping, faUtensils,faChartLine)


import Toaster from "@meforma/vue-toaster"
import './assets/main.css'
import { createPinia } from 'pinia'
import { io } from "socket.io-client"

const apiDomain = import.meta.env.VITE_API_DOMAIN
const wsConnection = import.meta.env.VITE_WS_CONNECTION


const pinia = createPinia();
const app = createApp(App)

const serverBaseUrl = apiDomain
app.provide('axios', axios.create({
  baseURL: serverBaseUrl + '/api',
  headers: {
    'Content-type': 'application/json',
  },
}))
app.provide('serverBaseUrl', serverBaseUrl)

//Toaster
app.use(Toaster, {
  // Global/Default options
  position: 'top',
  timeout: 3000,
  pauseOnHover: true
})
app.provide('toast', app.config.globalProperties.$toast)

//socket.io

app.provide('serverUrl',`${apiDomain}/api`)
app.provide('socket',io(wsConnection))

app.use(pinia);
app.use(router);
app.component('font-awesome-icon', FontAwesomeIcon).mount('#app')

