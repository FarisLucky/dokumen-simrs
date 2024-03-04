import { createApp } from "vue";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import ArgonDashboard from "./argon-dashboard";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import VueProgressBar from "@aacassandra/vue3-progressbar";
import { createPinia } from 'pinia'
import VueLazyload from "vue-lazyload";
import VueImgOrientationChanger from "vue-img-orientation-changer";

// import the necessary css file
const options = {

    color: "#F56565",
    failedColor: "#874b4b",
    thickness: "7px",
    transition: {
        speed: "0.5s",
        opacity: "0.8s",
        termination: 300,
    },
    autoRevert: true,
    location: "top",
    inverse: false,
};

const loadimage = require('./assets/img/loading.gif')
const errorimage = require('./assets/img/error.gif')

const pinia = createPinia()

const app = createApp(App);
app.use(pinia);
app.use(store);
app.use(router);
app.use(ArgonDashboard);
app.use(VueProgressBar, options);
app.use(VueLazyload, {
    preLoad: 1.3,
    error: errorimage,
    loading: loadimage,
    attempt: 1
})
app.use(VueImgOrientationChanger);
app.component("v-select", vSelect);
app.mount("#app");

