<template>
  <div class="flex h-screen bg-gray-100">
    <Sidebar :activeMenu="activeMenu" @update:activeMenu="updateActiveMenu" />
    <div class="flex-1 p-8 pt-4 bg-white">
      <HeaderBar title="Supplier" class="mt-3" />
      <div class="my-4 border-b border-gray-300"></div>

      <div class="pb-12">
        <!-- Bagian Filter -->
        <div class="filters space-y-4">
          <!-- <div class="flex flex-wrap gap-4 w-full">
                        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md bg-white relative flex-1 cursor-pointer"
                            @click="toggleDropdown('kategori')">
                            <img src="@/assets/posko.svg" alt="Kategori" class="w-5 h-5" />
                            <span class="flex-1 text-sm text-gray-500 truncate">
                                {{ selectedKategori || "Pilih Kategori Supplier" }}
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
                    </div> -->

          <div class="relative">
            <input type="text" v-model="searchQuery" @input="onInputSearch" placeholder="Cari nama supplier..."
              class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-4 text-sm text-gray-700" />
            <img src="@/assets/search.svg" alt="Search"
              class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
          </div>
        </div>

        <!-- Tabel Data Supplier -->
        <div class="bg-white rounded-lg shadow border border-gray-300 mt-8 overflow-hidden">
          <div class="flex justify-between items-center px-5 p-3 border-b border-gray-300">
            <h3 class="text-sm font-semibold text-gray-900">
              Data Supplier Masjid
            </h3>
            <button @click="exportToExcel"
              class="cursor-pointer text-sm font-semibold text-green-600 hover:text-green-800 border border-green-600 px-3 py-1 rounded-md">
              Export Excel
            </button>
            <router-link to="/supplier-add"
              class="text-sm font-semibold text-[#074a5d] no-underline hover:text-[#0066cc] hover:no-underline">
              Tambah Supplier
            </router-link>
          </div>

          <table class="w-full table-fixed border-collapse border border-gray-300">
            <thead class="bg-gray-100 text-[#7d7f81]">
              <tr>
                <th class="w-16">No</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(supplier, index) in paginatedSupplierList" :key="index" class="text-[#333436]">
                <td>
                  {{
                    (currentPage - 1) * itemsPerPage +
                    index +
                    1
                  }}
                </td>
                <td>{{ supplier.nama_supplier }}</td>
                <td>{{ supplier.alamat }}</td>
                <td>{{ supplier.kontak }}</td>
                <td>{{ supplier.email }}</td>
                <td>
                  <div class="flex items-center space-x-2 justify-center">
                    <button title="Informasi" @click="
                      navigateTo('informasi', supplier)
                      " class="cursor-pointer hover:opacity-70 border-r-1 pr-2 cursor-pointer">
                      <img :src="informasiIcon" alt="Informasi" class="w-5 h-5 object-contain" />
                    </button>
                    <button title="Edit" @click="navigateTo('edit', supplier)"
                      class="cursor-pointer hover:opacity-70 border-r-1 pr-2 cursor-pointer">
                      <img :src="updateIcon" alt="Update" class="w-5 h-5 object-contain" />
                    </button>
                    <button title="Hapus" @click="confirmDelete(supplier)" class="cursor-pointer hover:opacity-70 cursor-pointer">
                      <img :src="deleteIcon" alt="Delete" class="w-5 h-5 object-contain" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination Controls -->
          <div class="flex justify-between items-center px-4 py-3 border-t border-gray-300 text-sm text-[#333436]">
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
      message="Apakah Anda yakin ingin menghapus data ini?" @cancel="cancelDelete" @confirm="deleteSupplier" />
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
export default {
  name: "DataSupplier",
  components: { Sidebar, HeaderBar, ModalConfirm, SuccessAlert },

  data() {
    return {
      activeMenu: "inventaris",
      searchQuery: "",
      showModal: false,
      showSuccessAlert: false,
      successMessage: "",
      supplierToDelete: null,
      dropdownOpen: null,
      debouncedSearch: null,

      supplierList: [],
      informasiIcon,
      updateIcon,
      deleteIcon,

      currentPage: 1,
      itemsPerPage: 10,
    };
  },

  computed: {
    filteredSupplierList() {
      return this.supplierList
        .filter(a => !this.searchQuery || a.nama_supplier.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },
    paginatedSupplierList() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.supplierList.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.supplierList.length / this.itemsPerPage);
    },
  },

  created() {
    this.debouncedSearch = this.debounce(this.searchSupplier, 500);
    this.fetchLaporanSupplier();
    this.fetchKategori();
  },

  methods: {
    exportToExcel() {
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet('Data Acara');

      worksheet.columns = [
        { header: 'No', key: 'no', width: 5 },
        { header: 'Nama_Supplier', key: 'nama_supplier', width: 20 },
        { header: 'Alamat', key: 'alamat', width: 20 },
        { header: 'Kontak', key: 'kontak', width: 20 },
        { header: 'Email', key: 'email', width: 20 },
      ];

      this.filteredSupplierList.forEach((a, i) => {
        worksheet.addRow({
          no: i + 1,
          nama_supplier: a.nama_supplier,
          alamat: a.alamat,
          kontak: a.kontak,
          email: a.email
        });
      });

      workbook.xlsx.writeBuffer().then((buffer) => {
        saveAs(new Blob([buffer]), 'Data_Supplier.xlsx');
      });
    },

    // 🔍 Search & Filter
    onInputSearch() {
      this.debouncedSearch();
    },
    searchSupplier() {
      if (this.searchQuery.trim() === "") {
        this.fetchLaporanSupplier();
      } else {
        this.supplierList = this.filteredSupplierList;
      }
    },

    // 📦 Fetch
    async fetchLaporanSupplier() {
      try {
        const token = localStorage.getItem("token");
        const res = await axios.get("http://localhost:8000/api/supplier", {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.supplierList = res.data.data;
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
    navigateTo(action, supplier) {
      localStorage.setItem(`dataSupplier${action}`, JSON.stringify(supplier));
      this.$router.push(`/supplier-${action}/${supplier.id}`);
    },

    // ❌ Delete
    confirmDelete(supplier) {
      this.supplierToDelete = supplier;
      this.showModal = true;
    },
    cancelDelete() {
      this.supplierToDelete = null;
      this.showModal = false;
    },
    async deleteSupplier() {
      try {
        const token = localStorage.getItem("token");
        await axios.delete(`http://localhost:8000/api/supplier/${this.supplierToDelete.id}`, {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.successMessage = "Supplier berhasil dihapus!";
        this.showSuccessAlert = true;
        setTimeout(() => this.showSuccessAlert = false, 2000);
        this.fetchLaporanSupplier();
      } catch (err) {
        console.error("Gagal menghapus supplier:", err);
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
