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
        token: '',
        refreshToken: '',
        user: '',
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
        }
    }
})

new Vue({
    el: '#app',
    store: store,
    render: h => h(App),
    router
})

