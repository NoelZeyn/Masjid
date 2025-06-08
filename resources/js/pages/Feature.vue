<template>
    <div class="flex flex-wrap h-screen bg-gray-100">
        <!-- Sidebar -->
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />

        <!-- Main Content -->
        <div class="flex-1 p-8 pt-4 bg-white overflow-y-auto">
            <HeaderBar title="Dashboard" />
            <div class="my-4 border-b border-gray-300 pt-1"></div>

            <!-- Header Section -->
            <div class="flex flex-wrap justify-between items-center mt-[-10px] pt-5 gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Selamat Datang, {{ userRole }} {{ userName }} 👋
                    </h3>
                </div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-lg border text-sm font-bold text-gray-800 bg-white border-gray-300">
                    <img class="w-5 h-5" :src="iconKalender" alt="icon" />
                    <span>{{ formattedDate }}</span>
                </div>
            </div>

            <!-- Categorized Menus -->
            <div class="space-y-10 mt-6">
                <div v-for="section in categorizedMenus" :key="section.title">
                    <h2 class="text-lg font-bold text-gray-700 mb-2">
                        {{ section.icon }} {{ section.title }}
                    </h2>

                    <!-- Responsive Grid Menu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div v-for="menu in section.menus" :key="menu.title"
                            class="flex items-center gap-3 p-5 rounded-xl bg-white border border-gray-200 shadow-lg hover:bg-blue-50 active:bg-blue-700 active:text-white transition-all cursor-pointer"
                            @click="navigateTo(menu.path)">
                            <div class="flex items-center justify-center">
                                <img :src="menu.icon" class="w-6 h-6 object-cover" :alt="menu.title" />
                            </div>
                            <p class="font-semibold text-blue-900">{{ menu.title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import iconKalender from "@/assets/kalender.svg";
import iconPosyandu from "@/assets/posko.svg";

export default {
    name: "FeatureComponent",
    components: {
        Sidebar,
        HeaderBar,
    },
    data() {
        return {
            activeMenu: "feature",
            iconKalender,
            userName: "",
            userRole: "",
            today: new Date(),
            categorizedMenus: [
                {
                    title: "Verifikasi",
                    icon: "🟢",
                    menus: [
                        // { icon: iconPosyandu, title: "Admin", path: "/admin" },
                        // { icon: iconPosyandu, title: "Warga", path: "/warga" },
                        { icon: iconPosyandu, title: "Data Diri", path: "/data-diri" },
                        { icon: iconPosyandu, title: "Kritik & Saran", path: "/kritik-saran" },
                        { icon: iconPosyandu, title: "Log Aktivitas", path: "/log-aktifitas" },
                        { icon: iconPosyandu, title: "Soft Delete", path: "/soft-delete" },
                    ],
                },
                {
                    title: "Acara",
                    icon: "🟣",
                    menus: [
                        { icon: iconPosyandu, title: "Acara", path: "/acara" },
                        { icon: iconPosyandu, title: "Peserta Acara", path: "/peserta-acara" },
                        { icon: iconPosyandu, title: "Dokumentasi Acara", path: "/dokumentasi-acara" },
                    ],
                },
                {
                    title: "Inventaris",
                    icon: "🟣",
                    menus: [
                        { icon: iconPosyandu, title: "Barang", path: "/acara" },
                        { icon: iconPosyandu, title: "Pengajuan", path: "/pengajuan" },
                        { icon: iconPosyandu, title: "Riwayat Penggunaan", path: "/riwayat-penggunaan" },
                        { icon: iconPosyandu, title: "Supplier", path: "/supplier" },
                        { icon: iconPosyandu, title: "Transaksi", path: "/transaksi" },
                        { icon: iconPosyandu, title: "Detail Transaksi", path: "/detail-transaksi" },
                    ],
                },
                {
                    title: "Keuangan",
                    icon: "🔵",
                    menus: [
                        { icon: iconPosyandu, title: "Keuangan", path: "/keuangan" },
                        { icon: iconPosyandu, title: "Pemasukan", path: "/keuangan/pemasukan" },
                        { icon: iconPosyandu, title: "Pengeluaran", path: "/keuangan/pengeluaran" },
                        { icon: iconPosyandu, title: "Infaq", path: "/infaq" },
                    ],
                },
                {
                    title: "Kurban",
                    icon: "🟡",
                    menus: [
                        { icon: iconPosyandu, title: "Kurban", path: "/kurban" },
                        { icon: iconPosyandu, title: "Kurban Warga", path: "/kurban-warga" },
                        { icon: iconPosyandu, title: "Riwayat Penyembelihan", path: "/riwayat-penyembelihan" },
                    ],
                },
            ],
        };
    },
    computed: {
        formattedDate() {
            const options = { day: "numeric", month: "long" };
            const dateStr = this.today.toLocaleDateString("id-ID", options);
            const [day, month] = dateStr.split(" ");
            return `${month} ${day}`;
        },
    },
    methods: {
        updateActiveMenu(menu) {
            this.activeMenu = menu;
        },
        navigateTo(path) {
            if (path) this.$router.push(path);
        },
    },
    mounted() {
        this.userName = localStorage.getItem("userName") || "";
        this.userRole = localStorage.getItem("userRole") || "";
    },
};
</script>
