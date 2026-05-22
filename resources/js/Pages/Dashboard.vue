<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    role: String,
    stats: Object,
    exams: Array,
    my_attempts: Array
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard {{ role === 'guru' ? 'Panel Guru' : 'Panel Murid' }}
            </h2>
        </template>

        <div class="py-12 bg-gray-200/60 min-h-[calc(100vh-65px)]">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div v-if="role === 'guru'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase">Total Ujian Dibuat</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ stats.total_ujian }}</h3>
                            </div>
                            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase">Total Murid Terdaftar</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ stats.total_murid }}</h3>
                            </div>
                            <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase">Sesi Ujian Selesai</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ stats.total_mengerjakan }}</h3>
                            </div>
                            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Kontrol Manajemen</h4>
                        <div class="flex flex-wrap gap-4">
                            <Link href="/exams" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium shadow transition">
                                Kelola Bank Soal & Ujian &rarr;
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-4">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Ujian Aktif</h3>

                        <div v-if="exams.length === 0" class="bg-white p-6 rounded-xl text-center text-gray-500 border">
                            Belum ada jadwal ujian yang diterbitkan oleh guru.
                        </div>

                        <div v-for="exam in exams" :key="exam.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center hover:border-blue-300 transition">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">{{ exam.title }}</h4>
                                <p class="text-gray-500 text-sm mt-1">{{ exam.description || 'Tidak ada deskripsi' }}</p>
                                <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-md font-semibold mt-3">
                                    Durasi: {{ exam.duration_minutes }} Menit
                                </span>
                            </div>
                            <Link :href="'/take-exam/' + exam.id" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow transition text-sm">
                                Mulai Tes
                            </Link>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Ujian Anda</h3>

                        <div v-if="my_attempts.length === 0" class="bg-white p-6 rounded-xl text-center text-gray-500 border text-sm">
                            Anda belum pernah mengikuti ujian apa pun.
                        </div>

                        <div v-for="attempt in my_attempts" :key="attempt.id" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="font-bold text-gray-800 text-sm leading-tight">{{ attempt.exam.title }}</h5>
                                    <p class="text-xs text-gray-400 mt-1">{{ new Date(attempt.created_at).toLocaleDateString('id-ID') }}</p>
                                </div>
                                <div class="text-right">
                                    <span v-if="attempt.status === 'selesai'" class="text-xl font-black text-blue-600">{{ attempt.total_score }}</span>
                                    <span v-else class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-bold">Diproses</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
