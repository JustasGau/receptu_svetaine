export default {
    install (Vue) {
        Vue.prototype.$fetcher = function (page, type, form) {
            return new Promise((resolve, reject) => {
                const token = this.$store.state.token
                const requestOptions = {
                    method: type,
                    headers: {'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token},
                };
                if (form) {
                    requestOptions.body = JSON.stringify(form)
                }
                fetch(this.$store.state.address + page, requestOptions)
                    .then((response) => {
                        return response.json()
                    }).then((data) => {
                        if (!data.code) {
                            console.log(data)
                            resolve(data)
                        } else {
                            this.$emit('show-error', data.message)
                        }
                    }
                )
            })
        }
    }
}