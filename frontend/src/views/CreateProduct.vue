<script>
import createForm from '../components/CreateForm.vue'
import { inject } from '@vue/runtime-core';
export default {
    data(){
        return {
          axios:inject('axios'),
          toast: inject('toast'),
          
        }
    },
    components: {
    createForm
  },
  computed:{
    formItems(){
        return [
        {
          label: 'Nome',
          value:'',
          type:'text'
          
        },
        {
          label:'Preço',
          value:'',
          type:'number'
        }
      ];
    },
    selectItems(){
        return[
            {
                label:'Tipo',
                value:''
            }
        ];
    },
    options(){
        return[
            {
            value:'drink',
            option:'Drink'
            },
            {
            value:'cold dish',
            option:'Cold Dish'
            },
            {
            value:'hot dish',
            option:'Hot Dish'
            },
            {
            value:'dessert',
            option:'Dessert'
            },

        ];
    },
    textAreaItems(){
        return[
            {
                label:'Descrição',
                value:''
            }

        ];
    }

  },
  methods:{
    async createProduct(array){
      const userInfo = new FormData

      userInfo.append('name',array[0].value)
      userInfo.append('price',array[1].value)
      userInfo.append('type',array[2].text)
      userInfo.append('photo',array[3])
      userInfo.append('description',array[4][0].value)

      var config = {
        headers: {
          "Content-type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random()
              .toString()
              .substr(2),
          processData: true,
          Accept: "application/json"
        }
      };
      try{
      const data = await this.axios.post('product/create',userInfo,config)
      this.$toast.success('Product created with success')
      this.$router.push('/dashboard'); 
      }catch(error){
        this.$toast.error('Error creating the product')
      }
      
    }

  }

}
</script>

<template>
<div class="content-form">
<createForm :formItems="formItems" :isSelectedRequired="true" :selectItems="selectItems" :options="options" :isFileUploadRequired="true" :title="'Criar Novo Produto'" :isTextAreaRequired="true"
:textAreaItems="textAreaItems" @createItem="createProduct" :selected="drink"
></createForm>
</div>

</template>





<style scoped> 

.content-form{
  
  border-radius: 4px;
  position: fixed;
  top: 50%;
  left: 50%;
  margin-top: -300px;
  margin-left: -200px;
    
    
}

</style>