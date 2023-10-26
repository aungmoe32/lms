import Vue from 'vue'
import router from './router.js'
import axios from 'axios'
import store from "./store";
import App from "./views/App.vue";
import vuetify from './vuetify';

// window.store = store
// window.axios = axios
axios.defaults.withCredentials = true;
// axios.defaults.baseURL = "http://127.0.0.1:8000/api";
axios.defaults.baseURL = window.location.origin + '/api'


axios.interceptors.response.use(undefined, function (error) {
    if (error) {
        // 401 = unauthorized
        // 403 unauthenticated
        // 419 page expire
        if (error.response.status === 403) {
            store.dispatch("Logout");
            router.push("/login");
            return Promise.reject()
        }
        if (error.response.status === 419) {
            router.push("/page-expired");
            return Promise.reject()

        }
    }
});


new Vue({
    store,
    router,
    vuetify,
    render: (h) => h(App),

}).$mount("#app");

