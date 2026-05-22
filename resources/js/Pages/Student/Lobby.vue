<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    exam: Object
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
        <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">

            <div class="bg-blue-600 p-8 text-white text-center">
                <h1 class="text-3xl font-extrabold mb-2">Persiapan Ujian</h1>
                <p class="text-blue-100 text-lg">{{ exam.title }}</p>
            </div>

            <div class="p-8">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-8 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-800 font-semibold uppercase">Durasi Pengerjaan</p>
                        <p class="text-2xl font-bold text-blue-900">{{ exam.duration_minutes }} Menit</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-800 font-semibold uppercase">Status Ujian</p>
                        <p class="text-lg font-bold text-green-600">Siap Dikerjakan</p>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Peraturan & Ketentuan</h3>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-gray-700"><strong>Waktu Berjalan Otomatis:</strong> Setiap soal memiliki batasan waktu tersendiri (PG 1 Menit, Isian 2 Menit, Essay 3 Menit).</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-gray-700"><strong>Auto-Next Paksa:</strong> Jika waktu di satu soal habis, sistem akan langsung memaksa Anda pindah ke soal berikutnya tanpa bisa kembali.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span class="text-gray-700"><strong>Sekali Jalan (One-Time Attempt):</strong> Ujian hanya bisa dikerjakan 1 (satu) kali. Pastikan koneksi internet Anda stabil sebelum menekan tombol mulai.</span>
                    </li>
                </ul>

                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t">
                    <Link href="/dashboard" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-6 rounded-xl transition">
                        Kembali ke Dashboard
                    </Link>
                    <Link :href="'/take-exam/' + exam.id + '/start'" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition">
                        Mulai Kerjakan Sekarang
                    </Link>
                </div>

            </div>
        </div>
    </div>
</template>

