import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createPinia } from "pinia";
// import Home from "./Home.vue";
import "./bootstrap";
import App from "./App.vue";
import router from "./router.js";
// import "./index.css";
// import VueApexCharts from "vue3-apexcharts";
// window.Apex.chart = { fontFamily: "Poppins, sans-serif" };

const pinia = createPinia();
const app = createApp(App);
// app.use(VueApexCharts);
app.use(router);
app.use(pinia);
app.mount("#app");
