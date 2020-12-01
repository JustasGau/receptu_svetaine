function refreshToken() {
    return new Promise((resolve, reject) => {
        const requestForm = { refresh_token: this.$store.state.refreshToken }
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestForm)
        };
        fetch(this.$store.state.address + '/token/refresh', requestOptions)
            .then((response) => {
                return response.json()
            }).then((data) => {
                this.$store.commit('setToken', data.token)
                this.$store.commit('setRefreshToken', data.refresh_token)
                resolve(data)
            }
        )
    })
}

function workRequest (page, type, form) {
    return new Promise((resolve, reject) => {
        const token = this.$store.state.token
        const requestOptions = {
            method: type,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
        };
        if (form) {
            requestOptions.body = JSON.stringify(form)
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
        Vue.prototype.$fetcher = workRequest
    }
}