<template>
  <div class="table-wrapper">
    <table id="keywords" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th v-for="header in headers" :key="header"><span>{{ header.name }}</span></th>
        </tr>
      </thead>

      <!-- Produtos -->
      <tbody v-if="filter == 'Produtos'">
        <tr v-for="item in items" :key="item">
          <td id="picture"><img class="product_photo"
              :src="baseURL + '/storage/products/' + item.photo_url"></td>
          <td>{{ item.id }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.type }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.price }}€</td>
          <td v-show="isActionsNeeded" class="actions"><a @click="removeItem(item)"><font-awesome-icon
                icon="fa-solid fa-trash" style="color:red" /></a><a><font-awesome-icon icon="fa-solid fa-pen-to-square"
                style="color:green" @click="editItem(item)" /></a></td>
        </tr>
      </tbody>

      <!-- Utilizadores -->
      <tbody v-else-if="filter == 'Utilizadores'">
        <tr v-for="item in items" :key="item.id">
          <td id="picture"><img class="product_photo"
              :src="baseURL + '/storage/fotos/' + item.photo_url"></td>
          <td>{{ item.id }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.type }}</td>
          <td class="handleBlock" v-if="item.blocked == 1"><a><font-awesome-icon icon="fa-solid fa-lock"
                style="color:red" @click="handleBlock(item)" /></a></td>
          <td class="handleBlock" v-if="item.blocked == 0"><a><font-awesome-icon icon="fa-solid fa-lock-open"
                style="color:green" @click="handleBlock(item)" /></a></td>
          <td v-show="isActionsNeeded" class="actions"><a @click="removeItem(item)"><font-awesome-icon
                icon="fa-solid fa-trash" style="color:red" /></a><a><font-awesome-icon icon="fa-solid fa-pen-to-square"
                style="color:green" @click="editItem(item)" /></a></td>
        </tr>
      </tbody>

      <!-- Pedidos -->
      <tbody v-else-if="filter == 'Pedidos'">
        <tr v-for="item in items" :key="item">
          <td>{{ item.ticket_number }}</td>
          <td>{{ displayReadyItemNames(item) }}</td>
          <td v-show="isActionsNeeded" class="actions">
            <a @click="removeItem(item)"><font-awesome-icon icon="fa-solid fa-trash" style="color:red" /></a>
          </td>
        </tr>
      </tbody>

      <!-- Pedidos Para Entrega -->
      <tbody v-else-if="filter == 'Pedidos Para Entrega'">
        <tr v-for="item in items" :key="item">
          <td>{{ item.ticket_number }}</td>
          <td>{{ displayReadyItemNames(item) }}</td>
          <td v-show="isActionsNeeded" class="actions">
            <a v-show="userStore.userType == 'ED'" @click="deliverOrder(item)"><font-awesome-icon icon="fa-solid fa-bag-shopping" style="color:green" /></a>
          </td>
        </tr>
      </tbody>

      <!-- Itens à Espera -->
      <tbody v-else-if="filter == 'Itens à Espera'">
        <tr v-for="item in items" :key="item">
          <td>{{ item.ticket_number.ticket_number }}</td>
          <td>{{ item.product.name }}</td>
          <td v-show="isActionsNeeded" class="actions">
            <a v-show="userStore.userType == 'EC'" @click="prepareItem(item)"><font-awesome-icon icon="fa-solid fa-utensils" style="color:green" /></a>
          </td>
        </tr>
      </tbody>

      <!-- Itens Para Preparar -->
      <tbody v-else-if="filter == 'Itens Para Preparar'">
        <tr v-for="item in items" :key="item">
          <td>{{ item.ticket_number.ticket_number }}</td>
          <td>{{ item.product.name }}</td>
          <td v-show="isActionsNeeded" class="actions">
            <a v-show="userStore.userType == 'EC'" @click="readyItem(item)"><font-awesome-icon icon="fa-solid fa-utensils" style="color:green" /></a>
          </td>
        </tr>
      </tbody>

      <!-- Quadro de Pedidos -->
      <!-- v-if="filter != 'Utilizadores' && filter != 'Produtos' && filter != 'Pedidos Para Entrega'" -->
      <tbody v-else>
        <tr v-for="item in items" :key="item">
          <td>{{ item.ticket_number }}</td>
          <td>{{ item.status }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { useUserStore } from '../stores/user.js';
import { inject } from '@vue/runtime-core';
import Order from './Order.vue';

export default {
  data() {
    return {
      axios: inject('axios'),
      socket: inject("socket"),
      baseURL: inject("serverBaseUrl"),
      userStore: useUserStore(),
      str: ''
    }
  },
  name: "Table",
  emits: ["update:filter", "update:items", "RemoveItem", "HandleBlock", "EditItem", "PrepareItem"],
  props: {
    isActionsNeeded: {
      type: Boolean,
      default: true
    },
    items: {
      type: Array,
      default: () => []
    },
    headers: {
      type: Array,
      default: () => []
    },
    filter: {
      type: String,
      default: "",
    },
  },
  methods: {
    removeItem(item) {
      this.$emit("RemoveItem", item);
    },
    handleBlock(item) {
      this.$emit("HandleBlock", item);
    },
    editItem(item) {
      this.$emit("EditItem", item);
    },
    async deliverOrder(item) {
      let response = await this.axios.put('orders/' + item.id + '/deliver')
      this.socket.emit('deliverOrder', response.data.data)
      this.items.splice(this.items.indexOf(item), 1)
    },
    async prepareItem(item) { //W --> P
      let response = await this.axios.put('order_items/' + item.id + '/prepare')
      this.items.splice(this.items.indexOf(item), 1)
      this.$emit("PrepareItem", item)
    },
    async readyItem(item) { //P --> R
      let response = await this.axios.put('order_items/' + item.id + '/ready')
      if (response.data.data == undefined) { //if its an order
        this.socket.emit('orderIsReadyWithHotDish', response.data)
      }
      this.items.splice(this.items.indexOf(item), 1)
    },
    displayReadyItemNames(item) {
      this.str = ''
      for (let i = 0; i < item.orderItems.length; i++) {
        this.str += item.orderItems[i].product.name + ' / '
      }
      // console.log(item)
      // item.orderItems.forEach(element => this.str += element.product.name + ' / ');

      this.str = this.str.slice(0, -2)
      return this.str
    }
  },
  components: {
    Order
  },
  mounted() {
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css?family=Amarante');



#keywords td,
#keywords th {
  text-align: center;
  padding: 8px;
}

#keywords td {
  border-right: 1px solid #f8f8f8;
  font-size: 15px;
}

#keywords thead th {
  color: #ffffff;
  background: #505f8f;
}

#keywords thead th:nth-child(odd) {
  color: #ffffff;
  background: #324960;
}

#keywords tr:nth-child(even) {
  background: lightblue;
}

#keywords {
  border-radius: 5px;
  font-size: 15px;
  font-weight: normal;
  border: none;
  border-collapse: collapse;
  width: 100%;
  max-width: 100%;
  background-color: white;
  border: 2px solid lightblue;
}

@media (max-width: 767px) {
  #keywords {
    display: block;
    width: 100%;
  }

  .table-wrapper:before {
    content: "Scroll horizontally >";
    display: block;
    text-align: right;
    font-size: 11px;
    color: white;
    padding: 0 0 10px;
  }

  #keywords thead,
  #keywords tbody,
  #keywords thead th {
    display: block;
  }

  #keywords thead th:last-child {
    border-bottom: none;
  }

  #keywords thead {
    float: left;
  }

  #keywords tbody {
    width: auto;
    position: relative;
    overflow-x: auto;
  }

  #keywords td,
  #keywords th {
    padding: 20px .625em .625em .625em;
    height: 60px;
    vertical-align: middle;
    box-sizing: border-box;
    overflow-x: hidden;
    overflow-y: auto;
    width: 120px;
    font-size: 13px;
    text-overflow: ellipsis;
  }

  #keywords thead th {
    text-align: left;
    border-bottom: 1px solid #f7f7f9;
  }

  #keywords tbody tr {
    display: table-cell;
  }

  #keywords tbody tr:nth-child(odd) {
    background: none;
  }

  #keywords tr:nth-child(even) {
    background: transparent;
  }

  #keywords tr td:nth-child(odd) {
    background: #F8F8F8;
    border-right: 1px solid #E6E4E4;
  }

  #keywords tr td:nth-child(even) {
    border-right: 1px solid #E6E4E4;
  }

  #keywords tbody td {
    display: block;
    text-align: center;
  }
}

.handleBlock a:hover {
  cursor: pointer;
}

.actions a {
  padding-top: 17px;
}

.actions a:hover {
  cursor: pointer;
}

.actions {
  display: flex;
  justify-content: space-evenly;
}

.product_photo {
  height: 50px
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

img {
  border: 0;
  max-width: 100%;
}

#keywords thead {
  cursor: pointer;
  background: #c9dff0;
}

#keywords thead tr th {
  font-weight: bold;
  padding: 12px 30px;
  padding-left: 42px;
}

#keywords tbody tr td {
  text-align: center;
}

td {
  padding-bottom: 10px;
}
</style>