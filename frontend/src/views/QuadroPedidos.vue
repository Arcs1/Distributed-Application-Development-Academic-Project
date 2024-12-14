<template>
  <div class="content">
    <Table :headers="itemsHeader" @update:filter="setFilter" :filter="filtro" :items="sort"></Table>
    <Pagination :data="ordersMetaData" @update:page="setPage" :page="page"></Pagination>
  </div>
</template>

<script>
import Table from '../components/Table.vue';
import { inject } from '@vue/runtime-core';
import Pagination from '../components/Pagination.vue';
export default {
  components: {
    Table,
    Pagination
  },
  data() {
    return {
      axios: inject('axios'),
      data: [],
      filtro: 'Quadro Pedidos',
      baseURL: inject("serverBaseUrl"),
      ordersMetaData: [],
      page: '1',
    }

  },
  methods: {
    setPage(page) {
      this.page = page;
    },
    async loadData() {
      let response = await this.axios.get('orders/ready')
      this.data = response.data.data;
      // console.log(this.data)
    },
    setFilter(filter) {
      this.filtro = filter;
    },
    async loadMetaOrders(page) {
      let response = await this.axios.get('orders/ready?page=' + this.page)
      this.ordersMetaData = response.data.meta;
    }
  },
  computed: {
    itemsHeader() {
      return [{
        name: 'Ticket Nº'
      }, {
        name: 'Estado'
      },
      ]
    },
    // orders() {
    //   return this.data.filter(f => f.status == 'R');
    // },
    sort(){
      return this.data.sort(function(a, b){return a.ticket_number - b.ticket_number});
    }
  },
  mounted() {
    this.loadData();
    this.loadMetaOrders();
  }

}
</script>

<style>

</style>
