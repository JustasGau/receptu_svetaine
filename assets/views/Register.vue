
<template>
  <div>
    <b-form id="login-form" @submit.stop.prevent="onSubmit">
      <b-form-group
          id="input-group-1"
          label="Slapyvardis:"
          label-for="input-1"
      >
        <b-form-input
            id="input-1"
            v-model="form.username"
            type="text"
            required
            placeholder="Slapyvardis"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Slaptažodis:" label-for="input-2">
        <b-form-input
            id="input-2"
            v-model="form.password"
            required
            placeholder="Slaptažodis"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-3" label="Epaštas:" label-for="input-2">
        <b-form-input
            id="input-3"
            v-model="form.email"
            type="email"
            required
            placeholder="Epaštas"
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">Prisiregistruoti</b-button>
    </b-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      form: {
        username: '',
        password: '',
        email: ''
      },
    }
  },
  methods: {
    onSubmit () {
      const requestOptions = {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(this.form)
      };
      fetch(this.$store.state.address.substring(0, this.$store.state.address.length - 4) + "register", requestOptions)
          .then((response) => {
            return response.json()
          }).then((data) => {
            if (data.errors) {
              this.$emit('show-error', 'Įvyko klaida: ' + data.errors, 'danger')
            } else {
              this.$router.push('/')
              this.$emit('show-error', 'Sėkmingai prisiregistruota', 'success')
            }
          })
    }
  }
}
</script>

<style>
#login-form {
  width: 50%;
  margin: 40px auto;
}
</style>