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
                        <input type="text" :value="tanggalFormatted" readonly
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
                        <input type="text" v-model="formData.status" @input="
                            formData.status = sanitizeInput(formData.status)
                            " maxlength="100" placeholder="Status (direncanakan, berjalan, selesai, dibatalkan)"
                            class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="text-right mt-6">
                        <button @click="updateAcara"
                            class="bg-[#074a5d] text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
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
import axios from "axios";

export default {
    name: "AcaraViewComponent",
    components: {
        Sidebar,
        HeaderBar,
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

            axios
                .put(`http://localhost:8000/api/acara/${id}`, this.formData, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                })
                .then(() => {
                    alert("Acara berhasil diperbarui.");
                    this.$router.push("/acara");
                })
                .catch((err) => {
                    alert("Gagal memperbarui acara.");
                    console.error(err);
                });
        },
        sanitizeInput(text) {
            const div = document.createElement("div");
            div.textContent = text;
            return div.innerHTML;
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
        formatKategoriName(nama) {
            return nama.charAt(0).toUpperCase() + nama.slice(1);
        },
    },
};
</script>
