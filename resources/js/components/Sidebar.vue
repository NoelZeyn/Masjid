<template>
    <div>
        <!-- Hamburger Button (mobile only) -->
        <button
            class="fixed top-2.5 left-2.5 z-[1001] bg-white border border-gray-300 px-3 py-2 text-lg cursor-pointer md:hidden"
            @click="toggleSidebar"
        >
            ☰
        </button>

        <!-- Sidebar -->
        <aside
            :class="[
                'transition-transform duration-300 z-[1000] fixed md:static top-0 left-0 min-h-screen w-[320px] bg-white border-r border-gray-200 pl-7 pt-7 flex flex-col gap-6',
                { '-translate-x-full': !isSidebarOpen && isMobile },
            ]"
        >
            <!-- Logo -->
            <div class="flex items-center gap-1 border-b border-gray-200 pb-2">
                <img
                    :src="logoImage"
                    alt="Logo"
                    class="w-[35px] object-cover rounded-t-[10px] mt-[-5px]"
                />
                <span
                    class="logo-text text-[20px] font-bold text-[#08607a] font-['Protest_Strike']"
                    >MasjidOne</span
                >
            </div>

            <!-- Menu Utama -->
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold text-[#b0b385] mb-1">
                    Main Menu
                </p>
                <ul>
                    <router-link
                        v-if="role !== 'ss'"
                        to="/dashboard"
                        class="block"
                    >
                        <li :class="menuClass('dashboard')">
                            <img
                                src="@/assets/dashboard.svg"
                                class="w-5"
                                alt="Dashboard"
                            />
                            <span>Dashboard</span>
                        </li>
                    </router-link>
                    <router-link
                        v-if="role !== 'ss'"
                        to="/feature"
                        class="block"
                    >
                        <li :class="menuClass('feature')">
                            <img
                                src="@/assets/data1.svg"
                                class="w-5"
                                alt="Feature"
                            />
                            <span>Feature</span>
                        </li>
                    </router-link>

                    <router-link
                        v-if="role !== 'ss'"
                        to="/inventaris-dashboard"
                        class="block"
                    >
                        <li
                            :class="menuClass('inventaris')"
                            @click="setActive('Inventaris')"
                        >
                            <img
                                src="@/assets/data1.svg"
                                class="w-5"
                                alt="acara"
                            />
                            <span>Inventaris</span>
                        </li>
                    </router-link>
                    <router-link
                        v-if="role !== 'ss'"
                        to="/acara-dashboard"
                        class="block"
                    >
                        <li
                            :class="menuClass('acara')"
                            @click="setActive('Acara')"
                        >
                            <img
                                src="@/assets/posko.svg"
                                class="w-5"
                                alt="acara"
                            />
                            <span>Acara</span>
                        </li>
                    </router-link>

                    <router-link
                        v-if="role !== 'ss'"
                        to="/keuangan"
                        class="block"
                    >
                        <li :class="menuClass('keuangan')">
                            <img
                                src="@/assets/data1.svg"
                                class="w-5"
                                alt="keuangan"
                            />
                            <span>Keuangan</span>
                        </li>
                    </router-link>

                    <router-link v-if="role !== 'ss'" to="/infaq" class="block">
                        <li
                            :class="menuClass('infaq')"
                            @click="setActive('infaq')"
                        >
                            <img
                                src="@/assets/laporan1.svg"
                                class="w-5"
                                alt="Iaporan"
                            />
                            <span>Infaq</span>
                        </li>
                    </router-link>

                    <router-link
                        v-if="role === 'Ketua'"
                        to="/admin-verifikator"
                        class="block"
                    >
                        <li :class="menuClass('verifikasi')">
                            <img
                                src="@/assets/profil.svg"
                                class="w-5"
                                alt="Verifikasi"
                            />
                            <span>Verifikasi Admin</span>
                        </li>
                    </router-link>
                </ul>
            </div>

            <!-- Menu Admin -->
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold text-[#b0b385] mb-1">Admin</p>
                <ul>
                    <router-link to="/profile" class="block">
                        <li :class="menuClass('profile')">
                            <img
                                src="@/assets/profil.svg"
                                class="w-5"
                                alt="Profile"
                            />
                            <span>Profile</span>
                        </li>
                    </router-link>
                    <li
                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-[#7d7f81] hover:bg-slate-100 cursor-pointer"
                        @click="showModalConfirm = true"
                    >
                        <img
                            src="@/assets/SignOut.svg"
                            class="w-5"
                            alt="Keluar"
                        />
                        <span>Keluar</span>
                    </li>
                </ul>
            </div>

            <ModalConfirm
                :visible="showModalConfirm"
                title="Konfirmasi Logout"
                message="Apakah Anda yakin ingin keluar?"
                @confirm="logout"
                @cancel="showModalConfirm = false"
            />
        </aside>
    </div>
</template>

<script>
import axios from "axios";
import logoImage from "@/assets/logo2.png";
import ModalConfirm from "@/components/ModalConfirm.vue";

export default {
    name: "Sidebar",
    components: { ModalConfirm },
    props: ["activeMenu"],
    emits: ["update:activeMenu"],
    data() {
        return {
            logoImage,
            showModalConfirm: false,
            role: localStorage.getItem("userRole") || "",
            isSidebarOpen: true,
            isMobile: window.innerWidth < 768,
        };
    },
    mounted() {
        window.addEventListener("resize", this.checkScreenSize);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.checkScreenSize);
    },
    methods: {
        setActive(menu) {
            this.$emit("update:activeMenu", menu);
        },
        logout() {
            axios
                .post(
                    "http://localhost:8000/api/logout",
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "token"
                            )}`,
                        },
                    }
                )
                .then(() => {
                    localStorage.clear();
                    sessionStorage.clear();
                    this.showModalConfirm = false;
                    this.$router.push("/login");
                })
                .catch((error) => {
                    console.error("Logout gagal:", error);
                    alert("Gagal logout. Silakan coba lagi.");
                });
        },
        toggleSidebar() {
            if (this.isMobile) {
                this.isSidebarOpen = !this.isSidebarOpen;
            }
        },
        checkScreenSize() {
            this.isMobile = window.innerWidth < 768;
            if (!this.isMobile) {
                this.isSidebarOpen = true;
            }
        },
        menuClass(name) {
            const isActive = this.activeMenu === name;
            return [
                "flex items-center gap-2 px-3 py-2 rounded-md text-sm cursor-pointer w-full",
                isActive
                    ? "bg-[#84bbd1] text-[#074a5d] font-semibold"
                    : "text-[#7d7f81] hover:bg-slate-100",
            ];
        },
    },
};
</script>

<style scoped>
.logo-text {
    font-family: "Protest Strike", sans-serif;
    font-size: 26px;
    color: #08607a;
}
</style>
