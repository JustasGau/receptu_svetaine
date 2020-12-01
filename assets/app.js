import Vue from "vue"
import Vuex from 'vuex'
import App from './components/App'
import VueRouter from "vue-router";
import routes from "./router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './styles/app.css'

import fetcher from './plugins/fetcher'

import RecipeCard from './components/RecipeCard'
import ShowRecipe from './components/ShowRecipe'
import Comment from './components/CommentRecipe'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Vuex)
Vue.use(fetcher)
Vue.component('recipe-card', RecipeCard)
Vue.component('show-recipe', ShowRecipe)
Vue.component('comment-recipe', Comment)

const router = new VueRouter({
    routes
});

Vue.use(VueRouter)

const store = new Vuex.Store({
    state: {
        validated: false,
        token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDY4MTIxNTYsImV4cCI6MTYwNzQxMjE1Niwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6InRlc3Rhc05hbWUifQ.AMqrW4P1A-dXPupypa35gQ1vRIPMNw3_2_HVZfNu3DG3Cl5Oc-r9HkzSq-TrtR-OuNImldEFtckshTLxBIpZIZ_5SKuJMwRTfjaa35X_aNchpQ8pKJnDJk-a6RvT_cWsCnX5SwyoeTCrmgSx_Rr3RQZXXYudSUQY0LFWi1SE4Q2lUyZ1may7bQY2XB1aG3kptmSrUbXSSXO8KEF1KvqLiQvKd3SNsHOqCCisBnWiZLr8RDnyo1jKqSt2yAeC_jC5YiMRao71LyF889uIqcJvwYC_FLVD0l75K2cpQAfEYXN4l0eeMmMKzLsd-rJMBfgODfK2S-Q6zhakcT2aVg_ZVVah11VltvE5oASLW9GsANonl0Aejf4AIWlIEJQc83r3oRwgaA6kN9BFla3B9P3W-Xyuvf1kfB_2MdNU3TDj0pd74nxUkOKXvT9Gz2hjDzbVx6XoOKFKzpH2MiC75NzWZT2IhbhZDTrhCYvIZPet4w1mzfSmIj0CbLKQ3rCcTsKpQnsw_7rTt6uyJciwc8itbEvpXB2JWDgFCM6YDesIG7aXn-178LeOnQMRgP9WzDjlAozvlg7LnxSK5xIJYwlTnhGFFPvxecpk64X_B9ELoGixbNU4FtAReygKeVu3vsUUWaSED1oRwgIv7tZXEQwpH-DBnckxv4sPI2W1RcFPvN0', //TODO clear token
        address: 'https://127.0.0.1:8000/api/'
        // address: 'https://arcane-spire-27910.herokuapp.com/api/'
    },
    mutations: {
        validate (state) {
            state.validated = true
        },
        setToken (state, token) {
            state.token = token
        }
    }
})

new Vue({
    el: '#app',
    store: store,
    render: h => h(App),
    router
})

