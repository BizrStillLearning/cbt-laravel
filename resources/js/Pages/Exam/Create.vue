<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    duration_minutes: 60,
    is_published: false,
    start_time: '',
    end_time: ''
});

const submit = () => {
    form.post('/exams');
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 border-b pb-4 mb-6">Buat Ujian Baru</h1>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block font-bold text-gray-700">Judul Ujian</label>
                    <input type="text" v-model="form.title" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div>
                    <label class="block font-bold text-gray-700">Deskripsi Singkat</label>
                    <textarea v-model="form.description" rows="3" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700">Estimasi Total Waktu (Menit)</label>
                    <input type="number" v-model="form.duration_minutes" class="w-full border-gray-300 rounded-lg" min="1" required>
                </div>

                <div class="bg-blue-50 p-5 rounded-lg border border-blue-100 space-y-4">
                    <h3 class="font-bold text-blue-800 border-b border-blue-200 pb-2">Pengaturan Jadwal & Publikasi</h3>

                    <div>
                        <label class="block font-bold text-gray-700">Status Ujian</label>
                        <select v-model="form.is_published" class="w-full border-gray-300 rounded-lg">
                            <option :value="false">Mode Draft (Disembunyikan dari Murid)</option>
                            <option :value="true">Published (Aktif / Bisa Diakses)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 text-sm">Waktu Dibuka (Opsional)</label>
                            <input type="datetime-local" v-model="form.start_time" class="w-full border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 text-sm">Waktu Ditutup (Opsional)</label>
                            <input type="datetime-local" v-model="form.end_time" class="w-full border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <Link href="/exams" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg mr-3">Batal</Link>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Simpan Ujian</button>
                </div>
            </form>
        </div>
    </div>
</template>


