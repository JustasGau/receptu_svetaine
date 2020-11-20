<template>
  <div id="app">
    <div v-if="authenticated" id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </div>
    <router-view />
  </div>
</template>

<script>
export default {
  data () {
    return {
      authenticated: false
    }
  },
  created () {
    this.authenticated = this.$store.state.validated
  },
  mounted () {
    if(!this.authenticated) {
      this.$router.push('login')
    }
  }
}
</script>
