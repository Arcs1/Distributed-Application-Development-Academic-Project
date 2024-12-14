<template>
    <div id="login-form-wrap">
  <h2>Criar Conta</h2>
  <form @submit.prevent="handleSubmit" id="login-form">
    <p>
    <input type="text" id="name" name="name" v-model="credentials.name" placeholder="Username" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="email" id="email" name="email" v-model="credentials.email" placeholder="Email" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="text" id="phone" name="phone" v-model="credentials.phone" placeholder="Phone Number" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="password" id="password" name="password" v-model="credentials.password" placeholder="Password" required><i class="validation"><span></span><span></span></i>
    </p>
    <p>
    <input type="password" id="password" name="password" v-model="credentials.password_confirm" placeholder="Confirmar Password" required><i class=""><span></span><span></span></i>
    </p>
    <p>
    <input type="submit" id="register" value="Registar">
    </p>
  </form>
</div>
</template>


<script>
import { inject} from 'vue'
export default {
    data(){
        return {
          axios:inject('axios'),
          credentials:{
            name:'',
            email:'',
            phone:'',
            password:'',
            password_confirm:'',
          }
        }
    },
    methods:{
        async handleSubmit(){
            
            const data = await this.axios.post('user/register',this.credentials);
            this.$router.push('/login'); 
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

input[type="text"],
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
input[type="text"]:focus,
input[type="password"]:focus,
input[type="email"]:focus {
  border-color: #3ca9e2;
}
input[type="text"]:focus:invalid,
input[type="password"]:focus:invalid,
input[type="email"]:focus:invalid {
  color: #cc1e2b;
  border-color: #cc1e2b;
}
input[type="text"]:valid ~ .validation,

input[type="password"]:valid ~ .validation,
input[type="email"]:valid ~ .validation {
  display: block;
  border-color: #0C0;
}
input[type="text"]:valid ~.validation span,
input[type="password"]:valid ~ .validation span,
input[type="email"]:valid ~ .validation span {
  background: #0C0;
  position: absolute;
  border-radius: 6px;
}

input[type="text"]:valid ~ .validation span:first-child,
input[type="password"]:valid ~ .validation span:first-child,
input[type="email"]:valid ~ .validation span:first-child {
  top: 30px;
  left: 14px;
  width: 20px;
  height: 3px;
  -webkit-transform: rotate(-45deg);
          transform: rotate(-45deg);
}
input[type="text"]:valid ~ .validation span:last-child,
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

</style>