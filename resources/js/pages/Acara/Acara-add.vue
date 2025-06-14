<template>
  <div class="flex h-screen bg-gray-100">
    <Sidebar :activeMenu="activeMenu" @update:activeMenu="activeMenu = $event" />

    <div class="flex-1 p-8 pt-7 flex flex-col bg-white">
      <HeaderBar title="Tambah Acara Masjid" />
      <div class="border-b border-gray-300"></div>

      <div class="bg-white p-6 rounded-2xl shadow mt-8">
        <h3 class="text-[15px] text-[#074a5d] font-semibold mb-4">
          Formulir Tambah Acara Masjid
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
            <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal Mulai</label>
            <input type="date" v-model="formData.tanggal_mulai"
              class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
          </div>

          <div class="flex items-center gap-5">
            <label class="min-w-[150px] font-semibold text-sm text-black">Tanggal Selesai</label>
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
            <input type="text" v-model="formData.status" @input="
              formData.status = sanitizeInput(formData.status)
              " maxlength="100" placeholder="Status (direncanakan, berjalan, selesai, dibatalkan)"
              class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-sm" />
          </div>

          <div class="text-right mt-6">
            <button @click="submitAcara"
              class="bg-[#074a5d] text-white px-4 py-2 rounded-lg hover:bg-[#063843] transition">
              Simpan Acara
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
  name: "TambahAcaraComponent",
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
    this.fetchKategori();
  },
  methods: {
    fetchKategori() {
      const token = localStorage.getItem("token");
      axios
        .get("http://localhost:8000/api/kategori", {
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
    submitAcara() {
      const token = localStorage.getItem("token");

      axios
        .post("http://localhost:8000/api/acara", this.formData, {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
          },
        })
        .then(() => {
          alert("Acara berhasil ditambahkan.");
          this.$router.push("/acara");
        })
        .catch((err) => {
          alert("Gagal menambahkan acara.");
          console.error(err);
        });
    },
    sanitizeInput(text) {
      const div = document.createElement("div");
      div.textContent = text;
      return div.innerHTML;
    },
    formatKategoriName(nama) {
      return nama.charAt(0).toUpperCase() + nama.slice(1);
    },
  },
};
</script>
