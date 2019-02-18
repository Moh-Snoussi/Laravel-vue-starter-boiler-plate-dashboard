/**
 * Centralized file
 * loads main libraries
 * load frond-end framework
 * load all component
 * initialize vue router
 * mounts all data in the welcome.blade.php
 * load all required libraries and components
 */

// main libraries 
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
var VueCookie = require('vue-cookie');
// Tell Vue to use the plugin

// front-end libraries
import 'bootstrap-css-only/css/bootstrap.min.css'; // only bootstrap css , no jquery
import 'mdbvue/build/css/mdb.css'; // the best for frond-end

import VueGoodTablePlugin from 'vue-good-table';
 
// import the styles 
import 'vue-good-table/dist/vue-good-table.css'
 
Vue.use(VueGoodTablePlugin);


import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'


// components
import App from './components/auth/baseComponent.vue';
import Logout from './components/auth/Logout.vue';
import Register from './components/auth/Register.vue';
import Login from './components/auth/Login.vue';

import Table from './components/dashboard/Table.vue';
import Dashboard from './components/dashboard/Dashboard.vue'; // only authenticated user get access to this 

/**
 * initializing the router 
 * every router get:
 * path : the url on the address bar 
 * name : name of the router that we later direct to the corresponding router (see auth/baseComponent.vue)
 * component : the component that is associated with the route
 */

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'home',
        component: Login
    }, {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    }, { // we get the query from the url and then we pass props to the login component. 
        path: '/login',
        name: 'login',
        component: Login,
        props: (route) => ({ email: route.query.email, confirmed: route.query.confirmed, user: route.query.user }),
        meta: {
            auth: false
        }
    }, {
        path: '/dashboard:id',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true // only authorized user
        }},
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
            meta: {
                auth: true // only authorized user
            }
    },
    {
        path: '/dashboard/history',
        name: 'Table',
        component: Table,
        meta: {
            auth: true // only authorized user
        }
}
],
    mode: 'history' // to have lean url without '#'
});
Vue.router = router

/**
 * setting all http methods to get out to the api url 
 */
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://localhost:8000/api/';

Vue.use(VueCookie);

/**
 * setting up the security
 * authentication methods 
 * Bearer JSON web token
 */
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router

/**
 * get the div with id #app and mounting all the app
 */
new Vue(App).$mount('#app');

// here where all start