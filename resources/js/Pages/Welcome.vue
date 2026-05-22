<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center relative overflow-hidden">

        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-48 -right-24 w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center px-6">

            <div class="mb-8 inline-flex items-center justify-center p-4 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 tracking-tight">
                Computer-Based Test <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Platform</span>
            </h1>

            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Sistem evaluasi akademik digital yang interaktif, aman, dan terintegrasi. Silakan masuk untuk memulai sesi ujian atau mengelola bank soal.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <Link href="/login" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-xl shadow-lg shadow-blue-200 transition duration-300 transform hover:-translate-y-1">
                    Masuk (Login)
                </Link>
                <Link href="/register" class="bg-white hover:bg-gray-50 text-gray-800 font-bold py-4 px-10 rounded-xl shadow border border-gray-200 transition duration-300 transform hover:-translate-y-1">
                    Daftar Akun
                </Link>
            </div>

        </div>
    </div>
</template>

<style scoped>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
