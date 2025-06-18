<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />
        <div class="flex-1 p-8 pt-4 bg-white">
            <HeaderBar title="Acara" class="mt-3" />
            <div class="my-4 border-b border-gray-300"></div>

            <div class="pb-12">
                <!-- Bagian Filter -->
                <div class="filters space-y-4">
                    <!-- Group Dropdown -->
                    <div class="flex flex-wrap gap-4 w-full">
                        <!-- Dropdown Kategori -->
                        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md bg-white relative flex-1 cursor-pointer"
                            @click="toggleDropdown('kategori')">
                            <img src="@/assets/posko.svg" alt="Kategori" class="w-5 h-5" />
                            <span class="flex-1 text-sm text-gray-500 truncate">
                                {{ selectedKategori || "Pilih Kategori Acara" }}
                            </span>
                            <img src="@/assets/arrow-down.svg" alt="arrow"
                                class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2" />
                            <ul v-show="dropdownOpen === 'kategori'"
                                class="text-gray-500 absolute top-full mt-1 left-0 w-full max-h-70 overflow-y-auto border border-gray-300 rounded-md bg-white shadow z-10 text-sm scroll-smooth">
                                <li @click.stop="selectOption('kategori', '')"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    <em>Hilangkan Filter Kategori</em>
                                </li>
                                <li v-for="kategori in kategoriOptions" :key="kategori.id" @click.stop="
                                    selectOption('kategori', kategori.nama)
                                    " class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ kategori.nama }}
                                </li>
                            </ul>
                        </div>

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

                    <!-- Input Cari Nama Acara -->
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
                            Data Acara Masjid
                        </h3>

                        <button @click="exportToExcel"
                            class="cursor-pointer text-sm font-semibold text-green-600 hover:text-green-800 border border-green-600 px-3 py-1 rounded-md">
                            Export Excel
                        </button>

                        <router-link to="/acara-add"
                            class="text-sm font-semibold text-[#074a5d] no-underline hover:text-[#0066cc] hover:no-underline">
                            Tambah Acara
                        </router-link>
                    </div>

                    <table class="w-full table-fixed border-collapse border border-gray-300">
                        <thead class="bg-gray-100 text-[#7d7f81]">
                            <tr>
                                <th class="w-16">No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Kategori</th>
                                <th>Nama Acara</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(news, index) in paginatedAcaraList" :key="index" class="text-[#333436]">
                                <td>
                                    {{
                                        (currentPage - 1) * itemsPerPage +
                                        index +
                                        1
                                    }}
                                </td>
                                <td>
                                    {{ formatTanggalRange(
                                        news.tanggal_mulai,
                                        news.tanggal_selesai
                                    )
                                    }}
                                </td>
                                <td>{{ news.waktu }}</td>
                                <td>{{ news.kategori.nama }}</td>
                                <td>{{ news.nama_acara }}</td>
                                <td>{{ news.lokasi }}</td>
                                <td>
                                    <div class="flex items-center space-x-2 justify-center">
                                        <button title="Informasi" @click="
                                            navigateTo('informasi', news)
                                            " class="hover:opacity-70 border-r-1 pr-2 cursor-pointer">
                                            <img :src="informasiIcon" alt="Informasi" class="w-5 h-5 object-contain" />
                                        </button>
                                        <button title="Edit" @click="navigateTo('edit', news)"
                                            class="hover:opacity-70 border-r-1 pr-2 cursor-pointer">
                                            <img :src="updateIcon" alt="Update" class="w-5 h-5 object-contain" />
                                        </button>
                                        <button title="Hapus" @click="confirmDelete(news)"
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
                            class="cursor-pointer px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            Prev
                        </button>
                        <span>Halaman {{ currentPage }} dari
                            {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="cursor-pointer px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                    <!-- Tombol Tampilkan/Sembunyikan Chart -->
                    <div class="flex justify-center mt-4">
                        <button @click="toggleChart"
                            class="cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-800 border border-blue-600 px-4 py-2 rounded-md">
                            {{ showChart ? 'Sembunyikan Chart' : 'Tampilkan Chart' }}
                        </button>
                    </div>

                    <!-- Tombol Ekstrak Gambar -->
                    <div class="flex justify-center mt-4" v-if="showChart">
                        <button @click="renderChart(true)"
                            class="cursor-pointer text-sm font-semibold text-green-600 hover:text-green-800 border border-green-600 px-4 py-2 rounded-md">
                            Ekstrak Gambar Chart
                        </button>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="flex justify-center mt-4" v-show="showChart">
                        <canvas id="hiddenChart" width="600" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <SuccessAlert :visible="showSuccessAlert" :message="successMessage" />
        <ModalConfirm :visible="showModal" title="Konfirmasi Hapus Data"
            message="Apakah Anda yakin ingin menghapus data ini?" @cancel="cancelDelete" @confirm="deleteAcara" />
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
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';
import Chart from 'chart.js/auto';

export default {
    name: "DataAcara",
    components: { Sidebar, HeaderBar, ModalConfirm, SuccessAlert },

    data() {
        return {
            activeMenu: "acara",
            searchQuery: "",
            showChart: false,
            showModal: false,
            showSuccessAlert: false,
            successMessage: "",
            acaraToDelete: null,
            dropdownOpen: null,
            debouncedSearch: null,

            acaraList: [],
            kategoriOptions: [],
            selectedBulan: "",
            selectedTahun: "",
            selectedKategori: "",
            bulanOptions: [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember",
            ],
            tahunOptions: [],

            informasiIcon,
            updateIcon,
            deleteIcon,

            currentPage: 1,
            itemsPerPage: 10,
        };
    },

    computed: {
        filteredAcaraList() {
            return this.acaraList
                .filter(a => !this.selectedKategori || a.kategori.nama === this.selectedKategori)
                .filter(a => !this.selectedBulan || this.formatBulan(a.tanggal_mulai) === this.selectedBulan)
                .filter(a => !this.selectedTahun || this.formatTahun(a.tanggal_mulai) === this.selectedTahun)
                .filter(a => !this.searchQuery || a.nama_acara.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
        paginatedAcaraList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.acaraList.slice(start, start + this.itemsPerPage);
        },
        totalPages() {
            return Math.ceil(this.acaraList.length / this.itemsPerPage);
        },
    },

    created() {
        this.debouncedSearch = this.debounce(this.searchAcara, 500);
        this.fetchLaporanAcara();
        this.fetchKategori();
        this.generateTahunOptions();
    },

    methods: {
        toggleChart() {
            this.showChart = !this.showChart;
            this.renderChart();
        },

        renderChart(exportAsImage = false) {
            const canvas = document.getElementById('hiddenChart');
            const ctx = canvas.getContext('2d');

            // Ambil data dari acaraList
            const acaraPerBulan = {};

            this.acaraList.forEach(acara => {
                const bulan = new Date(acara.tanggal_mulai).getMonth(); // 0-11
                if (!acaraPerBulan[bulan]) {
                    acaraPerBulan[bulan] = 1;
                } else {
                    acaraPerBulan[bulan]++;
                }
            });

            // Siapkan label bulan dalam Bahasa Indonesia
            const bulanLabels = [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
            ];

            // Buat data chart
            const labels = bulanLabels;
            const data = labels.map((_, index) => acaraPerBulan[index] || 0);

            if (this.chartInstance) this.chartInstance.destroy();

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Jumlah Acara',
                        data,
                        borderColor: 'rgba(59,130,246,1)',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: false,
                    animation: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Jumlah Acara per Bulan',
                            font: { size: 18 },
                            align: 'center'
                        },
                        legend: { display: false }
                    }
                }
            });

            // Ekspor gambar jika diminta
            if (exportAsImage) {
                setTimeout(() => {
                    const image = canvas.toDataURL("image/png");
                    const link = document.createElement('a');
                    link.href = image;
                    link.download = 'jumlah-acara-per-bulan.png';
                    link.click();
                }, 500);
            }
        },

        exportToExcel() {
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Data Acara');

            worksheet.columns = [
                { header: 'No', key: 'no', width: 5 },
                { header: 'Tanggal', key: 'tanggal', width: 20 },
                { header: 'Waktu', key: 'waktu', width: 15 },
                { header: 'Kategori', key: 'kategori', width: 20 },
                { header: 'Nama Acara', key: 'nama', width: 30 },
                { header: 'Lokasi', key: 'lokasi', width: 25 },
            ];

            this.filteredAcaraList.forEach((a, i) => {
                worksheet.addRow({
                    no: i + 1,
                    tanggal: this.formatTanggalRange(a.tanggal_mulai, a.tanggal_selesai),
                    waktu: a.waktu,
                    kategori: a.kategori.nama,
                    nama: a.nama_acara,
                    lokasi: a.lokasi
                });
            });

            workbook.xlsx.writeBuffer().then((buffer) => {
                saveAs(new Blob([buffer]), 'Data_Acara.xlsx');
            });
        },

        // 🔍 Search & Filter
        onInputSearch() {
            this.debouncedSearch();
        },
        searchAcara() {
            const token = localStorage.getItem("token");
            let url = "http://localhost:8000/api/search?";
            if (this.searchQuery) url += `nama=${encodeURIComponent(this.searchQuery)}&`;
            if (this.selectedBulan) url += `bulan=${encodeURIComponent(this.selectedBulan)}&`;
            if (this.selectedTahun) url += `tahun=${encodeURIComponent(this.selectedTahun)}&`;
            if (this.selectedKategori) url += `kategori=${encodeURIComponent(this.selectedKategori)}&`;
            if (url.endsWith("&")) url = url.slice(0, -1);

            axios.get(url, { headers: { Authorization: `Bearer ${token}` } })
                .then((res) => {
                    this.acaraList = res.data.status === "success" ? res.data.data : [];
                    this.currentPage = 1;
                })
                .catch(err => console.error("Gagal mencari acara:", err));
        },
        selectOption(type, value) {
            if (type === "bulan") this.selectedBulan = value;
            if (type === "tahun") this.selectedTahun = value;
            if (type === "kategori") this.selectedKategori = value;
            this.dropdownOpen = null;
            this.searchAcara();
        },

        // 📦 Fetch
        async fetchLaporanAcara() {
            try {
                const token = localStorage.getItem("token");
                const res = await axios.get("http://localhost:8000/api/acara", {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.acaraList = res.data.data.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            } catch (err) {
                console.error("Gagal mengambil data:", err);
            }
        },
        async fetchKategori() {
            try {
                const token = localStorage.getItem("token");
                const res = await axios.get("http://localhost:8000/api/kategori", {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.kategoriOptions = res.data.data;
            } catch (err) {
                console.error("Gagal mengambil kategori:", err);
            }
        },
        generateTahunOptions() {
            const current = new Date().getFullYear();
            for (let y = current + 50; y >= current - 50; y--) {
                this.tahunOptions.push(y.toString());
            }
        },

        // 🧰 Utility
        formatTanggalRange(start, end) {
            const f = (tgl) => {
                const d = new Date(tgl);
                return {
                    d: String(d.getDate()).padStart(2, "0"),
                    m: d.toLocaleString("id-ID", { month: "long" }),
                    y: d.getFullYear()
                };
            };
            const a = f(start), b = f(end);
            if (a.m === b.m && a.y === b.y) return `${a.d} - ${b.d} ${b.m} ${b.y}`;
            if (a.y === b.y) return `${a.d} ${a.m} - ${b.d} ${b.m} ${b.y}`;
            return `${a.d} ${a.m} ${a.y} - ${b.d} ${b.m} ${b.y}`;
        },
        debounce(fn, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), wait);
            };
        },
        toggleDropdown(menu) {
            this.dropdownOpen = this.dropdownOpen === menu ? null : menu;
        },
        updateActiveMenu(menu) {
            this.activeMenu = menu;
        },
        navigateTo(action, acara) {
            localStorage.setItem(`dataAcara${action}`, JSON.stringify(acara));
            this.$router.push(`/acara-${action}/${acara.id}`);
        },

        // ❌ Delete
        confirmDelete(acara) {
            this.acaraToDelete = acara;
            this.showModal = true;
        },
        cancelDelete() {
            this.acaraToDelete = null;
            this.showModal = false;
        },
        async deleteAcara() {
            try {
                const token = localStorage.getItem("token");
                await axios.delete(`http://localhost:8000/api/acara/${this.acaraToDelete.id}`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.successMessage = "Acara berhasil dihapus!";
                this.showSuccessAlert = true;
                setTimeout(() => this.showSuccessAlert = false, 2000);
                this.fetchLaporanAcara();
            } catch (err) {
                console.error("Gagal menghapus acara:", err);
            } finally {
                this.cancelDelete();
            }
        },

        // ⏮️ Pagination
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        }
    }
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
