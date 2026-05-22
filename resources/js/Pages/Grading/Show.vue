<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    attempt: Object
});

const form = useForm({
    scores: {}
});

if (props.attempt && props.attempt.responses) {
    props.attempt.responses.forEach(response => {
        form.scores[response.question_id] = response.score || 0;
    });
}

const submitScores = () => {
    form.post(`/attempts/${props.attempt.id}/grade`);
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-200">

            <div class="border-b pb-6 mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Koreksi Lembar Jawaban</h1>
                    <p class="text-gray-600 mt-2">Nama Siswa: <span class="font-bold text-gray-800">{{ attempt.user.name }}</span></p>
                    <p class="text-gray-600">Ujian: <span class="font-bold text-gray-800">{{ attempt.exam.title }}</span></p>
                </div>
                <div class="text-right bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <p class="text-sm text-blue-800 font-semibold">Total Nilai Sementara</p>
                    <p class="text-4xl font-black text-blue-600">{{ attempt.total_score }}</p>
                </div>
            </div>

            <form @submit.prevent="submitScores" class="space-y-8">

                <div v-for="(response, index) in attempt.responses" :key="response.id" class="p-6 border rounded-xl" :class="response.question.type === 'essay' ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200'">

                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-bold text-lg text-gray-800">{{ index + 1 }}. {{ response.question.question_text }}</h3>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider ml-4 whitespace-nowrap">
                            {{ response.question.type.replace('_', ' ') }}
                        </span>
                    </div>

                    <div class="bg-white p-4 rounded border mb-4">
                        <p class="text-sm font-semibold text-gray-500 mb-1">Jawaban Siswa:</p>
                        <p v-if="response.answer_text" class="text-gray-800 text-lg">{{ response.answer_text }}</p>
                        <p v-else class="text-gray-400 italic">Jawaban PG tercatat otomatis di sistem / Kosong.</p>
                    </div>

                    <div class="flex items-center gap-4 bg-white p-3 rounded shadow-sm border border-gray-100 w-max">
                        <label class="font-bold text-gray-700">Poin Didapat:</label>

                        <div v-if="response.question.type !== 'essay'" class="flex items-center gap-2">
                            <span class="text-2xl font-black text-green-600">{{ response.score }}</span>
                            <span class="text-gray-400">/ {{ response.question.points }} (Otomatis)</span>
                        </div>

                        <div v-else class="flex items-center gap-2">
                            <input type="number" v-model="form.scores[response.question.id]" min="0" :max="response.question.points" class="w-24 text-xl font-bold border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500 p-2 text-center" required>
                            <span class="text-gray-500 font-bold">/ {{ response.question.points }} Maksimal</span>
                        </div>
                    </div>

                </div>

                <div class="pt-6 border-t flex justify-end gap-4">
                    <Link :href="'/exams/' + attempt.exam_id + '/results'" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition">
                        Batal
                    </Link>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                        Simpan Nilai & Selesaikan Koreksi
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

