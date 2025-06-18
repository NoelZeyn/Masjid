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
import Acara from "./pages/Acara/Acara.vue";
import AcaraInfo from "./pages/Acara/Acara-info.vue";
import AcaraDashboard from "./pages/Acara/AcaraDashboard.vue";
import AcaraEdit from "./pages/Acara/Acara-edit.vue";
import AcaraAdd from "./pages/Acara/Acara-add.vue";
import DokumentasiAcara from "./pages/Acara/DokumentasiAcara.vue";
import DokumentasiAcaraDetail from "./pages/Acara/DokumentasiAcara-info.vue";
import DokumentasiAcaraEdit from "./pages/Acara/DokumentasiAcara-edit.vue";
import DokumentasiAcaraAdd from "./pages/Acara/DokumentasiAcara-add.vue";
import PesertaAcara from "./pages/Acara/PesertaAcara.vue";
import InventarisDashboard from "./pages/Inventaris/InventarisDashboard.vue";
import Supplier from "./pages/Inventaris/Supplier.vue";
import SupplierEdit from "./pages/Inventaris/Supplier-edit.vue";
import SupplierInfo from "./pages/Inventaris/Supplier-info.vue";
import SupplierAdd from "./pages/Inventaris/Supplier-add.vue";

// Fungsi validasi token
const isTokenValid = () => {
    const token = localStorage.getItem("token");
    const expiry = localStorage.getItem("token_expiry");

    // Jika token atau expiry tidak ada
    if (!token || !expiry) {
        localStorage.clear();
        sessionStorage.clear();
        return false;
    }

    // Jika waktu sudah habis
    if (Date.now() >= parseInt(expiry)) {
        localStorage.clear();
        sessionStorage.clear();
        return false;
    }

    return true;
};


// Daftar route
const routes = [
    // Public routes (tidak butuh token)
    { path: "/", redirect: "/register", meta: { title: "Register" } },
    { path: "/login", component: Login, meta: { title: "Login" } },
    { path: "/register", component: Register, meta: { title: "Register" } },
    { path: "/register-next", component: RegisterNext, meta: { title: "RegisterNext" } },
    { path: "/loginTransition", component: LoginTransition, meta: { title: "Login Transition" } },

    // Protected routes (butuh token)
    { path: "/dashboard", component: Dashboard, meta: { requiresAuth: true, title: "Dashboard" } },
    { path: "/feature", name: "Feature", component: Feature, meta: { requiresAuth: true, title: "Feature" } },
    { path: "/profile", name: "Profile", component: Profile, meta: { requiresAuth: true, title: "Profile" } },
    { path: "/profile-edit", name: "ProfileEdit", component: ProfileEdit, meta: { requiresAuth: true, title: "Profile Edit" } },
    { path: "/ubah-password", component: UbahPassword, meta: { requiresAuth: true, title: "Password Change" } },

    { path: "/acara-dashboard", name: "AcaraDashboard", component: AcaraDashboard, meta: { requiresAuth: true, title: "Acara Dashboard" } },
    { path: "/acara", name: "Acara", component: Acara, meta: { requiresAuth: true, title: "Acara" } },
    { path: "/acara-add", name: "AcaraAdd", component: AcaraAdd, meta: { requiresAuth: true, title: "Acara Add" } },
    { path: "/acara-informasi/:id", name: "AcaraInfo", component: AcaraInfo, meta: { requiresAuth: true, title: "Acara Informasi" } },
    { path: "/acara-edit/:id", name: "AcaraEdit", component: AcaraEdit, meta: { requiresAuth: true, title: "Acara Edit" } },
    
    { path: "/dokumentasi-acara", name: "DokumentasiAcara", component: DokumentasiAcara, meta: { requiresAuth: true, title: "Dokumentasi Acara" } },
    { path: "/dokumentasi-acara-informasi/:id", name: "DokumentasiAcaraDetail", component: DokumentasiAcaraDetail, meta: { requiresAuth: true, title: "Dokumentasi Acara Detail" } },
    { path: "/dokumentasi-acara-edit/:id", name: "DokumentasiAcaraEdit", component: DokumentasiAcaraEdit, meta: { requiresAuth: true, title: "Dokumentasi Acara Edit" } },
    { path: "/dokumentasi-acara-add", name: "DokumentasiAcaraAdd", component: DokumentasiAcaraAdd, meta: { requiresAuth: true, title: "Dokumentasi Acara Add" } },
    
    { path: "/peserta-acara", name: "Peserta", component: PesertaAcara, meta: { requiresAuth: true, title: "Peserta Acara" } },
    { path: "/dokumentasi-acara-informasi/:id", name: "DokumentasiAcaraDetail", component: DokumentasiAcaraDetail, meta: { requiresAuth: true, title: "Dokumentasi Acara Detail" } },
    { path: "/dokumentasi-acara-edit/:id", name: "DokumentasiAcaraEdit", component: DokumentasiAcaraEdit, meta: { requiresAuth: true, title: "Dokumentasi Acara Edit" } },
    { path: "/dokumentasi-acara-add", name: "DokumentasiAcaraAdd", component: DokumentasiAcaraAdd, meta: { requiresAuth: true, title: "Dokumentasi Acara Add" } },

    { path: "/inventaris-dashboard", name: "InventarisDashboard", component: InventarisDashboard, meta: { requiresAuth: true, title: "Inventaris Dashboard" } },
    { path: "/supplier", name: "Supplier", component: Supplier, meta: { requiresAuth: true, title: "Supplier" } },
    { path: "/supplier-add", name: "SupplierAdd", component: SupplierAdd, meta: { requiresAuth: true, title: "Supplier Add" } },
    { path: "/supplier-edit/:id", name: "SupplierEdit", component: SupplierEdit, meta: { requiresAuth: true, title: "Supplier Edit" } },
    { path: "/supplier-informasi/:id", name: "SupplierInfo", component: SupplierInfo, meta: { requiresAuth: true, title: "Supplier Informasi" } },
    
];

// Membuat router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Middleware validasi token
router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !isTokenValid()) {
        next("/login");
    } else {
        next();
    }
});

// Set judul halaman
router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = to.meta.title;
    }
});

export default router;
