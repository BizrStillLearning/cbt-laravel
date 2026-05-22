<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    exam: Object,
    attempts: Array
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Rekap Nilai: {{ exam.title }}</h1>
                    <Link href="/exams" class="text-blue-600 hover:underline mt-2 inline-block font-semibold">&larr; Kembali ke Daftar Ujian</Link>
                </div>

                <a :href="'/exams/' + exam.id + '/export'" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Excel
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-gray-100 border-b text-gray-700">
                        <th class="p-4 font-semibold">Nama Siswa</th>
                        <th class="p-4 font-semibold text-center">Status Koreksi</th>
                        <th class="p-4 font-semibold text-center">Waktu Pengerjaan</th>
                        <th class="p-4 font-semibold text-center">Nilai Akhir</th>
                        <th class="p-4 font-semibold text-right">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="attempts.length === 0">
                        <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Belum ada murid yang mengerjakan ujian ini.</td>
                    </tr>
                    <tr v-for="attempt in attempts" :key="attempt.id" class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-800">{{ attempt.user.name }}</td>

                        <td class="p-4 text-center">
                            <span v-if="attempt.status === 'selesai'" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                            <span v-else class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold animate-pulse">Butuh Dikoreksi</span>
                        </td>

                        <td class="p-4 text-center text-sm text-gray-600">
                            {{ new Date(attempt.updated_at).toLocaleString('id-ID') }}
                        </td>

                        <td class="p-4 text-center font-black text-2xl text-blue-600">{{ attempt.total_score }}</td>

                        <td class="p-4 text-right">
                            <Link :href="'/attempts/' + attempt.id + '/grade'" class="inline-block bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold px-4 py-2 rounded transition">
                                Beri Nilai / Cek Lembar &rarr;
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

