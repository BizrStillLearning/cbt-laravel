<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    exams: Array
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen Ujian</h1>
                    <p class="text-gray-500 mt-1">Kelola daftar ujian, bank soal, dan jadwal pelaksanaan.</p>
                </div>

                <div class="flex gap-3">
                    <Link href="/dashboard" class="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition">
                        &larr; Kembali ke Dashboard
                    </Link>

                    <Link href="/exams/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                        + Buat Ujian Baru
                    </Link>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-100 text-gray-700 border-b">
                            <th class="p-4 font-semibold">Judul Ujian</th>
                            <th class="p-4 font-semibold text-center">Durasi</th>
                            <th class="p-4 font-semibold text-center">Status Jadwal</th>
                            <th class="p-4 font-semibold text-right">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="exams.length === 0">
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                Belum ada ujian yang dibuat. Silakan klik tombol "Buat Ujian Baru" di atas.
                            </td>
                        </tr>

                        <tr v-for="exam in exams" :key="exam.id" class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-800 text-lg">{{ exam.title }}</div>
                                <div class="text-sm text-gray-500">{{ exam.description || 'Tidak ada deskripsi' }}</div>
                            </td>
                            <td class="p-4 text-center text-gray-600 font-medium">
                                {{ exam.duration_minutes }} Menit
                            </td>

                            <td class="p-4 text-center">
                                <span v-if="exam.is_published" class="bg-green-100 text-green-700 px-3 py-1 text-xs rounded-full font-bold">Published</span>
                                <span v-else class="bg-gray-200 text-gray-600 px-3 py-1 text-xs rounded-full font-bold border border-gray-300">Draft</span>

                                <div v-if="exam.start_time" class="text-xs text-gray-500 mt-2">
                                    Mulai: {{ new Date(exam.start_time).toLocaleDateString('id-ID') }}
                                </div>
                            </td>

                            <td class="p-4 text-right space-x-2">
                                <Link :href="'/exams/' + exam.id + '/results'" class="inline-block bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 font-semibold px-3 py-1.5 rounded transition">
                                    Rekap Nilai
                                </Link>
                                <Link :href="'/exams/' + exam.id" class="inline-block bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-semibold px-3 py-1.5 rounded transition">
                                    Kelola Soal
                                </Link>
                                <Link :href="'/exams/' + exam.id + '/edit'" class="inline-block text-yellow-600 hover:text-yellow-700 font-semibold px-2 py-1.5 transition">
                                    Edit
                                </Link>
                                <Link :href="'/exams/' + exam.id" method="delete" as="button" class="inline-block text-red-600 hover:text-red-700 font-semibold px-2 py-1.5 transition" onclick="return confirm('Peringatan: Menghapus ujian ini akan menghapus semua soal dan data nilai murid di dalamnya. Anda yakin?')">
                                    Hapus
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

