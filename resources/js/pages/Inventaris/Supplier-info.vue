<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />

        <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
            <HeaderBar title="Edit Supplier Masjid" />
            <div class="border-b border-gray-300"></div>

            <div class="bg-white p-6 rounded-2xl shadow mt-8">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
                    Edit Supplier Masjid
                </h3>
                <div class="h-[1px] w-[calc(100%+47px)] bg-gray-300 my-4 -ml-6"></div>

                <div class="flex flex-col gap-4 mx-9">
                    <h4 class="text-[15px] font-medium text-black text-center pb-3">
                        Supplier Masjid
                    </h4>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Nama Supplier</label>
                        <input type="text" v-model="formData.nama_supplier" placeholder="Nama Supplier" readonly
                            class="text-gray-700 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Alamat</label>
                        <textarea v-model="formData.alamat" placeholder="Alamat Supplier" readonly
                            class="text-gray-700 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm resize-y"></textarea>
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kontak</label>
                        <input type="text" v-model="formData.kontak" placeholder="Kontak Supplier" readonly
                            class="text-gray-700 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kontak</label>
                        <input type="text" v-model="formData.email" placeholder="Email Supplier" readonly
                            class="text-gray-700 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />

                    <div class="flex justify-between items-center mt-6">
                        <router-link to="/supplier">
                            <button
                                class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                                Kembali
                            </button>
                        </router-link>

                        <router-link :to="`/supplier-edit/${$route.params.id}`">
                            <button
                                class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                                Edit Supplier
                            </button>
                        </router-link>
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
    name: "SupplierEdit",
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
    mounted() {
        this.fetchSupplier();
    },
    computed: {
    },
    methods: {
        fetchSupplier() {
            const id = this.$route.params.id;
            const token = localStorage.getItem("token");

            axios.get(`http://localhost:8000/api/supplier/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
            }).then((res) => {
                this.formData = res.data.data;

            }).catch(() => {
                alert("Gagal memuat data supplier.");
            });
        },
    },
};
</script>
