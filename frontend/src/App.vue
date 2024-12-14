<script setup>
import { inject } from "vue"
import NavBar from './components/NavBar.vue';
import { useUserStore } from './stores/user';

const userStore = useUserStore()
const socket = inject("socket")
const toast = inject("toast")

components: {
  NavBar
}
// if (useUserStore.user == undefined) {
//   socket.on(socket.id, (order) => {
//     if (order.status == 'R') {
//       toast.success(`Order is ready`)
//     }
//     if (order.status == 'D') {
//       toast.success(`Order has been delivered`)
//     }
//   })
// }
</script>

<template>
  <NavBar></NavBar>
</template>

<style>
html {
  background-position: 100px 5px;
}

.content {
  position: relative;
  padding-top: 30px;
  height: fit-content;
  width: fit-content;

}

body {
  font-family: "Roboto", sans-serif;
  display: inline-block;
  justify-content: center;
  align-items: center;
  overflow-x: hidden;
  height: 100%;
  width: 100%;
  background-image: url(@/assets/img/1423593.jpg);
}
</style>
