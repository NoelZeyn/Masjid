<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />
        <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
            <HeaderBar title="Dokumentasi Acara" />
            <div class="border-b border-gray-300"></div>

            <div class="bg-white p-6 rounded-2xl shadow mt-8">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
                    Dokumentasi Acara Detail
                </h3>

                <div class="h-[1px] w-[calc(100%+47px)] bg-gray-300 my-4 -ml-6"></div>

                <div class="flex flex-col gap-4 mx-9">
                    <h4 class="text-[15px] font-medium text-black text-center pb-3">
                        Informasi Acara Terkait
                    </h4>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Judul Acara</label>
                        <input type="text" v-model="formData.nama_acara" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal</label>
                        <input type="text" :value="tanggalFormatted" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kategori</label>
                        <input type="text" v-model="formData.kategori" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5 mb-10">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Status</label>
                        <input type="text" v-model="formData.status" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="h-[1px] bg-gray-300 my-2"></div>

                    <h4 class="text-[15px] font-medium text-black text-center pb-3">Dokumentasi</h4>

                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Tipe</label>
                            <select v-model="dokumentasi.tipe"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm">
                                <option value="foto">Foto</option>
                                <option value="video">Video</option>
                                <option value="dokumen">Dokumen</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal Upload</label>
                            <input type="date" v-model="dokumentasi.uploaded_at"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                        </div>

                        <div class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Catatan</label>
                            <textarea v-model="dokumentasi.catatan" rows="3"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm resize-none"></textarea>
                        </div>

                        <div class="flex items-center gap-5" v-if="dokumentasi.tipe !== 'video'">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Upload File</label>
                            <input type="file" @change="handleFileUpload"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                        </div>

                        <div class="flex items-center gap-5" v-if="['video', 'dokumen'].includes(dokumentasi.tipe)">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Link</label>
                            <input type="text" v-model="dokumentasi.link"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-white text-sm" />
                        </div>

                        <!-- Preview Foto -->
                        <div v-if="dokumentasi.tipe === 'foto' && dokumentasi.file_path"
                            class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Preview Foto</label>
                            <img :src="`/storage/${dokumentasi.file_path}`" alt="Preview"
                                class="rounded-lg shadow w-[300px] h-auto object-cover border" />
                        </div>

                        <!-- Preview Dokumen -->
                        <div v-if="dokumentasi.tipe === 'dokumen' && dokumentasi.file_path"
                            class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Dokumen</label>
                            <a :href="`/storage/${dokumentasi.file_path}`" target="_blank"
                                class="text-blue-600 underline text-sm">Lihat Dokumen</a>
                        </div>

                        <!-- Preview Video -->
                        <div v-if="dokumentasi.tipe === 'video' && dokumentasi.link" class="flex items-center gap-5">
                            <label class="min-w-[150px] font-semibold text-sm text-black">Video</label>
                            <iframe :src="dokumentasi.link" class="w-[300px] h-[200px] border rounded-lg"></iframe>
                        </div>
                    </div>
                    <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />
                    <div class="flex justify-between items-center mt-6">
                        <router-link to="/dokumentasi-acara">
                            <button
                                class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                                Kembali
                            </button>
                        </router-link>

                        <button @click="updateDokumentasi"
                            class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                            Simpan Perubahan
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
    name: "DokumentasiAcaraDetail",
    components: {
        Sidebar,
        HeaderBar,
        SuccessAlert,
    },
    data() {
        return {
            activeMenu: "dokumentasi-acara",
            formData: {
                nama_acara: "",
                kategori: "",
                tanggal_mulai: "",
                tanggal_selesai: "",
                status: "",
            },
            dokumentasi: {
                tipe: "foto",
                file_path: "",
                link: "",
                catatan: "",
                uploaded_at: "",
            },
            selectedFile: null,
            showSuccessAlert: false,
            successMessage: "",
        };
    },
    mounted() {
        this.fetchDokumentasi(this.$route.params.id);
    },
    computed: {
        tanggalFormatted() {
            return this.formatTanggalRange(
                this.formData.tanggal_mulai,
                this.formData.tanggal_selesai
            );
        },
    },
    methods: {
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
        },
        async updateDokumentasi() {
            const token = localStorage.getItem("token");
            const formData = new FormData();
            formData.append('_method', 'PUT'); // <<< Ini WAJIB
            formData.append("acara_id_fk", this.$route.params.id);
            formData.append("tipe", this.dokumentasi.tipe);
            formData.append("link", this.dokumentasi.link || "");
            formData.append("catatan", this.dokumentasi.catatan || "");
            formData.append("uploaded_at", this.dokumentasi.uploaded_at || "");

            if (this.selectedFile) {
                formData.append("file", this.selectedFile);
            }

            try {
                const res = await axios.post(
                    `http://localhost:8000/api/dokumentasi-acara/${this.$route.params.id}`,
                    formData,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            Accept: "application/json",
                            "Content-Type": "multipart/form-data",
                        },
                    }
                )
                    .then((response) => {
                        this.successMessage = "Dokumentasi berhasil diperbarui";
                        this.showSuccessAlert = true;
                        setTimeout(() => {
                            this.showSuccessAlert = false;
                            this.$router.push({
                                path: `/dokumentasi-acara`,
                            });
                        }, 2500);
                    })
                    .catch((error) => {
                        console.error(
                            "Gagal menambahkan pemeriksaan:",
                            error.response?.data || error.message
                        );
                    });
            } catch (error) {
                alert("Gagal memperbarui dokumentasi.");
                console.error(error);
            }
        },
        async fetchDokumentasi(id) {
            const token = localStorage.getItem("token");

            try {
                const res = await axios.get(`http://localhost:8000/api/dokumentasi-acara/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });

                const data = res.data.data;

                this.formData = {
                    nama_acara: data.acara?.nama_acara || "-",
                    kategori: data.acara?.kategori?.nama || "-",
                    tanggal_mulai: data.acara?.tanggal_mulai || "-",
                    tanggal_selesai: data.acara?.tanggal_selesai || "-",
                    status: data.acara?.status || "-",
                };

                this.dokumentasi = {
                    tipe: data.tipe,
                    file_path: data.file_path,
                    link: data.link,
                    catatan: data.catatan,
                    uploaded_at: data.uploaded_at,
                };
            } catch (error) {
                alert("Gagal memuat data dokumentasi acara.");
                console.error(error);
            }
        },
        formatTanggalRange(tglMulai, tglSelesai) {
            if (!tglMulai || !tglSelesai) return "-";

            const format = (tgl) => {
                const date = new Date(tgl);
                return {
                    day: String(date.getDate()).padStart(2, "0"),
                    month: date.toLocaleString("id-ID", { month: "long" }),
                    year: date.getFullYear(),
                };
            };

            const start = format(tglMulai);
            const end = format(tglSelesai);

            if (start.month === end.month && start.year === end.year) {
                return `${start.day} - ${end.day} ${end.month} ${end.year}`;
            }
            if (start.year === end.year) {
                return `${start.day} ${start.month} - ${end.day} ${end.month} ${end.year}`;
            }
            return `${start.day} ${start.month} ${start.year} - ${end.day} ${end.month} ${end.year}`;
        },
    },
};
</script>
