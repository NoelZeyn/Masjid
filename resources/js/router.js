import { createRouter, createWebHistory } from "vue-router";

// Import components
import Login from "./pages/Authentication/Login.vue";
import Register from "./pages/Authentication/Register.vue";
import Dashboard from "./pages/Dashboard.vue";
import LoginTransition from "./pages/Authentication/LoginTransition.vue";
import RegisterNext from "./pages/Authentication/Register-next.vue";
import Feature from "./pages/Feature.vue";
import Profile from "./pages/Profile/Profile.vue";
import ProfileEdit from "./pages/Profile/Profile-edit.vue";
import UbahPassword from "./pages/Profile/UbahPassword.vue";

// Fungsi validasi token
const isTokenValid = () => {
    const token = localStorage.getItem("token");
    const expiry = localStorage.getItem("token_expiry");
    return token && expiry && Date.now() < parseInt(expiry);
};

// Daftar route dengan pemisahan yang memerlukan token dan yang tidak
const routes = [
    // Public routes (Tidak memerlukan token)
    { path: "/", redirect: "/register",meta: { title: "Register" } },
    { path: "/login", component: Login, meta: { title: "Login" } },
    { path: "/register", component: Register, meta: { title: "Register" } },
    { path: "/feature", name: "Feature", component: Feature, meta: { title: "Feature" } },
    { path: "/profile", name: "Profile", component: Profile, meta: { title: "Profile" } },
    { path: "/profile-edit", name: "ProfileEdit", component: ProfileEdit, meta: { title: "Profile Edit" } },
    {
        path: "/register-next",
        component: RegisterNext,
        meta: { title: "RegisterNext" },
    },
    {path: "/loginTransition", component: LoginTransition, meta: { title: "Login Transition" }},
    { path: "/dashboard", component: Dashboard, meta: { title: "Dashboard" } },
    { path: "/ubah-password", component: UbahPassword, meta: { title: "Password Change" } },
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
