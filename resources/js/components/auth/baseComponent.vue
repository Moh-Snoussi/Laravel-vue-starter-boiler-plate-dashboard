<template>
  <div id="app" class="flyout">
       <div v-if="!$auth.ready()" >Loading
    <object  id="loader" data="svg/circles.svg"></object>
    </div>
    <div v-if="$auth.ready()">
     
    <sidebar-menu style="z-index: 10;" @collapse="handelCollapse()" v-if="$auth.check()" :menu="menu" />
    <mdb-navbar v-if="!$auth.check()" dark position="top" class="default-color" scrolling :scrollingOffset="20">
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
 *
 */
import { SidebarMenu } from './../sidebar'
import Avatar from "./../sidebar/Avatar";
import profileName from "./../sidebar/profileName";

import {
  mdbNavbar,
  mdbNavItem,
  mdbNavbarNav,
  mdbNavbarToggler,
  mdbNavbarBrand,
} from "mdbvue";

import FooterPage from "./../Footer";
import { async } from 'q';
export default {
  name: "app",
  data: {
    collapse:false
  },

  components: {
    mdbNavbar,
    mdbNavItem,
    mdbNavbarNav,
    mdbNavbarToggler,
    mdbNavbarBrand,
    FooterPage,
    SidebarMenu
  },data() { 
            return {
              collapse: false,
                menu: [ // assigning side bar data 
                    {
                        header: true,
                        component: Avatar ,
                         visibleOnCollapse: true,
                      
                    },
                    {
                        header: true,
                        tittle : '' ,
                         visibleOnCollapse: false
                    },
                    {
                        href: '/dashboard/balance',
                        title: 'Dashboard',
                        icon: 'fa fa-user',
                        
                        
                    }, {
                        title: 'Deposit',
                        icon: 'fa fa-file-import',
                       
                    },
                    {
                        title: 'Withdraw',
                        icon: 'fa fa-file-export',
                       
                    },
                    
                    {
                        title: 'Transaction',
                        icon: 'fa fa-share-square',
                       
                    },
                    {
                        title: 'History',
                        icon: 'fa fa-list-alt',
                        href: '/dashboard/history'
                       
                    },{
                        title: 'Log out',
                        icon: 'fa fa-door-open',
                        href: '/logout'
                       
                    }
                ]
            }
        },
  methods: {
    /**
     * getting the props from child component and assigned to this component data 
     * editing the url to handel menu collapse from other component qs yell 
     */
    handelCollapse() {
    this.collapse = !this.collapse;
   this.$router.push(`/dashboard?collapse=${this.collapse}`)
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
