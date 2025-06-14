<template>
    <div class="flex h-screen bg-gray-100">
        <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />
        <div class="flex-1 p-8 pt-4 bg-white">
            <HeaderBar title="Acara" class="mt-3" />
            <div class="my-4 border-b border-gray-300"></div>

            <div class="pb-12">
                <!-- Tabel Data Acara -->
                <div class="bg-white rounded-lg shadow border border-gray-300 mt-8 overflow-hidden">
                    <div class="flex justify-between items-center px-5 p-3 border-b border-gray-300">
                        <h3 class="text-sm font-semibold text-gray-900">
                            Data Acara Acara posyandu
                        </h3>
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
                                <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                                <td>{{ formatTanggalRange(news.tanggal_mulai, news.tanggal_selesai) }}</td>
                                <td>{{ news.waktu }}</td>
                                <td>{{ news.kategori.nama }}</td>
                                <td>{{ news.nama_acara }}</td>
                                <td>{{ news.lokasi }}</td>
                                <td>
                                    <div class="flex items-center space-x-2 justify-center">
                                        <button title="Informasi" @click="navigateTo('informasi', news)"
                                            class="hover:opacity-70 border-r-1 pr-2 cursor-pointer">
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
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            Prev
                        </button>
                        <span>Halaman {{ currentPage }} dari {{ totalPages }}</span>
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
            message="Apakah Anda yakin ingin menghapus data ini?" @cancel="cancelDelete" @confirm="deleteAcara" />
    </div>
</template>

<script>
import Sidebar from "@/components/Sidebar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import ModalConfirm from "@/components/ModalConfirm.vue";
import informasiIcon from "@/assets/Informasi.svg";
import updateIcon from "@/assets/Edit.svg";
import deleteIcon from "@/assets/Delete.svg";
import SuccessAlert from "@/components/SuccessAlert.vue";
import axios from "axios";

export default {
    name: "DataAcara",
    components: { Sidebar, HeaderBar, ModalConfirm, SuccessAlert },
    data() {
        return {
            activeMenu: "acara",
            showModal: false,
            acaraToDelete: null,
            acaraList: [],
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
        paginatedAcaraList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.acaraList.slice(start, start + this.itemsPerPage);
        },
        totalPages() {
            return Math.ceil(this.acaraList.length / this.itemsPerPage);
        },
    },
    created() {
        this.fetchLaporanAcara();
    },
    methods: {
        updateActiveMenu(menu) {
            this.activeMenu = menu;
        },
        formatTanggalRange(tglMulai, tglSelesai) {
            const format = (tgl) => {
                const date = new Date(tgl);
                return {
                    day: String(date.getDate()).padStart(2, '0'),
                    month: date.toLocaleString('id-ID', { month: 'long' }),
                    year: date.getFullYear()
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
        async fetchLaporanAcara() {
            try {
                const token = localStorage.getItem("token");
                if (!token) throw new Error("Token tidak ditemukan");
                const response = await axios.get("http://localhost:8000/api/acara", {
                    headers: { Authorization: `Bearer ${token}` },
                });
                this.acaraList = response.data.data.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            } catch (error) {
                console.error("Gagal mengambil data:", error);
            }
        },
        navigateTo(action, acara) {
            localStorage.setItem(`dataAcara${action}`, JSON.stringify(acara));
            this.$router.push(`/acara-${action}/${acara.id}`);
        },
        confirmDelete(acara) {
            this.acaraToDelete = acara;
            this.showModal = true;
        },
        cancelDelete() {
            this.showModal = false;
            this.acaraToDelete = null;
        },
        async deleteAcara() {
            try {
                const token = localStorage.getItem("token");
                if (!token) throw new Error("Token tidak ditemukan");
                await axios.delete(`http://localhost:8000/api/acara/${this.acaraToDelete.id}`, {
                    headers: { Authorization: `Bearer ${token}` },
                });
                this.successMessage = "Acara berhasil dihapus!";
                this.showSuccessAlert = true;
                setTimeout(() => (this.showSuccessAlert = false), 2000);
                this.fetchLaporanAcara();
            } catch (error) {
                console.error("Gagal menghapus data:", error);
            } finally {
                this.cancelDelete();
            }
        },
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
