<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />
        <div class="flex-1 p-8 pt-4 bg-white">
            <HeaderBar title="Peserta Acara" class="mt-3" />
            <div class="my-4 border-b border-gray-300"></div>

            <div class="pb-12">
                <!-- Bagian Filter -->
                <div class="filters space-y-4">
                    <!-- Group Dropdown -->
                    <div class="flex flex-wrap gap-4 w-full">

                        <!-- Dropdown Bulan -->
                        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md bg-white relative flex-1 cursor-pointer"
                            @click="toggleDropdown('bulan')">
                            <img src="@/assets/kalender.svg" alt="Bulan" class="w-5 h-5" />
                            <span class="flex-1 text-sm text-gray-500 truncate">
                                {{ selectedBulan || "Pilih Bulan" }}
                            </span>
                            <img src="@/assets/arrow-down.svg" alt="arrow"
                                class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2" />
                            <ul v-show="dropdownOpen === 'bulan'"
                                class="text-gray-500 absolute top-full mt-1 left-0 w-full max-h-70 overflow-y-auto border border-gray-300 rounded-md bg-white shadow z-10 text-sm scroll-smooth">
                                <li @click.stop="selectOption('bulan', '')"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    <em>Hilangkan Filter Bulan</em>
                                </li>
                                <li v-for="(bulan, index) in bulanOptions" :key="index"
                                    @click.stop="selectOption('bulan', bulan)"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ bulan }}
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Tahun -->
                        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md bg-white relative flex-1 cursor-pointer"
                            @click="toggleDropdown('tahun')">
                            <img src="@/assets/kalender.svg" alt="Tahun" class="w-5 h-5" />
                            <span class="flex-1 text-sm text-gray-500 truncate">
                                {{ selectedTahun || "Pilih Tahun" }}
                            </span>
                            <img src="@/assets/arrow-down.svg" alt="arrow"
                                class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2" />
                            <ul v-show="dropdownOpen === 'tahun'"
                                class="text-gray-500 absolute top-full mt-1 left-0 w-full max-h-70 overflow-y-auto border border-gray-300 rounded-md bg-white shadow z-10 text-sm scroll-smooth">
                                <li @click.stop="selectOption('tahun', '')"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    <em>Hilangkan Filter Tahun</em>
                                </li>
                                <li v-for="tahun in tahunOptions" :key="tahun"
                                    @click.stop="selectOption('tahun', tahun)"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ tahun }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="text" v-model="searchQuery" @input="onInputSearch" placeholder="Cari nama acara..."
                            class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-4 text-sm text-gray-700" />
                        <img src="@/assets/search.svg" alt="Search"
                            class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    </div>
                </div>

                <!-- Tabel Data Acara -->
                <div class="bg-white rounded-lg shadow border border-gray-300 mt-8 overflow-hidden">
                    <div class="flex justify-between items-center px-5 p-3 border-b border-gray-300">
                        <h3 class="text-sm font-semibold text-gray-900">
                            Data Peserta Acara Masjid
                        </h3>
                    </div>

                    <table class="w-full table-fixed border-collapse border border-gray-300">
                        <thead class="bg-gray-100 text-[#7d7f81]">
                            <tr>
                                <th class="w-16">No</th>
                                <th>Nama</th>
                                <th>Acara</th>
                                <th>Tanggal Mulai</th>
                                <th>Kontak</th>
                                <th class="w-50">Status Kehadiran</th>
                                <th class="w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(peserta, index) in paginatedPesertaAcaraList" :key="index"
                                class="text-[#333436]">
                                <td>{{ index + 1 }}</td>
                                <td>{{ peserta.warga.nama_lengkap }}</td>
                                <td>{{ peserta.acara.nama_acara }}</td>
                                <td>{{ formatTanggal(peserta.acara.tanggal_mulai) }}</td>
                                <td>{{ peserta.warga.kontak }}</td>
                                <td>
                                    <select v-model="peserta.status_kehadiran" @change="updateStatusKehadiran(peserta)"
                                        class="text-sm capitalize py-1 border border-gray-300 rounded-md bg-white">
                                        <option value="hadir">Hadir</option>
                                        <option value="tidak_hadir">Tidak Hadir</option>
                                        <option value="belum_konfirmasi">Belum Konfirmasi</option>
                                    </select>
                                </td>

                                <td>
                                    <div class="flex items-center space-x-2 justify-center">
                                        <button title="Hapus" @click="confirmDelete(peserta)"
                                            class="hover:opacity-70 cursor-pointer">
                                            <img :src="deleteIcon" alt="Delete" class="w-5 h-5 object-contain" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div
                        class="flex justify-between items-center px-4 py-3 border-t border-gray-300 text-sm text-[#333436]">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            Prev
                        </button>
                        <span>Halaman {{ currentPage }} dari
                            {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />
        <ModalConfirm :visible="showModal" title="Konfirmasi Hapus Data"
            message="Apakah Anda yakin ingin menghapus data ini?" @cancel="cancelDelete"
            @confirm="deletePesertaAcara" />
    </div>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import ModalConfirm from "@/components/ModalConfirm.vue";
import SuccessAlert from "@/components/SuccessAlert.vue";
import informasiIcon from "@/assets/Informasi.svg";
import updateIcon from "@/assets/Edit.svg";
import deleteIcon from "@/assets/Delete.svg";
import axios from "axios";

export default {
    name: "DataAcara",
    components: { Sidebar, HeaderBar, ModalConfirm, SuccessAlert },

    data() {
        return {
            activeMenu: "acara",
            searchQuery: "",
            showModal: false,
            pesertaAcaraToDelete: null,
            dropdownOpen: null,
            debouncedSearch: null,
            pesertaAcaraList: [],
            selectedAcaraId: "",
            pesertaList: [],
            selectedBulan: "",
            selectedTahun: "",

            bulanOptions: [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            ],
            tahunOptions: [],

            informasiIcon,
            updateIcon,
            deleteIcon,

            currentPage: 1,
            itemsPerPage: 10,
            showSuccessAlert: false,
            successMessage: "",
        };
    },

    computed: {
        paginatedPesertaAcaraList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.pesertaAcaraList.slice(start, start + this.itemsPerPage);
        },
        totalPages() {
            return Math.ceil(this.pesertaAcaraList.length / this.itemsPerPage);
        },
    },

    created() {
        this.debouncedSearch = this.debounce(this.fetchPesertaAcaraList, 500);
        this.fetchLaporanPesertaAcara();
        this.generateTahunOptions();
    },

    methods: {
        formatTanggal(date) {
            return new Date(date).toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "long",
                year: "numeric",
            });
        },
        fetchPesertaAcaraList() {
            const bulan = this.selectedBulan;
            const tahun = this.selectedTahun;
            const search = this.searchQuery;

            const params = {};
            if (bulan) params.bulan = bulan;
            if (tahun) params.tahun = tahun;
            if (search) params.search = search;

            axios
                .get("/api/search-peserta", { params })
                .then((response) => {
                    this.pesertaAcaraList = response.data;
                    this.currentPage = 1;
                })
                .catch((error) => {
                    console.error("Gagal mengambil data peserta:", error);
                });
        },
        onInputSearch() {
            this.debouncedSearch();
        },
        selectOption(type, value) {
            if (type === "bulan") this.selectedBulan = value;
            if (type === "tahun") this.selectedTahun = value;
            this.dropdownOpen = null;
            this.fetchPesertaAcaraList();
        },

        // 📦 FETCH DATA
        async fetchLaporanPesertaAcara() {
            try {
                const token = localStorage.getItem("token");
                if (!token) throw new Error("Token tidak ditemukan");
                const res = await axios.get("http://localhost:8000/api/peserta-acara", {
                    headers: { Authorization: `Bearer ${token}` },
                });
                this.pesertaAcaraList = res.data.data.sort(
                    (a, b) => new Date(b.tanggal) - new Date(a.tanggal)
                );
            } catch (err) {
                console.error("Gagal mengambil data:", err);
            }
        },
        generateTahunOptions() {
            const currentYear = new Date().getFullYear();
            for (
                let year = currentYear + 50;
                year >= currentYear - 50;
                year--
            ) {
                this.tahunOptions.push(year.toString());
            }
        },

        // 🧰 UTILITY
        debounce(func, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    func.apply(this, args);
                }, wait);
            };
        },
        toggleDropdown(menu) {
            this.dropdownOpen = this.dropdownOpen === menu ? null : menu;
        },
        updateActiveMenu(menu) {
            this.activeMenu = menu;
        },
        navigateTo(action, pesertaAcara) {
            localStorage.setItem(`dataPesertaAcara${action}`, JSON.stringify(pesertaAcara));
            this.$router.push(`/peserta-acara-${action}/${pesertaAcara.id}`);
        },

        // ❌ DELETE
        confirmDelete(pesertaAcara) {
            this.pesertaAcaraToDelete = pesertaAcara;
            this.showModal = true;
        },
        cancelDelete() {
            this.showModal = false;
            this.pesertaAcaraToDelete = null;
        },
        async deletePesertaAcara() {
            try {
                const token = localStorage.getItem("token");
                await axios.delete(
                    `http://localhost:8000/api/peserta-acara/${this.pesertaAcaraToDelete.id}`,
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );
                this.successMessage = "Peserta Acara berhasil dihapus!";
                this.showSuccessAlert = true;
                setTimeout(() => (this.showSuccessAlert = false), 2000);
                this.fetchLaporanAcara();
            } catch (err) {
                console.error("Gagal menghapus acara:", err);
            } finally {
                this.cancelDelete();
            }
        },

        // ⏮️ PAGINATION
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
    },
};
</script>

<style scoped>
th,
td {
    padding: 12px 16px;
    text-align: center;
    font-size: 14px;
    border: 1px solid #ccc;
    word-wrap: break-word;
}
</style>
