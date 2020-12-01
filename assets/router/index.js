import Home from "../views/Recipes.vue";
import About from "../views/About.vue";
import Login from "../views/Login.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/about",
        name: "About",
        component: About,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
];

export default routes;