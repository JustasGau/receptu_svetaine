<template>
  <div id="app">
    <div id="main-content">
      <header> RECEPTAI </header>
      <b-navbar toggleable="lg" type="dark" variant="info">
        <b-navbar-brand to="/">Receptai</b-navbar-brand>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav>
            <b-nav-item to="/about">Apie</b-nav-item>
            <b-nav-item v-if="$store.state.isAdmin" to="/users">Vartotojai</b-nav-item>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto">
            <b-nav-item-dropdown right>
              <template #button-content>
                <em>{{ $store.state.user || 'Vartotojas' }}</em>
              </template>
              <b-dropdown-item to="/user/recipes">Receptai</b-dropdown-item>
              <b-dropdown-item v-if="!$store.state.user" to="/login">Prisijungti</b-dropdown-item>
              <b-dropdown-item v-if="$store.state.user" @click="logout">Atsijungti</b-dropdown-item>
              <b-dropdown-item to="/register">Prisiregistruoti</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
    </div>
    <!--    ALERT    -->
    <b-alert
        :show="dismissCountDown"
        dismissible
        fade
        :variant="alertType"
        @dismiss-count-down="countDownChanged"
    >
      {{ errorText }}
    </b-alert>
    <!--    ALERT    -->
    <router-view id="content" @show-error="showAlert"/>
    <footer> Visos teisės: Justo Gauryliau - nevokit</footer>
  </div>
</template>

<script>
export default {
  data () {
    return {
      dismissSecs: 5,
      dismissCountDown: 0,
      errorText: '',
      alertType: 'danger'
    }
  },
  methods: {
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert(text, type) {
      this.errorText = text
      this.dismissCountDown = this.dismissSecs
      this.alertType = type
    },
    logout () {
      this.$store.commit('setToken', '')
      this.$store.commit('setUser', '')
      this.$store.commit('setAdmin', false)
      this.$router.push('/')
      this.showAlert('Sėkmingai atsijungta', 'success')
    }
  },
  created () {

  },
  mounted () {
  }
}

/*
TODO
11animation
ingridientu trynimas
cookies del tokeno
mobile
*/
</script>

<style>
  html,body {
    margin:0;
    padding:0;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  #main-content, #content {
    flex: 1 0 auto;
  }
  header {
    background: #6cf;
    text-align: center;
  }
  footer {
    width: 100%;
    height: 30px;
    background: #6cf;
    font-size: 14px;
    flex-shrink: 0;
  }
  b-alert {
    position: absolute;
  }

</style>
