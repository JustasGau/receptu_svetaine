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
            <b-nav-item to="/about">Vartotojai</b-nav-item>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto">
            <b-nav-item-dropdown right>
              <template #button-content>
                <em>Vartotojas</em>
              </template>
              <b-dropdown-item to="#">Receptai</b-dropdown-item>
              <b-dropdown-item to="#">Atsijungti</b-dropdown-item>
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
        variant="danger"
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
      authenticated: false,
      errorText: ''
    }
  },
  methods: {
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert(text) {
      this.errorText = text
      this.dismissCountDown = this.dismissSecs
    }
  },
  created () {
    this.authenticated = this.$store.state.validated
  },
  mounted () {
    this.authenticated = true //TODO remove line
    if(!this.authenticated) {
      this.$router.push('login')
    }
  }
}

/*
TODO
1 grazus login
2 neforsuot login guestams
2logout
3auto token refresh

6admin view to edit users
7Add/edit/remove recipe
8Add/edit/remove ingredients
9add/edit/remove comments
11animation
12admin differentiation
13user editing
14image uploading
15Comment user picture?
komentaru pagination
komentuoja tik prisiregistrave
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
