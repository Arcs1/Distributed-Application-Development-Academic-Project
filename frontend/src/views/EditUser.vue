<script>
import { inject } from '@vue/runtime-core';
import createForm from '../components/CreateForm.vue'
export default {

  data() {
    return {
      axios: inject('axios'),
      user: [],
      baseURL: inject("serverBaseUrl"),

    }
  },
  components: {
    createForm
  },
  computed: {
    formItems() {
      return [
        {
          label: 'Nome',
          value: this.user.name,
          type: 'text'
        },
        {
          label: 'Email',
          value: this.user.email,
          type: 'email'
        },

      ];
    },
    selectItems() {
      return [
        {
          label: 'Tipo',
          value: ''
        },
      ];
    },
    options() {
      return [
        {
          value: 'EM',
          option: 'EM - Employee Manager'
        },
        {
          value: 'EC',
          option: 'EC - Employee Chef'
        },
        {
          value: 'EC',
          option: 'ED - Employee Delivery'
        },
      ];
    }
  },
  methods: {
    async getUser() {
      let response = await this.axios.get('users/' + this.$route.params.id)
      this.user = response.data.data;
      console.log(response.data.data)
    }, async editUser(array) {
      let userInfo = new FormData
      userInfo.append('name', array[0].value)
      userInfo.append('email', array[1].value)
      userInfo.append('type', array[2].text)
      userInfo.append('photo', array[3])

      const data = await this.axios.post('users/' + this.$route.params.id + '/update', userInfo);

      this.$router.push('/dashboard');
    }

  },
  mounted() {
    this.getUser();
  }
}


</script>


<template>
  <img v-if="this.user.photo_url != null" class="photo"
    :src="baseURL + '/storage/fotos/' + this.user.photo_url" alt="">
  <div class="content-form">
    <createForm :formItems="formItems" :isSelectedRequired="true" :selectItems="selectItems" :options="options"
      :isFileUploadRequired="true" :title="'Editar Utilizador'" :isTextAreaRequired="false" @createItem="editUser"
      :selected="this.user.type"></createForm>
  </div>
</template>


<style scoped>
.content-form {

  border-radius: 4px;
  position: fixed;
  top: 50%;
  left: 50%;
  margin-top: -300px;
  margin-left: -200px;
}

.photo {
  width: 150px;
  height: 150px;
  margin-left: 200px;
  margin-top: 100px;
  border-radius: 50%;
  border: 5px solid #555;
}
</style>