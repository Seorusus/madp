<template>
  <div class="subscription">
    <h1>Connexion</h1>
    <login-form v-on:loggedin="handleLogin($event)"></login-form>
  </div>
</template>

<script>
  import LoginForm from "../../components/LoginForm"

  export default {
    name: "connection",
    components: {
      LoginForm
    },
      methods:{
          handleLogin(user){
              this.$router.push({ name: 'ClientDashboard' })
          }
      }
  }
</script>

<style scoped>

</style>