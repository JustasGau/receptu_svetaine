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
import EditRecipe from './components/EditRecipe'
import AddRecipe from './components/AddRecipe'
import Comment from './components/CommentRecipe'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Vuex)
Vue.use(fetcher)
Vue.component('recipe-card', RecipeCard)
Vue.component('show-recipe', ShowRecipe)
Vue.component('comment-recipe', Comment)
Vue.component('edit-recipe', EditRecipe)
Vue.component('add-recipe', AddRecipe)

const router = new VueRouter({
    routes
});

Vue.use(VueRouter)

const store = new Vuex.Store({
    state: {
        token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDY4OTQzMTMsImV4cCI6MTYwNzQ5NDMxMywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6InRlc3Rhc05hbWUifQ.GlqffntzdCdqH6gv5ptVv4hVO3W-wvjysZpy0WdGK8fZBv1vaDDyiMjykwm9goQ-TAiv6H4GjSV-OJii38w1eQNVCt68mrCspdJxz-jcPn7GPIGyLcqwIR-5vvvyWRIyJfJC4Hb7iXQSrfamVgWSYYtDjLtBkJeB9en5FIE4lM5YTgiG_9MZTCHiJQRH1JtI2D4eoDK6lH8wYwvx0eBgqPKGNAtdmyytC_XzpI4RXp1Ijc2lNsASGmOd12lVNze__e-zkQKyjT_PC_b26vEiH1hwheh0P31ER9v-TmUiyqChYuOngbEWvg8-Xn1VAQdmGb9jMwX6bGsMmZwUHIPOKCsMZPQEHPF-3c_EH9tRKTgq4xatQsktq5ZUHLISS2c6SfwVpAPse7CYripd2TnWEYviKpp1VQavyKsC79NaTtxxMSgqWU-e3vq7JcBuQMPvDiQ_3Kty1TMdGKK1_7EAV9JGANw-rUAyGGvSDGCk5APuUFQDvM37odv-8FkNZOZL5tO_1a8W4YVg5NZA225MPbVHZlmphYrV7tfSoHcpgcA02LH8DtRrq335QJ2LSD1pMQoGEMZK8hgYY0IMTuwn4_xLNEbKfVdpgRMP-M00UqrB6mDNVtXrSLpyvhUPDxroop_7a3xDtLgSGtg4PtBQNTaSWdIinUNnVBIDU-1Pt6A',
        refreshToken: 'd88b57993efef071595a8cd0953d74b19993024b42c613d29610cfb388dfd9da849ef9fb0bcb3f89d6de42f815fbd0dd96a96bfc0300cb564fb33676d422b47f',
        user: 'testasName',
        isAdmin: true,
        address: 'https://127.0.0.1:8000/api/'
        // address: 'https://arcane-spire-27910.herokuapp.com/api/'
    },
    mutations: {
        setToken (state, token) {
            state.token = token
        },
        setUser (state, value) {
            state.user = value
        },
        setRefreshToken(state, token) {
            state.refreshToken = token
        },
        setAdmin(state, value) {
            state.isAdmin = value
        }
    }
})

new Vue({
    el: '#app',
    store: store,
    render: h => h(App),
    router
})

