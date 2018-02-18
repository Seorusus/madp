import Vue from "vue";
import App from "./src/App";
import router from './src/router'
import BootstrapVue from 'bootstrap-vue'
import 'nprogress/nprogress.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import fr from './src/config/errors-messages'
import VeeValidate, {Validator} from 'vee-validate'
import Vuex from 'vuex'
import $ from "jquery";
import './src/static/fonts/all'

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        user: false
    },
    mutations: {
        login: (state, user) => state.user = user,
        logout: state => state.user = false
    },
    getters: {
        user: state => {œ
            return state.user
        },
        hasRight: (state) => (right) => {
            return state.user && state.user.rights.indexOf(right) !== -1
        }
    }
})

Vue.config.productionTip = false

Vue.use(BootstrapVue, Vuex)

Validator.localize('fr', fr)

Validator.extend('password', {
    getMessage: field => "Le mot de passe doit contenir au moins: 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et un caractère spécial",
    validate: value => {
        var hasUpperCase = /[A-Z]/.test(value);
        var hasLowerCase = /[a-z]/.test(value);
        var hasNumbers = /\d/.test(value);
        var hasNonalphas = /\W/.test(value);

        return hasUpperCase + hasLowerCase + hasNumbers + hasNonalphas > 2
    }
});

Vue.use(VeeValidate)

Vue.mixin({
    computed: {
        user: () => {
            return store.getters.user
        }
    },
    methods: {
        hasRight(right){
            return store.getters.hasRight(right)
        }
    }
})


/* eslint-disable no-new */
var vm = new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    render: h => h(App)
})

$.ajaxSetup({
    dataType: 'json',
    xhrFields: {
        withCredentials: true
    },
    crossDomain: true
})

$.get({
    url: "http://api.madp.localhost/auth/me",
}).done(function(data){
    $('#loggedBtn').show().find(' > button').text(data.name)
    $('#unLoggedBtn').hide()
    vm.$store.commit('login', data)
}).fail(function(){
    $('#loggedBtn').hide()
    $('#unLoggedBtn').show()
    vm.$store.commit('logout')
})
document.getElementById("logout").addEventListener("click", function(event){
    event.preventDefault();
    $.get({
        url: "http://api.madp.localhost/auth/logout",
    }).done(function(){
        window.location.href="/";
    }).fail(function(){
    })
});