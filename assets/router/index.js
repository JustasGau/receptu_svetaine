import Recipes from "../views/Recipes.vue";
import About from "../views/About.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Users from "../views/Users.vue";

const routes = [
    {
        path: "/",
        name: "Recipes",
        component: Recipes,
    },
    {
        path: "/user/recipes",
        name: "URecipes",
        component: Recipes,
        props: { filter: true }
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
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
    {
        path: "/users",
        name: "Users",
        component: Users,
    },
];

export default routes;