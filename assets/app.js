import Vue from "vue"
import Vuex from 'vuex'
import App from './components/App'
import VueRouter from "vue-router";
import routes from "./router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './styles/app.css'
import VueCookies from 'vue-cookies'

import fetcher from './plugins/fetcher'

import RecipeCard from './components/RecipeCard'
import ShowRecipe from './components/ShowRecipe'
import EditRecipe from './components/EditRecipe'
import AddRecipe from './components/AddRecipe'
import Comment from './components/CommentRecipe'
import VueJwtDecode from "vue-jwt-decode";

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Vuex)
Vue.use(fetcher)
Vue.use(VueCookies)
Vue.component('recipe-card', RecipeCard)
Vue.component('show-recipe', ShowRecipe)
Vue.component('comment-recipe', Comment)
Vue.component('edit-recipe', EditRecipe)
Vue.component('add-recipe', AddRecipe)

Vue.$cookies.config('30d')

const router = new VueRouter({
    routes
});

Vue.use(VueRouter)

const store = new Vuex.Store({
    state: {
        token: '',
        refreshToken: '',
        user: '',
        isAdmin: false,
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
            Vue.$cookies.set("refresh_token",token)
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

