<template>
  <form class="form-horizontal" action="" method="post" @submit.prevent="login">
    <fieldset>
      <!-- Name input-->
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-at"></i></span>
          </div>
          <input type="text" class="form-control" :class="{'is-invalid': errors.has('email') }" v-validate="'required|email'" name="email" placeholder="Email" v-model="email">
          <div class="invalid-feedback">
            {{ errors.first('email') }}
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
          </div>
          <input type="text" class="form-control" :class="{'is-invalid': errors.has('password') }" v-validate="'required'" name="password" placeholder="Mot de passe" v-model="password">
          <div class="invalid-feedback">
            {{ errors.first('password') }}
          </div>
        </div>
      </div>
      <div class="form-group" v-show="loginError">
        <div class="p-3 mb-2 bg-danger text-white font-small">Identifiants invalides, veuillez réésayer</div>
      </div>
      <!-- Form actions -->
      <div class="form-group">
        <div class="col-12 widget-right no-padding">
          <button type="submit" class="btn btn-primary btn-md float-right">Se connecter</button>
        </div>
      </div>
    </fieldset>
  </form>
</template>
<script>
  import api from "../config/api"

  export default {
    name: 'LoginForm',
    data() {
      return {
        email : null,
        password : null,
        loginError: false
      }
    },
    methods:{
      login(){
        this.$validator.validateAll().then((result) => {
          if (result) {
            api.post("/auth/login", {
              login: this.email,
              password: this.password
            }).then(response => {
              if (response.data){
                this.$store.commit('login', response.data)
                this.$emit('loggedin')
              }else{
                this.loginError = true
              }
            })
          }
        })
      }
    }
  }
</script>
