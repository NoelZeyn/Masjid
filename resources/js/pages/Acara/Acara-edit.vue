<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />

        <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
            <HeaderBar title="Edit Acara Masjid" />
            <div class="border-b border-gray-300"></div>

            <div class="bg-white p-6 rounded-2xl shadow mt-8">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
                    Edit Acara Masjid
                </h3>
                <div class="h-[1px] w-[calc(100%+47px)] bg-gray-300 my-4 -ml-6"></div>

                <div class="flex flex-col gap-4 mx-9">
                    <h4 class="text-[15px] font-medium text-black text-center pb-3">
                        Acara Masjid
                    </h4>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Judul Acara</label>
                        <input type="text" v-model="formData.nama_acara" @input="
                            formData.nama_acara = sanitizeInput(
                                formData.nama_acara
                            )
                            " maxlength="255" placeholder="Judul Acara"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Deskripsi</label>
                        <textarea v-model="formData.deskripsi" @input="
                            formData.deskripsi = sanitizeInput(
                                formData.deskripsi
                            )
                            " rows="4" maxlength="1000" placeholder="Deskripsi Acara"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm resize-y"></textarea>
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal</label>
                        <input type="date" v-model="formData.tanggal_mulai"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                        <span> - </span>
                        <input type="date" v-model="formData.tanggal_selesai"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Lokasi</label>
                        <input type="text" v-model="formData.lokasi" @input="
                            formData.lokasi = sanitizeInput(formData.lokasi)
                            " maxlength="255" placeholder="Tempat Pelaksanaan"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Waktu</label>
                        <div class="flex items-center gap-2">
                            <input type="time" v-model="formData.waktu"
                                class="w-[100px] p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                            <span>WIB</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kategori</label>
                        <select v-model="formData.kategori_id"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm">
                            <option disabled value="">
                                -- Pilih Kategori --
                            </option>
                            <option v-for="item in kategoriList" :key="item.id" :value="item.id">
                                {{ formatKategoriName(item.nama) }}
                            </option>
                        </select>

                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Status</label>
                        <select v-model="formData.status"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm">
                            <option value="">
                                -- Pilih Kategori --
                            </option>
                            <option value="direncanakan">Direncanakan</option>
                            <option value="berjalan">Berjalan</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />

                    <div class="flex justify-between items-center mt-6">
                        <router-link to="/acara">
                            <button
                                class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                                Kembali
                            </button>
                        </router-link>

                        <button @click="updateAcara"
                            class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                            Simpan Perubahan
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
    name: "AcaraViewComponent",
    components: {
        Sidebar,
        HeaderBar,
        SuccessAlert,
    },
    data() {
        return {
            activeMenu: "acara",
            kategoriList: [],
            formData: {
                nama_acara: "",
                deskripsi: "",
                kategori_id: "",
                tanggal_mulai: "",
                tanggal_selesai: "",
                lokasi: "",
                waktu: "",
                status: "",
            },
            showSuccessAlert: false,
            successMessage: "",
        };
    },
    mounted() {
        this.fetchAcara();
        this.fetchKategori();
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
        fetchKategori() {
            const token = localStorage.getItem("token");
            axios
                .get(`http://localhost:8000/api/kategori`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                })
                .then((res) => {
                    this.kategoriList = res.data.data;
                })
                .catch(() => {
                    alert("Gagal memuat kategori.");
                });
        },
        fetchAcara() {
            const id = this.$route.params.id;
            const token = localStorage.getItem("token");

            axios
                .get(`http://localhost:8000/api/acara/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                })
                .then((res) => {
                    const data = res.data.data;
                    this.formData = {
                        nama_acara: this.sanitizeInput(data.nama_acara),
                        deskripsi: this.sanitizeInput(data.deskripsi),
                        kategori_id: data.kategori.id,
                        tanggal_mulai: data.tanggal_mulai,
                        tanggal_selesai: data.tanggal_selesai,
                        lokasi: this.sanitizeInput(data.lokasi),
                        waktu: data.waktu,
                        status: this.sanitizeInput(data.status),
                    };
                })
                .catch((err) => {
                    alert("Gagal memuat data.");
                    console.error(err);
                });
        },
        updateAcara() {
            const id = this.$route.params.id;
            const token = localStorage.getItem("token");

            // Validasi tanggal
            if (this.formData.tanggal_selesai < this.formData.tanggal_mulai) {
                alert("Tanggal selesai tidak boleh lebih awal dari tanggal mulai.");
                return;
            }

            axios
                .put(`http://localhost:8000/api/acara/${id}`, this.formData, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                })
                .then((response) => {
                    this.successMessage = "Acara berhasil diperbarui";
                    this.showSuccessAlert = true;
                    setTimeout(() => {
                        this.showSuccessAlert = false;
                        this.$router.push({
                            path: `/acara`,
                        });
                    }, 2500);
                })
                .catch((err) => {
                    alert("Gagal memperbarui acara.");
                    console.error(err);
                });
        },
        sanitizeInput(text) {
            if (typeof text !== "string") return text;
            const div = document.createElement("div");
            div.textContent = text;
            return div.innerHTML;
        },
        formatTanggalRange(tglMulai, tglSelesai) {
            if (!tglMulai || !tglSelesai) return "-";

            const format = (tgl) => {
                const date = new Date(tgl);
                const day = String(date.getDate()).padStart(2, "0");
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };

            return `${format(tglMulai)} - ${format(tglSelesai)}`;
        },
        formatKategoriName(nama) {
            return nama.charAt(0).toUpperCase() + nama.slice(1);
        },
    },
};
</script>
