import { ref, computed, inject } from "vue";
import { defineStore } from "pinia";
import avatarNoneUrl from '@/assets/img/defaultLogo.jpg'

export const useUserStore = defineStore("user", () => {
  const axios = inject("axios");
  const serverBaseUrl = inject('serverBaseUrl')
  const socket = inject("socket")
  const toast = inject("toast")

  const user = ref(null);
  const customer = ref(null);
  const userId = computed(() => {
    return user.value?.id ?? -1
  })

  const userType = computed(() => {
    return user.value?.type ?? ''
  })

  const userPhotoUrl = computed(() => {
    if (!user.value?.photo_url) {
      return avatarNoneUrl
    }
    return serverBaseUrl + '/storage/fotos/' + user.value.photo_url
  })

  async function loadUser() {
    try {
      const response = await axios.get("users/me");
      user.value = response.data.data;
      if(user.value.blocked == 1){
        toast.error(`You are blocked`)
        clearUser()
      }
      switch (user.value.type) {
        case 'ED':
          socket.on('orderReadyToDeliver', (order) => {
            toast.success(`An order is ready to be delivered`)
          })
          break

        case 'EC':
          socket.on('newOrder', (order) => {
            toast.success(`A new order as been requested`)
          })
          break

        default:
          //customer
          socket.on(socket.id, (order) => {
            if (order.status == 'R') {
              toast.success(`Order is ready`)
            }
            if (order.status == 'D') {
              toast.success(`Order has been delivered`)
            }
          })
      }

      if (user.value.type == 'C') {
        const responseCustomer = await axios.get("customers/me")
        customer.value = responseCustomer.data.data;
      }
    } catch (error) {
      clearUser();
      throw error;
    }
  }

  function clearUser() {
    delete axios.defaults.headers.common.Authorization;
    sessionStorage.removeItem('token')
    user.value = null;
    customer.value = null;
  }

  async function login(credentials) {
    try {
      const response = await axios.post("login", credentials);
      axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token;
      sessionStorage.setItem('token', response.data.access_token)
      await loadUser();
      socket.emit('loggedIn', user.value)
      return true;
    } catch (error) {
      clearUser();
      return false;
    }
  }

  async function logout() {
    try {
      await axios.post("logout");
      socket.emit('loggedOut', user.value)
      clearUser();
      return true;
    } catch (error) {
      return false;
    }
  }

  async function restoreToken() {
    let storedToken = sessionStorage.getItem('token')
    if (storedToken) {
      axios.defaults.headers.common.Authorization = "Bearer " + storedToken
      await loadUser()
      socket.emit('loggedIn', user.value)
      return true
    }
    clearUser()
    return false
  }

  return { user, customer, userId, userType, userPhotoUrl, loadUser, clearUser, login, logout, restoreToken };
});
