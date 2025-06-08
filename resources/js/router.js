import { createRouter, createWebHistory } from "vue-router";

// Import components
import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import Dashboard from "./pages/Dashboard.vue";
import LoginTransition from "./pages/LoginTransition.vue";
import RegisterNext from "./components/Register-next.vue";

// Fungsi validasi token
const isTokenValid = () => {
    const token = localStorage.getItem("token");
    const expiry = localStorage.getItem("token_expiry");
    return token && expiry && Date.now() < parseInt(expiry);
};

// Daftar route dengan pemisahan yang memerlukan token dan yang tidak
const routes = [
    // Public routes (Tidak memerlukan token)
    { path: "/", redirect: "/register", meta: { title: "Login" } },
    { path: "/login", component: Login, meta: { title: "Login" } },
    { path: "/register", component: Register, meta: { title: "Register" } },
    {
        path: "/register-next",
        component: RegisterNext,
        meta: { title: "RegisterNext" },
    },
    {path: "/loginTransition", component: LoginTransition, meta: { title: "Login Transition" }},
    { path: "/dashboard", component: Dashboard, meta: { title: "Dashboard" } },
];

// Membuat router dengan history mode
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Middleware untuk memastikan token validasi sebelum melanjutkan
router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !isTokenValid()) {
        next("/login");
    } else {
        next();
    }
});

// Set judul halaman jika tersedia di meta
router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = to.meta.title;
    }
});

export default router;
