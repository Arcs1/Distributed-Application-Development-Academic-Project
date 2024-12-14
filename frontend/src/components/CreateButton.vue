<script>
export default {
  emits: ["createItem"],
    props: {
        btnText: {
            type: String,
            default: "",
        },
        route:{
          type:String,
          default:"",
        }
    },
    methods:{
      
    }
}

</script>


<template>
<button class="btn btn-primary" @click="$router.push(route)"><font-awesome-icon icon="fa-solid fa-plus" />&nbsp;{{this.btnText}}</button>    
</template>


<style scoped>
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
</style>
