<template>
  <div class="pagination">
    <a v-for="i in data.links" :key="i.label" @click="setPage(i.label)" :class="{ active: page == i.label }"
      v-html="i.label"></a>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currPage: 1,
    }
  },
  name: 'Pagination',
  emits: ["update:page"],
  props: {
    data: {
      type: Object,
      default: () => []
    },
    page: {
      type: String,
      default: '1',
    },
  },
  methods: {
    tests(){
      console.log(this.data)
    },
    setPage(page) {
      if (page != 'Next &raquo;' && page != '&laquo; Previous') {
        this.currPage = page
      }
      if (page == 'Next &raquo;') {
        if (this.currPage >= this.data.last_page) {
          this.currPage = 7
        } else {
          this.currPage++
        }
        page = this.currPage
        this.$emit("update:page", page)
      }

      if (page == '&laquo; Previous') {
        this.currPage--
        if (this.currPage < 1) {
          this.currPage = 1
        }
        page = this.currPage
        this.$emit("update:page", page)
      }

      this.$emit("update:page", page);
    },
  }
}
</script>

<style scoped>
.pagination {
  display: inline-block;
  background-color: white;
}

.pagination a:hover {
  cursor: pointer;
}

.pagination a {
  color: black;
  background-color: white;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {
  background-color: #ddd;
}
</style>