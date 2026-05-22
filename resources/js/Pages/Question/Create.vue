<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    exam: Object
});

const form = useForm({
    type: 'pilihan_ganda',
    question_text: '',
    points: 10,

    answers: ['', '', '', ''],
    correct_answer: 0,

    keyword_answer: ''
});

const submit = () => {
    form.post('/exams/' + props.exam.id + '/questions');
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-200">

            <div class="border-b pb-4 mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Tambah Soal Baru</h1>
                <Link :href="'/exams/' + exam.id" class="text-blue-600 hover:underline text-sm">&larr; Batal & Kembali</Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Tipe Soal</label>
                        <select v-model="form.type" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="pilihan_ganda">Pilihan Ganda</option>
                            <option value="isian">Isian Singkat</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Bobot Nilai (Poin)</label>
                        <input type="number" v-model="form.points" min="1" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-2">Pertanyaan</label>
                    <textarea v-model="form.question_text" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik soal Anda di sini..." required></textarea>
                </div>

                <div v-if="form.type === 'pilihan_ganda'" class="bg-gray-50 p-6 rounded-lg border">
                    <h3 class="font-bold text-gray-700 mb-4">Opsi Jawaban (Pilih salah satu radio button sebagai Kunci Benar)</h3>
                    <div class="space-y-3">
                        <div v-for="(answer, index) in form.answers" :key="index" class="flex items-center gap-4">
                            <input type="radio" :value="index" v-model="form.correct_answer" class="w-5 h-5 text-green-600 focus:ring-green-500" required>

                            <span class="font-bold text-gray-500">{{ String.fromCharCode(65 + index) }}.</span>

                            <input type="text" v-model="form.answers[index]" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" :placeholder="'Opsi ' + String.fromCharCode(65 + index)" required>
                        </div>
                    </div>
                </div>

                <div v-if="form.type === 'isian'" class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                    <label class="block font-bold text-blue-800 mb-2">Kata Kunci Jawaban Benar</label>
                    <input type="text" v-model="form.keyword_answer" class="w-full border-blue-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: jakarta, dki jakarta, daerah khusus ibukota jakarta" required>
                    <p class="text-sm text-blue-600 mt-2">
                        <span class="font-bold">Info:</span> Pisahkan dengan tanda koma (,). Sistem akan memberikan nilai benar jika jawaban siswa mengandung minimal salah satu dari kata kunci di atas.
                    </p>
                </div>

                <div v-if="form.type === 'essay'" class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-yellow-800 text-sm">
                            <span class="font-bold block mb-1">Catatan Ujian Essay:</span>
                            Anda tidak perlu memasukkan kunci jawaban. Sistem akan mengosongkan nilai untuk soal ini saat ujian selesai, dan Anda harus mengoreksi serta memberikan nilainya secara manual di halaman Hasil Ujian.
                        </p>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                        Simpan Soal Ke Database
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

