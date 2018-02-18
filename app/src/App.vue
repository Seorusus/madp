<template>
    <div id="app">
        <div class="row">
            <main class="col">
                <router-view/>
            </main>
        </div>
        <b-modal ref="loginModal" hide-footer title="Connexion">
            <login-form v-on:loggedin="handleLogin($event)"></login-form>
        </b-modal>
    </div>
</template>

<script>
    import api from "./config/api"
    import router from "./router/index"
    import LoginForm from "./components/LoginForm"
    import NProgress from 'nprogress'

    export default {
        components: {
            "login-form": LoginForm
        },
        data() {
            return {
                loading: false,
                isMenuToggled: false
            }
        },
        mounted() {
            let self = this

            // Add a request interceptor
            api.interceptors.request.use(function (config) {
                // Do something before request is sent
                NProgress.set(0.3)
                return config
            }, function (error) {
                // Do something with request error
                NProgress.done()
                return Promise.reject(error)
            })


            // Add a response interceptor
            api.interceptors.response.use(function (response) {
                // Do something with response data
                NProgress.done()
                return response
            }, function (error) {
                NProgress.done()
                if (error.response.status == 401) {
                    self.$store.commit('logout')
                    self.$refs.loginModal.show()
                }
                return Promise.reject(error)
            })

        },
        methods: {
            handleLogin() {
                this.$router.push("dossiers")
                this.$refs.loginModal.hide()
            },
            logout() {
                api.get('auth/logout')
                    .then(response => {
                        this.$store.commit('logout')
                        router.push({path: 'login'})
                    })
            }
        }

    }
</script>