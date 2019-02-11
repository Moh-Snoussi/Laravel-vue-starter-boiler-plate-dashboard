
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';

import 'bootstrap-css-only/css/bootstrap.min.css'; // only bootstrap css , no jquery
import 'mdbvue/build/css/mdb.css'; // the best for frondend

import App from './components/auth/baseComponent.vue';
import Home from './components/auth/Home.vue';
import Register from './components/auth/Register.vue';
import Login from './components/auth/Login.vue';

import Dashboard from './components/Dashboard.vue'; // only authenticated user get acceces to this 


Vue.use(VueRouter);
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://localhost:8000/api/'; // all http methods will get out of this url 
const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'home',
        component: Home
    }, {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    }, {
        path: '/login',
        name: 'login',
        component: Login,
        props: (route) => ({ email: route.query.email, confirmed: route.query.confirmed, user: route.query.user }),
        meta: {
            auth: false
        }
    }, {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true // only authorized user
        }
    }],
    mode: 'history' // to have lean url without '#'
});
Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router
new Vue(App).$mount('#app');

// here where all start