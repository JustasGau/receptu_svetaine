
<template>
  <div>
    <b-form @submit="onSubmit">
      <b-form-group
          id="input-group-1"
          label="Username:"
          label-for="input-1"
      >
        <b-form-input
            id="input-1"
            v-model="form.username"
            type="text"
            required
            placeholder="Username"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Password:" label-for="input-2">
        <b-form-input
            id="input-2"
            v-model="form.password"
            required
            placeholder="Password"
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">Submit</b-button>
    </b-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      form: {
        username: '',
        password: ''
      },
    }
  },
  methods: {
    onSubmit () {
      console.log(this.$store.state.address + "login_check")
      const requestOptions = {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(this.form)
      };
      fetch(this.$store.state.address + "login_check", requestOptions)
          .then((response) => {
            return response.json()
          }).then((data) => {
            if (data.token){
                this.$store.commit('validate')
                this.$store.commit('setToken', data.token)
                this.$router.push('/')
              }
          }
      )
    }
  }
}
</script>