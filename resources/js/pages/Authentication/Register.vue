<template>
    <div class="flex min-h-screen flex-col md:flex-row">
        <!-- Left Side (Hidden on Mobile) -->
        <div class="hidden md:flex w-full md:w-2/5 text-white rounded-r-lg overflow-hidden bg-cover bg-center opacity-80"
            :style="{ backgroundImage: `url(${mosqueBackground})` }">
            <div
                class="space-y-2 p-8 bg-black/50 h-full flex flex-col items-center justify-center text-center mb-5 w-[100%]">

                <h2 class="text-3xl font-bold">Selamat Datang</h2>
                <p class="text-base leading-relaxed text-white/90">
                    Mengelola informasi kesehatan ibu dan anak, laporan kegiatan, dan data kunjungan dalam satu platform
                    praktis.
                </p>
            </div>
        </div>

        <!-- Right Side -->
        <div class="w-full md:w-3/5 flex flex-col items-center pt-5 overflow-hidden">
            <div class="flex items-center gap-3 self-start ml-12 mb-10">
                <img :src="logoImage" alt="Logo Image" class="w-[75px] rounded-t-lg object-cover" />
                <span class="logo-text text-[20px] font-bold text-[#08607a] font-['Protest_Strike']">MasjidCare</span>
            </div>

            <div class="flex flex-col items-center text-center mb-5 w-[100%] max-w-md">
                <h2 class="text-3xl font-semibold mb-2 w-full">Register Akun MasjidCare</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Monitor, Manage, and Care Better
                </p>

                <form @submit.prevent="registerNext" class="w-full">
                    <!-- Email Input -->
                    <div class="mb-4">
                        <label class="block text-left font-semibold mb-1">Email</label>
                        <div class="relative">
                            <img src="@/assets/email.svg"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-6 h-6 opacity-50" />
                            <input type="email" v-model="form.email" placeholder="Enter your email" required
                                class="pl-12 pr-4 py-3 w-full rounded-xl border border-gray-300 text-gray-800 focus:outline-none" />
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4 ">
                        <label class="block text-left font-semibold mb-1">Password</label>
                        <div class="relative">
                            <img src="@/assets/password.svg"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-6 h-6 opacity-60" />
                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                                placeholder="Masukkan password Anda" minlength="8" required
                                class="pl-12 pr-10 py-3 w-full rounded-xl border border-gray-300 text-gray-800 focus:outline-none " />
                            <button type="button" @click="togglePassword"
                                class="absolute top-1/2 right-3 transform -translate-y-1/2 ">
                                <img :src="showPassword ? eyeOffIcon : eyeIcon" alt="Toggle Password" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit"
                        class="cursor-pointer w-full mt-2 py-3 bg-[#4f93af] text-white font-semibold rounded-3xl hover:bg-[#166357]">
                        Register
                    </button>
                </form>

                <!-- Message -->
                <p v-if="message" :class="messageClass + ' mt-3 text-sm'">
                    {{ message }}
                </p>

                <!-- Divider -->
                <div class="w-full my-3 flex items-center justify-center">
                </div>

                <!-- Google Login -->
                <div class="flex justify-center gap-2 mb-5">
                    <button @click="googleRegister"
                        class="flex items-center justify-center px-6 py-2 border border-gray-300 rounded-full bg-white cursor-pointer text-sm text-center w-55 hover:bg-gray-100 transition">
                        <img :src="googleLogo" alt="Google Logo" class="w-5 h-5 mr-2" />
                        <strong>Register with Google</strong>
                    </button>
                </div>

                <!-- Sign Up Link -->
                <p class="text-sm">
                    Already have an account?
                    <router-link to="/login" class="text-sm text-[#ffad54] hover:underline">Sign
                        in</router-link>
                </p>
            </div>
        </div>
    </div>
</template>


<script>
import axios from "axios";
import mosqueBackground from "@/assets/mosque-background.png";
import eyeIcon from "@/assets/eye.svg";
import eyeOffIcon from "@/assets/eye-off.svg";
import babyImage from "@/assets/baby.jpg";
import dokterImage from "@/assets/dokter.png";
import logoImage from "@/assets/logo2.png";
import googleLogo from "@/assets/Google.svg";
import appleLogo from "@/assets/Apple.svg";
import informIcon from "@/assets/inform.svg";
import SuccessAlert from "@/components/SuccessAlert.vue";

export default {
    components: {
        SuccessAlert,
    },
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            mosqueBackground,
            message: "",
            messageClass: "",
            showPassword: false,
            babyImage,
            dokterImage,
            logoImage,
            googleLogo,
            appleLogo,
            eyeIcon,
            eyeOffIcon,
            informIcon,
            showSuccessAlert: false,
            successMessage: "",
        };
    },
    mounted() {
        const url = window.location.href;
        const emailMatch = url.match(/email=([^&#]+)/); // Cari email setelah tanda '=' sebelum '#' atau '&'
        const tokenMatch = url.match(/token=([^&#]+)/); // Cari token dengan cara yang sama

        if (tokenMatch && emailMatch) {
            const token = decodeURIComponent(tokenMatch[1]);
            const email = decodeURIComponent(emailMatch[1]);

            localStorage.setItem("auth_token", token);
            localStorage.setItem("email", email);
            localStorage.setItem("password", "from_google_oauth");
            console.log("Token & email dari Google berhasil disimpan:", email);
            window.history.replaceState({}, document.title, "/register");
        }
    },
    methods: {
        mounted() {
            const emailMatch = url.match(/email=([^&#]+)/); // Cari email setelah tanda '=' sebelum '#' atau '&'
            const tokenMatch = url.match(/token=([^&#]+)/); // Cari token dengan cara yang sama

            if (tokenMatch && emailMatch) {
                const token = decodeURIComponent(tokenMatch[1]);
                const email = decodeURIComponent(emailMatch[1]);

                localStorage.setItem("auth_token", token);
                localStorage.setItem("email", email);
                localStorage.setItem("password", "from_google_oauth");
                console.log("Token & email dari Google berhasil disimpan:", email);
                window.history.replaceState({}, document.title, "/register");
            }
        },
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        googleRegister() {
            const backendUrl =
                import.meta.env.VITE_APP_URL ||
                "http://localhost:3000";
            window.location.href = `${backendUrl}/auth/google`;
        },
        onFileChange(field, event) {
            const file = event.target.files[0];
            if (file) {
                this.files[field] = file;
            }
        },
        async registerNext() {
            // Simpan email dan password ke localStorage
            localStorage.setItem("email", this.form.email);
            localStorage.setItem("password", this.form.password);

            try {
                const apiUrl = import.meta.env.VITE_APP_URL || "http://localhost:8000/api";

                const response = await axios.post(`${apiUrl}/check`, {
                    email: this.form.email,
                });

                if (response.status === 200) {
                    this.successMessage = "Lanjutkan pendaftaran!";
                    this.showSuccessAlert = true;

                    this.$router.push("/register-next");
                }
            } catch (error) {
                console.error("Error:", error);

                if (error.response?.status === 404) {
                    this.message = error.response.data.message || "Email tidak tersedia.";
                } else {
                    this.message = "Terjadi kesalahan saat memeriksa email.";
                }

                this.messageClass = "text-red-500";
            }
        }

    },
};
</script>
<style scoped>
.logo-text {
    font-family: "Protest Strike", sans-serif;
    font-size: 26px;
    color: #08607a;
}
</style>