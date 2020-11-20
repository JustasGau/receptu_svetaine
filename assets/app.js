import { createApp } from "vue"
import { createStore } from 'vuex'
import App from './components/App'
import router from './router'
import './styles/app.css'

const store = createStore({
    state () {
        return {
            validated: true,
            token: ''
        }
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

const app = createApp(App).use(router)

app.use(store)
app.mount("#app")
