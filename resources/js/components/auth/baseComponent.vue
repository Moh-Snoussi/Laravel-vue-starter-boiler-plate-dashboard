<template>
  <div id="app" class="flyout">
    <mdb-navbar dark position="top" class="default-color" scrolling :scrollingOffset="20">
      <mdb-navbar-brand to="/" waves style="font-weight: bolder;">ATM</mdb-navbar-brand>
      <mdb-navbar-toggler>
        <mdb-navbar-nav right>
          <mdb-nav-item v-if="!$auth.check()" to="/login" waves-fixed>Login</mdb-nav-item>
          <mdb-nav-item v-if="!$auth.check()" to="/register" waves-fixed>Register</mdb-nav-item>
          <mdb-nav-item class="pull-right" to="/home" v-if="$auth.check()" waves-fixed>
            <li @click.prevent="$auth.logout()">logout</li>
          </mdb-nav-item>
        </mdb-navbar-nav>
      </mdb-navbar-toggler>
    </mdb-navbar>
    <main :style="{marginTop: '60px'}">
      <router-view></router-view>
    </main>

    <FooterPage footer-expand-lg color="default-color"></FooterPage>
  </div>
</template>

<script>
import {
  mdbNavbar,
  mdbNavItem,
  mdbNavbarNav,
  mdbNavbarToggler,
  mdbNavbarBrand
} from "mdbvue";

import FooterPage from "./../Footer";
export default {
  name: "app",
  data: {
    website: "somekindofwebsite"
  },

  components: {
    mdbNavbar,
    mdbNavItem,
    mdbNavbarNav,
    mdbNavbarToggler,
    mdbNavbarBrand,
    FooterPage
  },
  methods: {
    // logout method
    loggingout: function() {
      this.$auth.logout({
        makeRequest: true,
        params: {}, // data: {} in axios
        success: function() {},
        error: function() {},
        url: "api/auth/logout", // logout from controller and revoke token from datbase
        redirect: "/"
      });
    }
  }
};
</script>

<style>
.flyout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  justify-content: space-between;
}
.active {
  background-color: rgba(255, 255, 255, 0.1);
}
.demo-section {
  padding: 20px 0;
}
.demo-section > section {
  border: 1px solid #e0e0e0;
  padding: 15px;
}
.demo-section > h4 {
  font-weight: bold;
  margin-bottom: 20px;
}
.demo-title {
  color: #9e9e9e;
  font-weight: 700;
  margin-bottom: 0;
  padding-left: 15px;
}
</style>
