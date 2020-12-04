<template>
  <div id="app">
    <div id="main-content">
      <header> <img src="/images/recipe.svg" style="max-width: 22px" alt="Kiwi standing on oval"> RECEPTŲ SVETAINĖ </header>
      <b-navbar toggleable="lg" type="dark" variant="info">
        <b-navbar-brand to="/">
          Receptai
        </b-navbar-brand>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav>
            <b-nav-item to="/about">Apie</b-nav-item>
            <b-nav-item v-if="$store.state.isAdmin && $store.state.user" to="/users">Vartotojai</b-nav-item>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto">
            <b-nav-item-dropdown right>
              <template #button-content>
                <em>{{ $store.state.user || 'Vartotojas' }}</em>
              </template>
              <b-dropdown-item v-if="$store.state.user" to="/user/recipes">Receptai</b-dropdown-item>
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

    <footer id="footer"> Visos teisės: Justo Gauryliaus - nevokit</footer>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Sansita+Swashed:wght@600&display=swap" rel="stylesheet">
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
      this.$cookies.remove("refresh_token")
      this.$router.push('/')
      this.showAlert('Sėkmingai atsijungta', 'success')
    }
  },
  created () {
    this.$refresh()
  },
  mounted () {
  }
}

/*
TODO
11 loading animation
mobile
deploy
ataskaita
*/
</script>

<style>
  html,body {
    margin:0;
    padding:0;
    overflow-x: hidden;
    height: 100%;
  }
  #content {
    margin-left: 20px;
  }
  header {
    font-family: 'Sansita Swashed', cursive;
    background: #17a2b8 ;
    text-align: center;
  }
  #footer {
    font-family: 'Sansita Swashed', cursive;
    width: 100%;
    height: 30px;
    background: #17a2b8;
    font-size: 14px;
    position: fixed;
    bottom: 0;
  }
  b-alert {
    position: absolute;
  }

</style>
