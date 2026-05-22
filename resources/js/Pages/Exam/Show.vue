<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    exam: Object
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <div class="border-b pb-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">{{ exam.title }}</h1>
                <p class="text-gray-600 mt-2">{{ exam.description }}</p>

                <div class="mt-4 flex gap-3">
                    <div class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        Durasi: {{ exam.duration_minutes }} Menit
                    </div>

                    <Link :href="'/exams/' + exam.id + '/results'" class="inline-block bg-purple-600 text-white px-4 py-1 rounded-full text-sm font-semibold hover:bg-purple-700 transition">
                        Lihat Hasil Ujian &rarr;
                    </Link>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Daftar Soal</h2>

                <Link :href="'/exams/' + exam.id + '/questions/create'" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
                    + Tambah Soal
                </Link>
            </div>

            <div v-if="exam.questions && exam.questions.length === 0" class="text-center text-gray-500 py-8 border-2 border-dashed rounded-lg">
                Belum ada soal untuk ujian ini.
            </div>

            <ul v-else class="space-y-4">
                <li v-for="(question, index) in exam.questions" :key="question.id" class="border p-4 rounded-md shadow-sm bg-gray-50">

                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold text-lg text-gray-800">{{ index + 1 }}. {{ question.question_text }}</span>
                        <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded font-bold whitespace-nowrap ml-4">
                            {{ question.type === 'pilihan_ganda' ? 'Pilihan Ganda' : (question.type === 'isian' ? 'Isian' : 'Essay') }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 mb-3">Bobot: {{ question.points }} Poin</p>

                    <ul v-if="question.type === 'pilihan_ganda'" class="mt-2 space-y-2 ml-4">
                        <li v-for="(answer, ansIndex) in question.answers" :key="answer.id"
                            class="flex items-center gap-2 p-2 rounded"
                            :class="answer.is_correct ? 'bg-green-100 border border-green-300' : 'bg-white border'">

                            <span class="font-bold w-6">{{ String.fromCharCode(65 + ansIndex) }}.</span>
                            <span>{{ answer.answer_text }}</span>
                            <span v-if="answer.is_correct" class="text-green-700 text-sm font-bold ml-auto">&#10003; Kunci</span>
                        </li>
                    </ul>

                    <div v-else-if="question.type === 'isian' && question.answers && question.answers.length > 0" class="mt-2 ml-4 p-2 bg-blue-50 border border-blue-200 rounded">
                        <span class="text-sm font-bold text-blue-800">Kata Kunci Benar:</span>
                        <span class="text-sm text-blue-900">{{ question.answers[0].answer_text }}</span>
                    </div>

                    <div class="flex justify-end space-x-4 mt-4 pt-4 border-t border-gray-200">
                        <Link :href="`/exams/${exam.id}/questions/${question.id}/edit`" class="text-sm text-yellow-600 hover:text-yellow-800 font-bold">
                            Edit Soal
                        </Link>
                        <Link :href="`/exams/${exam.id}/questions/${question.id}`" method="delete" as="button" class="text-sm text-red-600 hover:text-red-800 font-bold" onclick="return confirm('Yakin ingin menghapus soal ini?')">
                            Hapus Soal
                        </Link>
                    </div>

                </li>
            </ul>

            <div class="mt-8">
                <Link href="/exams" class="text-blue-600 hover:underline font-semibold">&larr; Kembali ke Daftar Ujian</Link>
            </div>

        </div>
    </div>
</template>
