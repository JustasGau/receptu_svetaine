import VueJwtDecode from "vue-jwt-decode";

function refreshToken() {
    return new Promise((resolve, reject) => {
        const requestForm = { refresh_token: this.$cookies.get("refresh_token") }
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestForm)
        };
        fetch(this.$store.state.address + 'token/refresh', requestOptions)
            .then((response) => {
                return response.json()
            }).then((data) => {
                const decoded_token = VueJwtDecode.decode(data.token)
                this.$store.commit('setToken', data.token)
                this.$store.commit('setRefreshToken', data.refresh_token)
                this.$store.commit('setUser', decoded_token.username)
                if (decoded_token.roles[0] === "ROLE_ADMIN") {
                    this.$store.commit('setAdmin', true)
                }
                resolve(data)
            }
        )
    })
}

function workRequest (page, type, form, isImage) {
    return new Promise((resolve, reject) => {
        const token = this.$store.state.token
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
            console.log("1")
        } else if (form && isImage) {
            let data = new FormData()
            data.append('image', form)
            requestOptions.body = data
            console.log(data)
        }
        fetch(this.$store.state.address + page, requestOptions)
            .then((response) => {
                return response.json()
            }).then((data) => {
                console.log(data)
                if (!data.code) {
                    resolve(data)
                } else if (data.message == "Expired JWT Token") {
                    refreshToken().then(() => {
                        return workRequest(page, type, form)
                    })
                } else {
                    this.$emit('show-error', 'Klaida: ' + data.message, 'danger')
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