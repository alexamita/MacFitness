import { createApp } from "vue"; // Function that starts our Vue app
import { createPinia } from "pinia"; // "Global brain" to store our data

// UI Framework (Vuetify) imports
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import * as labsComponents from 'vuetify/labs/components'
import "@mdi/font/css/materialdesignicons.css";


import "./assets/main.css";

// Components
import App from "./App.vue";

const vuetify = createVuetify({
    components:{
        ...components,
        ...labsComponents,
    },
    directives,
        icons: {
            defaultSet: "mdi",
        },
        theme: {
            defaultTheme: "light",
            themes: {
                light:{
                    colors:{
                        primary:"#ee6909",
                    }
                }
            }
        },
});

import router from "./router";

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(vuetify);
app.mount("#app");
