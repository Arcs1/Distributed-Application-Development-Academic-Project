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
          label:'Email',
          value:'',
          type:'email'
        },
        {
          label:'Password',
          value:'',
          type:'password'
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
            value:'EM',
            option:'EM - Employee Manager'
            },
            {
            value:'EC',
            option:'EC - Employee Chef'
            },
            {
            value:'EC',
            option:'ED - Employee Delivery'
            },
        ];
    },
  },
  methods:{
    async createUser(array){
        let userInfo = new FormData
        userInfo.append('name',array[0].value)
        userInfo.append('type',array[3].text)
        userInfo.append('photo', array[4])
        userInfo.append('password',array[2].value)
        userInfo.append('email',array[1].value)
       
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
       const data = await this.axios.post('user/create',userInfo,config);
       this.$toast.success('User created with success')
       this.$router.push('/dashboard');
      }catch(error){
        this.$toast.error('Error creating the user')
      }
       
      
    }

  }

}
</script>

<template>
<div class="content-form">
<createForm :formItems="formItems" :isSelectedRequired="true" :selectItems="selectItems" :options="options" :isFileUploadRequired="true" :title="'Criar Novo Utilizador'" :isTextAreaRequired="false"
 @createItem="createUser" :selected="EM"
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