<template>
  <div class="content">
    <div class="d-flex" id="wrapper">
      <!--SideBar Component-->

      <SideBar :items="itemsSideBar" :isActive="isSideBarActive" @update:filter="setFilter" :filter="filtro"
        :title="title" />
      <!-- Page content wrapper-->
      <div id="page-content-wrapper">
        <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
          <div class="container-fluid">
            <button class="btn btn-primary" id="sidebarToggle" @click="isSideBarActive = !isSideBarActive">Toggle
              Menu</button>
            <button class="btn btn-primary" id="sidebarToggle">Filtrar</button>
          </div>
        </nav>
        <!-- Page content-->

        <div class="product-content" v-for="product in dishes" :key="product.id">
          <div class="col mb-5" id="item-box">
            <div class="card h-100" id="product-box">
              <!-- Product image-->
              <img class="card-img-top" :src="baseURL + '/storage/products/' + product.photo_url"
                alt="..." />
              <!-- Product details-->
              <h6 class="fw-bolder">Descrição</h6>
              <p class="description">{{ product.description }}</p>
              <div class="card-body p-4">
                <div class="text-center">
                  <!-- Product name-->
                  <h5 class="fw-bolder">{{ product.name }}</h5>
                  <!-- Product reviews-->
                  <div class="d-flex justify-content-center small text-warning mb-2">
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                  </div>
                  <!-- Product price-->
                  <span class="text-muted text-decoration-line-through"></span>
                  {{ product.price }}€
                </div>
              </div>
              <!-- Product actions-->
              <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center" id="addCart"><a class="btn btn-outline-dark mt-auto" id="addCartBtn"
                    @click="cart.addItem(product)">Adicionar ao carrinho</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import SideBar from '../components/SideBar.vue';
import { useCartStore } from '../stores/cart.js'
import { inject } from '@vue/runtime-core';
export default {
  components: {
    SideBar
  },

  data: function () {
    return {
      products: [],
      isSideBarActive: true,
      filtro: "hot dish",
      title: "Menu",
      cart: useCartStore(),
      axios: inject('axios'),
      baseURL: inject('serverBaseUrl')
    }
  },

  computed: {
    dishes() {
      return this.products.filter(f => f.type == this.filtro);
    },

    itemsSideBar() {
      return [
        {
          name: 'Pratos Quentes',
          filter: 'hot dish'
        },
        {
          name: 'Pratos Fios',
          filter: 'cold dish'
        }, {
          name: 'Bebidas',
          filter: 'drink'
        },
        {
          name: 'Sobremesas',
          filter: 'dessert'
        }
      ];
    }
  },

  methods: {
    setFilter(filter) {
      this.filtro = filter;
    },

    async loadProducts() {
      let response = await this.axios.get('products')
      this.products = response.data.data;
    },

  },
  mounted() {
    this.loadProducts();
  },


}

</script>

<style scoped>
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
  border-block-start-style: groove;

}

#product-box {
  justify-content: space-between;
  border-color: #000;
  border-width: 2px;
  background-color: white;
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


