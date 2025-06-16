<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />

        <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
            <HeaderBar title="Informasi Acara Masjid" />
            <div class="border-b border-gray-300"></div>

            <div class="bg-white p-6 rounded-2xl shadow mt-8">
                <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
                    Informasi Acara Masjid
                </h3>
                <div class="h-[1px] w-[calc(100%+47px)] bg-gray-300 my-4 -ml-6"></div>

                <div class="flex flex-col gap-4 mx-9">
                    <h4 class="text-[15px] font-medium text-black text-center pb-3">
                        Acara Masjid
                    </h4>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Judul Acara</label>
                        <input type="text" v-model="formData.nama_acara" readonly placeholder="Judul Acara"
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Deskripsi</label>
                        <textarea v-model="formData.deskripsi" rows="4" readonly placeholder="Deskripsi Acara"
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm resize-y"></textarea>
                    </div>
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal</label>
                        <input type="text" :value="tanggalFormatted" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Lokasi</label>
                        <input type="text" v-model="formData.lokasi" readonly placeholder="Tempat Pelaksanaan"
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>
                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Waktu</label>
                        <div class="flex items-center gap-2">
                            <input type="time" v-model="formData.waktu" readonly
                                class="text-gray-500 w-[100px] p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                            <span>WIB</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Kategori</label>
                        <input type="text" v-model="formData.kategori" readonly
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>

                    <div class="flex items-center gap-5 mb-10">
                        <label class="min-w-[150px] font-semibold text-sm text-black">Status</label>
                        <input type="text" v-model="formData.status" readonly placeholder="Tempat Pelaksanaan"
                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
                    </div>
                </div>
                <div class="flex justify-between items-center mt-6">
                    <router-link to="/acara">
                        <button
                            class="bg-[#074a5d] cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
                            Kembali
                        </button>
                    </router-link>
                </div>
            </div>
            <br></br>
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
            formData: {
                nama_acara: "",
                deskripsi: "",
                kategori: "",
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
                        nama_acara: data.nama_acara,
                        deskripsi: data.deskripsi,
                        kategori: data.kategori.nama,
                        tanggal_mulai: data.tanggal_mulai,
                        tanggal_selesai: data.tanggal_selesai,
                        lokasi: data.lokasi,
                        waktu: data.waktu,
                        status: data.status,
                    };
                })
                .catch((err) => {
                    alert("Gagal memuat data.");
                    console.error(err);
                });
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
