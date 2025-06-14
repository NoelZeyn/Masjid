<template>
    
    <div class="flex flex-wrap h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />

    <div class="flex-1 p-8 pt-4 bg-white">
      <HeaderBar title="Acara Dashboard" class="mt-3"/>
      <div class="my-4 border-b border-gray-300"></div>

            <!-- Header -->
            <div class="flex flex-wrap justify-between items-center pt-3 gap-2">
                <h3 class="text-lg font-semibold text-gray-800">
                    Selamat Datang, {{ userRole }} {{ userName }} 👋
                </h3>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg border text-sm font-bold text-gray-800 bg-white border-gray-300">
                    <img class="w-5 h-5" :src="iconKalender" alt="Kalender" />
                    <span>{{ formattedDate }}</span>
                </div>
            </div>

            <!-- Menu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3  gap-4 mt-6">
                <div v-for="menu in menus" :key="menu.title"
                    class="flex items-center gap-3 p-5 rounded-xl bg-white border border-gray-200 shadow-lg hover:bg-blue-50 active:bg-blue-700 active:text-white transition-all cursor-pointer"
                    @click="navigateTo(menu.path)">
                    <img :src="menu.icon" class="w-6 h-6 object-cover" :alt="menu.title" />
                    <p class="font-semibold text-blue-900">{{ menu.title }}</p>
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
    components: { Sidebar, HeaderBar },
    data() {
        return {
            activeMenu: "acara",
            userName: "",
            userRole: "",
            menus: [
                { icon: iconPosyandu, title: "Acara", path: "/acara" },
                { icon: iconPosyandu, title: "Peserta Acara", path: "/peserta-acara" },
                { icon: iconPosyandu, title: "Dokumentasi Acara", path: "/dokumentasi-acara" },
            ],
        };
    },
    computed: {
        formattedDate() {
            const date = new Date();
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'long',
            }).format(date);
        },
        iconKalender() {
            return iconKalender;
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
