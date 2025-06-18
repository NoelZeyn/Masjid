<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />

        <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
            <HeaderBar title="Tambah Supplier Masjid" />
            <div class="border-b border-gray-300"></div>

            <div class="bg-white p-6 rounded-2xl shadow mt-8">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
                    Tambah Supplier Masjid
                </h3>
                <div class="h-[1px] w-[calc(100%+47px)] bg-gray-300 my-4 -ml-6"></div>

                <div class="flex flex-col gap-4 mx-9">
                    <h4 class="text-[15px] font-medium text-black text-center pb-3">
                        Supplier Masjid
                    </h4>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Nama Supplier</label>
                        <input type="text" v-model="formData.nama_supplier" placeholder="Nama Supplier"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Alamat</label>
                        <textarea v-model="formData.alamat" placeholder="Alamat Supplier"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm resize-y"></textarea>
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kontak</label>
                        <input type="text" v-model="formData.kontak" placeholder="Kontak Supplier"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kontak</label>
                        <input type="text" v-model="formData.email" placeholder="Email Supplier"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />

                    <div class="flex justify-between items-center mt-6">
                        <router-link to="/supplier">
                            <button
                                class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                                Kembali
                            </button>
                        </router-link>

                        <button @click="submitSupplier"
                            class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                            Tambahkan Supplier
                        </button>
                    </div>
                </div>
            </div>
            <br></br>
        </div>
    </div>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import SuccessAlert from "@/components/SuccessAlert.vue";
import axios from "axios";

export default {
    name: "SupplierAdd",
    components: {
        Sidebar,
        HeaderBar,
        SuccessAlert,
    },
    data() {
        return {
            activeMenu: "inventaris",
            kategoriList: [],
            formData: {
                nama_supplier: "",
                alamat: "",
                kontak: "",
                email: "",
            },
            showSuccessAlert: false,
            successMessage: "",
        };
    },
    methods: {
        validateForm() {
            if (!this.formData.nama_supplier.trim()) {
                alert("Nama Supplier wajib diisi.");
                return false;
            }
            if (!this.formData.alamat.trim()) {
                alert("Alamat wajib diisi.");
                return false;
            }
            if (!this.formData.kontak.trim()) {
                alert("Kontak wajib diisi.");
                return false;
            }
            if (!this.formData.email.trim()) {
                alert("Email wajib diisi.");
                return false;
            }

            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.formData.email)) {
                alert("Format email tidak valid.");
                return false;
            }
            return true;
        },

        submitSupplier() {
            if (!this.validateForm()) return;
            const token = localStorage.getItem("token");
            axios
                .post("http://localhost:8000/api/supplier", this.formData, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                })
                .then((response) => {
                    this.successMessage = "Acara berhasil ditambahkan";
                    this.showSuccessAlert = true;
                    setTimeout(() => {
                        this.showSuccessAlert = false;
                        this.$router.push({
                            path: `/supplier`,
                        });
                    }, 2500);
                })
                .catch(() => {
                    alert("Gagal menambahkan supplier.");
                });
        },
    },
};
</script>
