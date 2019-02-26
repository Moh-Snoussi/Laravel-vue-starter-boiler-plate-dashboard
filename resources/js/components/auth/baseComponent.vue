<template>
  <div id="app" class="flyout" v-on:click.stop="handelCollapse(true)">
    <div v-if="!$auth.ready()">
      Loading
      <object id="loader" data="svg/circles.svg"></object>
    </div>

    <div v-if="$auth.ready()">
      <div v-if="$auth.check()" style="height:35px;width:100%;background-color:#7dc6f6;">
        <div id="clock">
          <p class="date">
            {{ clock.date }}
            {{ clock.time }}
          </p>
        </div>
      </div>

      <sidebar-menu
        style="z-index: 10;"
        @collapse="handelCollapse()"
        v-bind:collapsed="collapse"
        v-if="$auth.check()"
        :menu="menu"
      />

      <mdb-navbar
        v-if="!$auth.check()"
        dark
        position="top"
        class="default-color"
        scrolling
        :scrollingOffset="20"
      >
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
    </div>
  </div>
</template>

<script>
/**
 * baseComponent is the route in action
 * checks authentication before redirect
 * it redirects to the name of the router that are defined on app.js
 * this is base component always on
 * it holds the sidebar
 *
 */
import { SidebarMenu } from "./../sidebar";
import Avatar from "./../sidebar/Avatar";
import profileName from "./../sidebar/profileName";
import Logout from "./../auth/Logout";
import { async } from "q";

import {
  mdbNavbar,
  mdbNavItem,
  mdbNavbarNav,
  mdbNavbarToggler,
  mdbNavbarBrand
} from "mdbvue";

export default {
  name: "app",

  components: {
    mdbNavbar,
    mdbNavItem,
    mdbNavbarNav,
    mdbNavbarToggler,
    mdbNavbarBrand,
    SidebarMenu
  },
  props: ["collapsed"],
  data() {
    return {
      bodyClick: 0,
      collapse: true,
      week: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"],
      clock: {
        time: "",
        date: ""
      },

      menu: [
        // assigning side bar data
        {
          header: true,
          component: Avatar,
          visibleOnCollapse: true
        },
        {
          header: true,
          tittle: "",
          visibleOnCollapse: false
        },
        {
          href: "/dashboard",
          title: "Dashboard",
          icon: "fa fa-user"
        },
        {
          title: "Deposit",
          icon: "fa fa-file-import",
          href: "/dashboard/deposit"
        },
        {
          title: "Withdraw",
          icon: "fa fa-file-export",
          href: "/dashboard/withdraw"
        },

        {
          title: "Transaction",
          icon: "fa fa-share-square",
          href: "/dashboard/transaction"
        },
        {
          title: "History",
          icon: "fa fa-list-alt",
          href: "/dashboard/history"
        },
        {
          title: "Log out",
          icon: "fa fa-door-open",
          href: "/logout"
        }
      ]
    };
  },
  /**
   * This function will watch the url on the route
   * and pass the collapse value accordingly
   *
   */
  watch: {
    $route: function() {
      this.collapse =
        this.$route.query.collapse == "false" ||
        this.$route.query.collapse == "true"
          ? this.$route.query.collapse !== "false"
            ? true
            : false
          : this.collapse;
    }
  },
  methods: {
    /**
     * getting the props from child component and assigned to this component data
     * editing the url to handel menu collapse from other component qs yell
     */
    handelCollapse(close = false) {
      close ? (this.collapse = true) : (this.collapse = !this.collapse);
      this.$router.push(`?collapse=${this.collapse}`);
    },

    /**
     * Javascript watch on the right of the screen
     */

    timerID() {
      setInterval(this.updateTime, 1000);
    },

    updateTime() {
      let cd = new Date();
      let zeroPadding = this.zeroPadding;
      this.clock.time =
        zeroPadding(cd.getHours(), 2) +
        ":" +
        zeroPadding(cd.getMinutes(), 2) +
        ":" +
        zeroPadding(cd.getSeconds(), 2);
      this.clock.date =
        zeroPadding(cd.getFullYear(), 4) +
        "-" +
        zeroPadding(cd.getMonth() + 1, 2) +
        "-" +
        zeroPadding(cd.getDate(), 2) +
        " " +
        this.week[cd.getDay()];
    },

    zeroPadding(num, digit) {
      var zero = "";
      for (var i = 0; i < digit; i++) {
        zero += "0";
      }
      return (zero + num).slice(-digit);
    }
  },
  mounted() {
    this.timerID();
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
#clock {
  color: #ffffff;
  text-align: right;
  margin-right: 15px;
  font-weight: 400;
  left: 50%;
  top: 50%;
  margin-left: 50px;
  padding-top: 8px;
}

.date {
  letter-spacing: 0.1em;
  font-size: 12px;
  text-shadow: 30 0 20px rgb(0, 54, 71), 0 0 20px rgba(10, 175, 230, 0);
}
</style>
