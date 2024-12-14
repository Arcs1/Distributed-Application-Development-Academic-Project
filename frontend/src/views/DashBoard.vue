<template>
  <!-- Manager -->
  <div class="content" v-show="userStore.userType == 'EM'">
    <div class="d-flex" id="wrapper">
      <SideBar class="sidebar" :items="itemsSideBar" :title="title" @update:filter="setFilter" :filter="filtro" />
      <!-- Page content wrapper-->
      <div id="page-content-wrapper">
        <!-- Page content-->
        <div class="product-content">
          <h1 style="background-color:rgba(255,255,255,0.7); display: inline;">{{ this.filtro }}</h1>
          <createButton v-show="filtro != 'Pedidos' && filtro!='Itens à Espera' && filtro!='Itens Para Preparar'" :btnText="btnText" :route="routes"></createButton>
          <Table :headers="itemsHeader" :filter="filtro" :items="data" @RemoveItem="removeItem"
            @HandleBlock="handleBlock" @EditItem="editItem"></Table>
          <Pagination v-if="filtro == 'Produtos'" :data="metadataProducts" @update:page="setPage" :page="page">
          </Pagination>
          <Pagination v-if="filtro == 'Utilizadores'" :data="metaDataUser" @update:page="setPage" :page="page">
          </Pagination>
        </div>
      </div>
    </div>
  </div>

  <!-- Delivery -->
  <div class="content" v-show="userStore.userType == 'ED'">
    <div class="d-flex" id="wrapper">
      <!-- Page content wrapper-->
      <div id="page-content-wrapper">
        <!-- Page content-->
        <div class="product-content">
          <h1>{{ this.filtro }}</h1>
          <Table :headers="itemsHeader" :filter="filtro" :items="data"></Table>
        </div>
      </div>
    </div>
  </div>

  <!-- Chef -->
  <div class="content" v-show="userStore.userType == 'EC'">
    <div class="d-flex" id="wrapper">
      <SideBar class="sidebar" :items="itemsSideBar" :title="title" @update:filter="setFilter" :filter="filtro" />
      <!-- Page content wrapper-->
      <div id="page-content-wrapper">
        <!-- Page content-->
        <div class="product-content">
          <h1 style="background-color:rgba(255,255,255,0.7); display: inline;">{{ this.filtro }}</h1>
          <Table :headers="itemsHeader" :filter="filtro" :items="data" @PrepareItem="prepareItem"></Table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SideBar from '../components/SideBar.vue';
import Table from '../components/Table.vue';
import { inject } from '@vue/runtime-core';
import Pagination from '../components/Pagination.vue';
import createButton from '../components/CreateButton.vue';
import createForm from '../components/CreateForm.vue'
import { useUserStore } from '../stores/user.js'
export default {
  components: {
    SideBar,
    Table,
    Pagination,
    createButton,
    createForm
  },
  data: function () {
    return {
      isFetching: true,
      metaDataUser: [],
      metadataProducts: [],
      headerArray: [],
      products: [],
      users: [],
      orders: [],
      undeliveredOrders: [],
      waitingOrderItems: [],
      unpreparedOrderItems: [],
      title: "DashBoard",
      asc: true,
      filtro: 'Produtos',
      page: '1',
      btnText: 'Criar Novo',
      axios: inject('axios'),
      baseURL: inject("serverBaseUrl"),
      userStore: useUserStore()
    }
  },
  watch: {
    page(newPage) {
      if (this.filtro == 'Produtos') {
        this.loadProducts(newPage)
      }
      if (this.filtro == 'Utilizadores') {
        this.loadUsers(newPage)
      }
    }
  },
  computed: {
    itemsSideBar() {
      switch (this.userStore.userType) {
        case 'EM':
          return [
            {
              name: 'Produtos',
              filter: 'Produtos'
            },
            {
              name: 'Utilizadores',
              filter: 'Utilizadores'
            },
            {
              name: 'Pedidos',
              filter: 'Pedidos'
            },
            {
              name: 'Itens à Espera',
              filter: 'Itens à Espera'
            },
            {
              name: 'Itens Para Preparar',
              filter: 'Itens Para Preparar'
            }
          ];

        case 'EC':
          return [
            {
              name: 'Itens à Espera',
              filter: 'Itens à Espera'
            },
            {
              name: 'Itens Para Preparar',
              filter: 'Itens Para Preparar'
            }
          ];

      }
    }, itemsHeader() {
      switch (this.filtro) {
        case 'Produtos':
          return [{
            name: ''
          }, {
            name: '#'
          }, {
            name: 'Produto'
          }, {
            name: 'Tipo'
          }, {
            name: 'Descrição'
          }, {
            name: 'Preço'
          }, {
            name: 'Ações'
          }]
          break;

        case 'Utilizadores':
          return [{
            name: ''
          }, {
            name: '#'
          }, {
            name: 'Nome'
          },
          {
            name: 'Tipo'
          },
          {
            name: 'Bloqueado'
          },
          {
            name: 'Ações'
          }]
          break;

        case 'Pedidos':
          return [{
            name: 'Ticket Nº'
          }, {
            name: 'Produtos'
          }, {
            name: 'Ações'
          }]
          break;

        case 'Pedidos Para Entrega':
          return [{
            name: 'Ticket Nº'
          }, {
            name: 'Produtos'
          }, {
            name: 'Ações'
          }]
          break;

        case 'Itens à Espera':
          return [{
            name: 'Ticket Nº'
          }, {
            name: 'Produto'
          }, {
            name: 'Ações'
          }]
          break;

        case 'Itens Para Preparar':
          return [{
            name: 'Ticket Nº'
          }, {
            name: 'Produto'
          }, {
            name: 'Ações'
          }]
          break;
      }
    },
    data() {
      switch (this.filtro) {
        case 'Produtos':
          return this.products
          break;

        case 'Utilizadores':
          return this.users
          break;

        case 'Pedidos':
          return this.orders
          break;

        case 'Pedidos Para Entrega':
          return this.undeliveredOrders
          break;

        case 'Itens à Espera':
          return this.waitingOrderItems
          break;

        case 'Itens Para Preparar':
          return this.unpreparedOrderItems
          break;

      }
    },
    routes() {
      switch (this.filtro) {
        case 'Produtos':
          return '/createProduct'
          break;

        case 'Utilizadores':
          return '/createUser'
          break;
      }
    }
  },
  methods: {
    editItem(item) {
      switch (this.filtro) {
        case 'Utilizadores':
          this.$router.push('users/' + item.id + '/edit');
          break;
        case 'Produtos':
          this.$router.push('products/' + item.id + '/edit');
          break;
      }
    },
    prepareItem(item) {
      this.unpreparedOrderItems.push(item)
    },
    async handleBlock(item) {
      var arrR = this.users.filter(function (ele) {
        return (ele == item)
      });

      if (arrR.length > 0) {
        try {
          let response = await this.axios.put('users/' + item.id + '/block');
          this.loadUsers()
        } catch (error) {

        }
      }
    },
    async removeItem(item) {
      switch (this.filtro) {
        case 'Produtos':
          var arrR = this.products.filter(function (ele) {
            return (ele == item)
          });

          if (arrR.length > 0) {
            this.products.splice(this.products.indexOf(item), 1)
            try {
              let response = await this.axios.delete('products/' + item.id);
            } catch (error) {
              alert("Erro ao apagar produto: " + error.message)
            }
          }
          break;

        case 'Utilizadores':
          var arrR = this.users.filter(function (ele) {
            return (ele == item)
          });

          if (arrR.length > 0) {
            this.users.splice(this.users.indexOf(item), 1)
            try {
              let response = await this.axios.delete('users/' + item.id);
              console.log(response)
            } catch (error) {
              alert("Erro ao apagar utilizador: " + error.message)
            }
          }
          break;

        case 'Pedidos':
          var arrR = this.orders.filter(function (ele) {
            return (ele == item)
          });

          if (arrR.length > 0) {
            this.orders.splice(this.orders.indexOf(item), 1)
            try {
              let response = await this.axios.delete('orders/' + item.id);
              console.log(response)
            } catch (error) {
              alert("Erro ao apagar pedido: " + error.message)
            }
          }
          break;
      }
    },
    setPage(page) {
      this.page = page;
    },
    setFilter(filter) {
      this.filtro = filter;
    },
    async loadProducts(page) {
      let response = await this.axios.get('paginatedProducts?page=' + page)
      this.products = response.data.data;
    },
    async loadUsers(page) {
      let response = await this.axios.get('users?page=' + page)
      this.users = response.data.data;
    },
    async loadOrders() {
      let response = await this.axios.get('orders')
      this.orders = response.data.data;
    },
    async loadUndeliveredOrders() {
      let response = await this.axios.get('orders/undelivered')
      this.undeliveredOrders = response.data.data;
    },
    async loadWaitingOrderItems() {
      let response = await this.axios.get('order_items/waiting')
      this.waitingOrderItems = response.data.data;
    },
    async loadUnpreparedOrderItems() {
      let response = await this.axios.get('order_items/unprepared')
      this.unpreparedOrderItems = response.data.data;
    },
    async loadMetaProducts() {
      let response = await this.axios.get('paginatedProducts?page=' + this.page)
      this.metadataProducts = response.data.meta;
    },
    async loadMetaUsers() {
      let response = await this.axios.get('users?page=' + this.page)
      this.metaDataUser = response.data.meta;
    },
    collapseCodeComment() {
      // sorter(type) {
      //   switch (type) {
      //     case 'tipo':
      //       this.products = this.products.sort((a, b) => {
      //         let fa = a.type.toLowerCase(), fb = b.type.toLowerCase();
      //         if (fa < fb) {
      //           return -1
      //         }
      //         if (fa > fb) {
      //           return 1
      //         }
      //         return 0
      //       })
      //       break;

      //     case 'id':
      //       if (this.asc == true) {
      //         this.asc = !this.asc
      //         this.products = this.products.sort((a, b) => {
      //           return a.id - b.id
      //         })
      //       } else {
      //         this.asc = !this.asc
      //         this.products = this.products.sort((a, b) => {
      //           return b.id - a.id
      //         })
      //       }


      //       break;

      //     case 'produto':
      //       this.products = this.products.sort((a, b) => {
      //         let fa = a.name.toLowerCase(), fb = b.name.toLowerCase();
      //         if (fa < fb) {
      //           return -1
      //         }
      //         if (fa > fb) {
      //           return 1
      //         }
      //         return 0
      //       })

      //       break;
      //     case 'preco':
      //       if (this.asc == true) {
      //         this.asc = !this.asc
      //         this.products = this.products.sort((a, b) => {
      //           return a.price - b.price
      //         })
      //       } else {
      //         this.asc = !this.asc
      //         this.products = this.products.sort((a, b) => {
      //           return b.price - a.price
      //         })
      //       }

      //       break;
      //   }

      // }
    }
  },
  async mounted() {
    const fetchData = async () => {
      switch (this.userStore.userType) {
        case 'EM':
          await this.loadProducts();
          await this.loadUsers();
          await this.loadOrders();
          await this.loadUndeliveredOrders();
          await this.loadWaitingOrderItems();
          await this.loadUnpreparedOrderItems();
          await this.loadMetaProducts();
          await this.loadMetaUsers();
          this.isFetching = false

        case 'ED':
          await this.loadUndeliveredOrders();
          this.isFetching = false

        case 'EC':
          await this.loadWaitingOrderItems();
          await this.loadUnpreparedOrderItems();
          this.isFetching = false
      }
    }

    fetchData()
    while (this.isFetching) {
      console.log("Fetching data...")
      await new Promise(resolve => setTimeout(resolve, 1000))
    }
    console.log("Data fetched.")

    if (this.userStore.userType == 'EM') {
      this.filtro = 'Produtos'
    }
    if (this.userStore.userType == 'ED') {
      this.filtro = 'Pedidos Para Entrega'
    }
    if (this.userStore.userType == 'EC') {
      this.filtro = 'Itens à Espera'
    }
  }
}
</script>

<style scoped>
#page-content-wrapper {
  padding-top: 30px;
  padding-left: 100px;
  padding-right: 20px;
}

#addCartBtn {
  border-color: #000;
  border-width: 2px;
}

#addCart {
  padding-bottom: 10px;
  padding-top: 5px;
}

.btn-outline-dark {
  color: #212529;
  border-color: #212529;
}

.btn-outline-dark:hover {
  color: #fff;
  background-color: #212529;
  border-color: #212529;
}

.fw-bolder {
  margin-top: 20px;
  text-align: center;
}

.description {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border: 3px solid rgba(255, 0, 0, .5);
  -webkit-background-clip: padding-box;
  /* for Safari */
  background-clip: padding-box;
  /* for IE9+, Firefox 4+, Opera, Chrome */
  border-radius: 5px;
}

#product-box {
  justify-content: space-between;
  border-color: #000;
  border-width: 2px;
}

#item-box {
  height: fit-content;
  width: 300px;
  overflow: hidden;
}

.product-content {
  display: inline-table;
  justify-content: space-between;
  padding: 1px;
}

.card-img-top {
  width: 200px;
  height: 300px;
}

h6,
.h6,
h5,
.h5,
h4,
.h4,
h3,
.h3,
h2,
.h2,
h1,
.h1 {
  margin-top: 0;
  margin-bottom: 0.5rem;
  font-weight: 500;
  line-height: 1.2;
}

h5,
.h5 {
  font-size: 1.25rem;
}

h6,
.h6 {
  font-size: 1rem;
}

p {
  margin-top: 0;
  margin-bottom: 1rem;
}

.container-fluid {
  width: 100%;
  padding-right: var(--bs-gutter-x, 0.75rem);
  padding-left: var(--bs-gutter-x, 0.75rem);
  margin-right: auto;
  margin-left: auto;
}

.btn {
  display: inline-block;
  font-weight: 400;
  line-height: 1.5;
  color: #212529;
  text-align: center;
  text-decoration: none;
  vertical-align: middle;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
  color: #fff;
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.navbar {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.navbar>.container-fluid {
  display: flex;
  flex-wrap: inherit;
  align-items: center;
  justify-content: space-between;
}

@media (min-width: 992px) {
  .navbar-expand-lg {
    flex-wrap: nowrap;
    justify-content: flex-start;
  }

  .navbar-expand-lg .navbar-nav {
    flex-direction: row;
  }

  .navbar-expand-lg .navbar-nav .dropdown-menu {
    position: absolute;
  }

  .navbar-expand-lg .navbar-nav .nav-link {
    padding-right: 0.5rem;
    padding-left: 0.5rem;
  }

  .navbar-expand-lg .navbar-nav-scroll {
    overflow: visible;
  }

  .navbar-expand-lg .navbar-collapse {
    display: flex !important;
    flex-basis: auto;
  }

  .navbar-expand-lg .navbar-toggler {
    display: none;
  }

  .navbar-expand-lg .offcanvas-header {
    display: none;
  }

  .navbar-expand-lg .offcanvas {
    position: inherit;
    bottom: 0;
    z-index: 1000;
    flex-grow: 1;
    visibility: visible !important;
    background-color: transparent;
    border-right: 0;
    border-left: 0;
    transition: none;
    transform: none;
  }

  .navbar-expand-lg .offcanvas-top,
  .navbar-expand-lg .offcanvas-bottom {
    height: auto;
    border-top: 0;
    border-bottom: 0;
  }

  .navbar-expand-lg .offcanvas-body {
    display: flex;
    flex-grow: 0;
    padding: 0;
    overflow-y: visible;
  }
}

.card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}

.card-img,
.card-img-top,
.card-img-bottom {
  width: 100%;
}

.d-flex {
  display: flex !important;
}

.mb-5 {
  margin-bottom: 1rem !important;
}

.fw-bolder {
  font-weight: bolder !important;
}

.text-center {
  text-align: center !important;
}
</style>