<template>
    <div class="flex h-screen flex-col md:flex-row">
        <!-- Left Side (Hidden on Mobile) -->
        <div class="hidden md:flex w-full md:w-2/5 text-white rounded-r-lg overflow-hidden bg-cover bg-center opacity-80"
            :style="{ backgroundImage: `url(${mosqueBackground})` }">
      <div class="space-y-2 p-8 bg-black/50 h-full flex flex-col items-center justify-center text-center mb-5 w-[100%]">
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
                <span class="logo-text">MasjidCare</span>
            </div>

            <div class="flex flex-col items-center text-center mb-5 w-full max-w-md">
                <h2 class="text-3xl font-semibold mb-2 w-full">Masuk Akun MasjidCare</h2>
                <p class="text-sm text-gray-500 mb-6">Monitor, Manage, and Care Better</p>

                <form @submit.prevent="login" class="w-full">
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
                    <div class="mb-2">
                        <label class="block text-left font-semibold mb-1">Password</label>
                        <div class="relative">
                            <img src="@/assets/password.svg"
                                class="absolute top-1/2 left-3 transform -translate-y-1/2 w-6 h-6 opacity-60" />
                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                                placeholder="Masukkan password Anda" minlength="8" required
                                class="pl-12 pr-10 py-3 w-full rounded-xl border border-gray-300 text-gray-800 focus:outline-none" />
                            <button type="button" @click="togglePassword"
                                class="absolute top-1/2 right-3 transform -translate-y-1/2 cursor-pointer">
                                <img :src="showPassword ? eyeOffIcon : eyeIcon" alt="Toggle Password" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-right mb-3">
                        <router-link to="/forgot-password"
                            class="cursor-pointer text-sm font-semibold text-[#ffad54] hover:underline">
                            Forgot Password?
                        </router-link>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit"
                        class="w-full py-3 bg-[#4f93af] text-white font-semibold rounded-3xl hover:bg-[#166357] cursor-pointer">
                        Sign In
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
                    <button @click="loginWithGoogle"
                        class="flex items-center justify-center px-6 py-2 border border-gray-300 rounded-full bg-white cursor-pointer text-sm text-center w-50 hover:bg-gray-100 transition">
                        <img :src="googleLogo" alt="Google Logo" class="w-5 h-5 mr-2" />
                        <strong>Login with Google</strong>
                    </button>
                </div>

                <!-- Sign Up Link -->
                <p class="text-sm">
                    Don't have an account?
                    <router-link to="/register" class="text-sm text-[#ffad54] hover:underline">Sign Up</router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import mosqueBackground from "@/assets/mosque-background.png";
import axios from "axios";
import logoImage from "@/assets/logo.png";
import googleLogo from "@/assets/Google.svg";
import eyeIcon from "@/assets/eye.svg";
import eyeOffIcon from "@/assets/eye-off.svg";
import passwordIcon from "@/assets/password.svg";

export default {
    data() {
        return {
            form: {
                email: "",
                password: "",
                rememberMe: false,
            },
            mosqueBackground,
            message: "",
            messageClass: "",
            logoImage,
            googleLogo,
            showPassword: false,
            eyeIcon,
            eyeOffIcon,
            passwordIcon,
        };
    },
    methods: {
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        loginWithGoogle() {
            const backendUrl = import.meta.env.VITE_APP_URL || "http://localhost:8000";
            window.location.href = `${backendUrl}/auth/google`;
        },
        async login() {
            try {
                const apiUrl = import.meta.env.VITE_APP_URL || "http://localhost:8000/api";
                const { data } = await axios.post(`${apiUrl}/login`, this.form, {
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });

                const expiresIn = 7 * 24 * 60 * 60 * 1000;
                const expiryTime = Date.now() + expiresIn;

                localStorage.setItem("token", data.access_token);
                localStorage.setItem("token_expiry", expiryTime);
                axios.defaults.headers.common["Authorization"] = `Bearer ${data.access_token}`;

                const meResponse = await axios.post(`${apiUrl}/me`, {});
                const { posisi: role, nama_lengkap } = meResponse.data;

                localStorage.setItem("userRole", role);
                localStorage.setItem("userName", nama_lengkap);

                this.message = "Login berhasil!";
                this.messageClass = "text-green-600 font-bold";

                this.$router.push("/dashboard");
            } catch (error) {
                this.message = error.response?.data?.message || "Login gagal.";
                this.messageClass = "text-red-600 font-bold";
            }
        },
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
