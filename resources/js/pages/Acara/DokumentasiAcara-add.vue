<template>
    <div class="flex min-h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />
        <div class="flex-1 p-8 pt-6 flex flex-col bg-white">
            <HeaderBar title="Tambah Dokumentasi Acara" />
            <div class="border-b border-gray-300 mb-6"></div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">Formulir Dokumentasi</h3>

                <div class="flex flex-col gap-4">
                    <!-- Pilih Acara -->
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Pilih Acara</label>
                        <select required v-model="dokumentasi.acara_id_fk"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm">
                            <option value="" disabled selected>Pilih salah satu (Acara - Tanggal mulai)</option>
                            <option v-for="acara in daftarAcara" :key="acara.id" :value="acara.id">
                                {{ acara.nama_acara }} - {{ formatTanggal(acara.tanggal_mulai) }}
                            </option>
                        </select>
                    </div>

                    <!-- Tipe -->
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Tipe</label>
                        <select v-model="dokumentasi.tipe"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm">
                            <option value="foto">Foto</option>
                            <option value="video">Video</option>
                            <option value="dokumen">Dokumen</option>
                        </select>
                    </div>

                    <!-- Tanggal Upload -->
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal Upload</label>
                        <input type="date" v-model="dokumentasi.uploaded_at"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                    </div>

                    <!-- Catatan -->
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Catatan</label>
                        <textarea v-model="dokumentasi.catatan" rows="3"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm resize-none"></textarea>
                    </div>

                    <!-- Upload File -->
                    <div class="flex items-center gap-5" v-if="dokumentasi.tipe !== 'video'">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Upload File</label>
                        <input type="file" @change="handleFileUpload"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                    </div>

                    <!-- Link -->
                    <div class="flex items-center gap-5" v-if="['video', 'dokumen'].includes(dokumentasi.tipe)">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Link</label>
                        <input type="text" v-model="dokumentasi.link"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                    </div>

                    <!-- Preview -->
                    <div v-if="dokumentasi.tipe === 'foto' && dokumentasiPreview" class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Preview Foto</label>
                        <img :src="dokumentasiPreview" alt="Preview"
                            class="w-[300px] h-auto rounded-lg border shadow" />
                    </div>

                    <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />

                    <div class="flex justify-between items-center mt-6">
                        <router-link to="/dokumentasi-acara">
                            <button class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                                Kembali
                            </button>
                        </router-link>
                        <button @click="submitForm"
                            class="bg-[#074a5d] text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                            Simpan Dokumentasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import SuccessAlert from "@/components/SuccessAlert.vue";
import axios from "axios";

export default {
    name: "DokumentasiAcaraAdd",
    components: { Sidebar, HeaderBar, SuccessAlert },
    data() {
        return {
            activeMenu: "dokumentasi-acara",
            dokumentasi: {
                acara_id_fk: "",
                tipe: "foto",
                uploaded_at: "",
                catatan: "",
                link: "",
            },
            selectedFile: null,
            dokumentasiPreview: null,
            daftarAcara: [],
            showSuccessAlert: false,
            successMessage: "",
        };
    },
    mounted() {
        this.fetchAcaraList();
    },
    methods: {
        formatTanggal(tgl) {
            const date = new Date(tgl);
            return date.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" });
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.selectedFile = file;

            if (this.dokumentasi.tipe === "foto" && file) {
                this.dokumentasiPreview = URL.createObjectURL(file);
            }
        },
        async fetchAcaraList() {
            const token = localStorage.getItem("token");
            try {
                const res = await axios.get("http://localhost:8000/api/acara", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });
                this.daftarAcara = res.data.data;
            } catch (err) {
                alert("Gagal memuat daftar acara.");
                console.error(err);
            }
        },
        async submitForm() {
            if (!this.dokumentasi.acara_id_fk) {
                alert("Silakan pilih acara terlebih dahulu.");
                return;
            }
            const token = localStorage.getItem("token");
            const formData = new FormData();

            formData.append("acara_id_fk", this.dokumentasi.acara_id_fk);
            formData.append("tipe", this.dokumentasi.tipe);
            formData.append("uploaded_at", this.dokumentasi.uploaded_at);
            formData.append("catatan", this.dokumentasi.catatan);
            formData.append("link", this.dokumentasi.link || "");

            if (this.selectedFile) {
                formData.append("file", this.selectedFile);
            }

            try {
                await axios.post("http://localhost:8000/api/dokumentasi-acara", formData, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                        "Content-Type": "multipart/form-data",
                    },
                });

                this.successMessage = "Dokumentasi berhasil ditambahkan!";
                this.showSuccessAlert = true;
                setTimeout(() => {
                    this.showSuccessAlert = false;
                    this.$router.push("/dokumentasi-acara");
                }, 2000);
            } catch (err) {
                alert("Gagal menyimpan dokumentasi.");
                console.error(err);
            }
        },
    },
};
</script>
