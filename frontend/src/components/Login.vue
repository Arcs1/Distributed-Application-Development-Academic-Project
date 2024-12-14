<template>
    
<div id="login-form-wrap">
  <h2>Login</h2>
  <form @submit.prevent="login" id="login-form">
    <p>
    <input type="email" id="email" name="email" v-model="credentials.email" placeholder="Email" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="password" id="password" name="password" v-model="credentials.password" placeholder="Password" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="submit" id="login" value="Login">
    </p>
  </form>
  <div id="create-account-wrap">
    <p>Ainda não é membro? <router-link  to="/register">Criar conta</router-link></p>
  </div>
</div>
</template>

<script>
import { inject} from 'vue'
import { useUserStore } from '../stores/user';
import router from '../router';
export default {
    data(){
        return{
          credentials:{
            email:'',
            password:''
          }
        }
    },
    emits:["login"], 
    methods:{
      async login(){
        if(await this.userStore.login(this.credentials)){
          this.$toast.success('User ' + this.userStore.user.name + ' has entered the application.')
          this.$emit('login')
          router.back()
        }else{
          this.credentials.password = ''
          this.$toast.error('User credentials are invalid!')
        }
      }
    },
    computed:{
      toast(){
        return inject('toast')
      }
      ,
      userStore(){
            return useUserStore();
        }
    }
}
</script>

<style scoped>

h2 {
  font-weight: 300;
  text-align: center;
}

p {
  position: relative;
}

a,
a:link,
a:visited,
a:active {
  color: #3ca9e2;
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
}
a:focus, a:hover,
a:link:focus,
a:link:hover,
a:visited:focus,
a:visited:hover,
a:active:focus,
a:active:hover {
  color: #329dd5;
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
}

#login-form-wrap {
  background-color: #fff;
  text-align: center;
  padding: 20px 0 0 0;
  border-radius: 4px;
  
  width: 500px;
  position: fixed;
  top: 50%;
  left: 50%;
  margin-top: -200px;
  margin-left: -200px;
}

#login-form {
  padding: 0 60px;
}

input {
  display: block;
  box-sizing: border-box;
  width: 100%;
  outline: none;
  height: 60px;
  line-height: 60px;
  border-radius: 4px;
}

input[type="password"],
input[type="email"] {
  width: 100%;
  padding: 0 0 0 10px;
  margin: 0;
  color: #8a8b8e;
  border: 1px solid #c2c0ca;
  font-style: normal;
  font-size: 16px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  position: relative;
  display: inline-block;
  background: none;
}
input[type="password"]:focus,
input[type="email"]:focus {
  border-color: #3ca9e2;
}
input[type="password"]:focus:invalid,
input[type="email"]:focus:invalid {
  color: #cc1e2b;
  border-color: #cc1e2b;
}
input[type="password"]:valid ~ .validation,
input[type="email"]:valid ~ .validation {
  display: block;
  border-color: #0C0;
}
input[type="password"]:valid ~ .validation span,
input[type="email"]:valid ~ .validation span {
  background: #0C0;
  position: absolute;
  border-radius: 6px;
}
input[type="password"]:valid ~ .validation span:first-child,
input[type="email"]:valid ~ .validation span:first-child {
  top: 30px;
  left: 14px;
  width: 20px;
  height: 3px;
  -webkit-transform: rotate(-45deg);
          transform: rotate(-45deg);
}
input[type="password"]:valid ~ .validation span:last-child,
input[type="email"]:valid ~ .validation span:last-child {
  top: 35px;
  left: 8px;
  width: 11px;
  height: 3px;
  -webkit-transform: rotate(45deg);
          transform: rotate(45deg);
}

.validation {
  display: none;
  position: absolute;
  content: " ";
  height: 60px;
  width: 30px;
  right: 15px;
  top: 0px;
}

input[type="submit"] {
  border: none;
  display: block;
  background-color: #3ca9e2;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
  cursor: pointer;
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
  font-size: 18px;
  position: relative;
  display: inline-block;
  cursor: pointer;
  text-align: center;
}
input[type="submit"]:hover {
  background-color: #329dd5;
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
}

#create-account-wrap {
  background-color: #eeedf1;
  color: #8a8b8e;
  font-size: 14px;
  width: 100%;
  padding: 10px 0;
  border-radius: 0 0 4px 4px;
}

</style>