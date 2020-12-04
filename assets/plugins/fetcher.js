import VueJwtDecode from "vue-jwt-decode";

let vueInst = null

function refreshToken(vue) {
    let context = null
    if (vue) context = vue
    else context = vueInst
    return new Promise((resolve, reject) => {
        console.log('Refreshing')
        const requestForm = { refresh_token: context.$cookies.get("refresh_token") }
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestForm)
        };
        fetch(context.$store.state.address + 'token/refresh', requestOptions)
            .then((response) => {
                return response.json()
            }).then((data) => {
                console.log(data.token)
                const decoded_token = VueJwtDecode.decode(data.token)
            context.$store.commit('setToken', data.token)
            context.$store.commit('setRefreshToken', data.refresh_token)
            context.$store.commit('setUser', decoded_token.username)
                if (decoded_token.roles[0] === "ROLE_ADMIN" || decoded_token.username === "testasName") {
                    context.$store.commit('setAdmin', true)
                }
                resolve(data)
            }
        )
    })
}

function workRequest (page, type, form, isImage) {
    if (vueInst === null) {
        vueInst = this
    }
    let context = this
    if (vueInst) context = vueInst
    return new Promise((resolve, reject) => {
        const token = context.$store.state.token
        const contentType = 'application/json'
        const requestOptions = {
            method: type,
            headers: {
                'Authorization': 'Bearer ' + token
            },
        }
        if (!isImage) {
            requestOptions.headers["Content-Type"] = contentType
        }
        if (form && !isImage) {
            requestOptions.body = JSON.stringify(form)
        } else if (form && isImage) {
            let data = new FormData()
            data.append('image', form)
            requestOptions.body = data
            console.log(data)
        }
        fetch(context.$store.state.address + page, requestOptions)
            .then((response) => {
                return response.json()
            }).then((data) => {
                console.log(data)
                if(data.errors) {
                    this.$emit('show-error', 'Klaida: ' + data.errors, 'danger')
                }
                if (!data.code) {
                    resolve(data)
                } else if (data.message == "Expired JWT Token") {
                    resolve(refreshToken(this).then(() => {
                        return workRequest(page, type, form)
                    }))
                } else {
                    context.$emit('show-error', 'Klaida: ' + data.message, 'danger')
                }
            }
        )
    })
}
export default {
    install (Vue) {
        Vue.prototype.$fetcher = workRequest,
        Vue.prototype.$refresh = refreshToken
    }
}