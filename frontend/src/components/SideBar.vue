<template>
  <div class="border-end bg-white" id="sidebar-wrapper" v-show="isActive">
    <div class="sidebar-heading border-bottom bg-light" id="titulo">{{ title }}</div>
    <div class="list-group list-group-flush">
      <a v-for="item in items" :key="item.filter"
        class="list-group-item list-group-item-action list-group-item-light p-3" @click="setFilter(item.filter)"
        :class="{ active: filter == item.filter }">{{ item.name }} </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SideBar',
  emits: ["update:filter"],
  props: {
    items: {
      type: Array,
      default: () => []
    },

    isActive: {
      type: Boolean,
      default: true
    },

    filter: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: ''
    }
  },

  methods: {
    setFilter(filter) {
      this.$emit("update:filter", filter);
    }
  }
}
</script>

<style scoped>
.list-group a.active {
  background-color: #006eff;
  color: white;

}

.list-group-item {
  position: relative;
  display: block;
  padding: 0.5rem 1rem;
  color: #212529;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.list-group-flush>.list-group-item {
  border-width: 0 0 1px;
}

.list-group-item-light.list-group-item-action:hover,
.list-group-item-light.list-group-item-action:focus {
  color: #636464;
  background-color: lightblue;
  cursor: pointer;
}

.list-group-item:active {
  color: #212529;
}

.p-3 {
  padding: 1rem !important;
}

#sidebar-wrapper .sidebar-heading {
  padding: 0.875rem 1.25rem;
  font-size: 1.2rem;
}

#sidebar-wrapper .list-group {
  width: 15rem;
}

#titulo {
  background-color: lightgray;
}
</style>