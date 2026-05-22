<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    exam: Object,
    questions: Array
});

const currentIndex = ref(0);
const isFinished = ref(false);
const timeLeft = ref(0);
let timerInterval = null;

const form = useForm({
    responses: {}
});

const currentQuestion = computed(() => props.questions[currentIndex.value]);

const formattedTime = computed(() => {
    const minutes = Math.floor(timeLeft.value / 60);
    const seconds = timeLeft.value % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

const setupTimerForCurrentQuestion = () => {
    const type = currentQuestion.value.type;

    if (type === 'pilihan_ganda') timeLeft.value = 60;
    else if (type === 'isian') timeLeft.value = 120;
    else if (type === 'essay') timeLeft.value = 180;

    clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
        } else {
            nextQuestion();
        }
    }, 1000);
};

const nextQuestion = () => {
    if (currentIndex.value < props.questions.length - 1) {
        currentIndex.value++;
        setupTimerForCurrentQuestion();
    } else {
        clearInterval(timerInterval);
        isFinished.value = true;
        submitExam();
    }
};

const cheatWarnings = ref(0);
const maxWarnings = 3;

const handleVisibilityChange = () => {
    if (document.hidden) {
        cheatWarnings.value++;

        if (cheatWarnings.value > maxWarnings) {
            alert('🚨 PELANGGARAN FATAL! Anda telah meninggalkan halaman ujian melebihi batas maksimal. Ujian dihentikan paksa.');
            submitExam();
        } else {
            alert(`⚠️ PERINGATAN KECURANGAN! \nAnda terdeteksi meninggalkan tab/halaman ujian. (Peringatan ${cheatWarnings.value} dari ${maxWarnings}). \n\nJika mencapai maksimal, nilai Anda akan langsung dikirim apa adanya!`);
        }
    }
};

const submitExam = () => {
    form.post('/take-exam/' + props.exam.id + '/submit');
};

onMounted(() => {
    document.addEventListener('visibilitychange', handleVisibilityChange);

    if (props.questions && props.questions.length > 0) {
        setupTimerForCurrentQuestion();
    }
});

onUnmounted(() => {
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    clearInterval(timerInterval);
});
</script>

<template>
    <div class="min-h-screen bg-gray-200 flex items-center justify-center p-4">

        <div v-if="isFinished" class="bg-white p-10 rounded-lg shadow-xl text-center max-w-lg w-full">
            <h1 class="text-3xl font-bold text-green-600 mb-4">Ujian Selesai!</h1>
            <p class="text-gray-600 mb-6">Jawaban Anda sedang diproses dan dikirim ke server.</p>
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto"></div>
        </div>

        <div v-else class="bg-white rounded-lg shadow-xl max-w-3xl w-full overflow-hidden">

            <div class="bg-blue-600 text-white p-6 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold">{{ exam.title }}</h1>
                    <p v-if="questions && questions.length > 0" class="text-blue-200 text-sm">Soal {{ currentIndex + 1 }} dari {{ questions.length }}</p>
                </div>

                <div v-if="questions && questions.length > 0" class="text-right">
                    <p class="text-xs text-blue-200 mb-1 uppercase tracking-wider">Sisa Waktu Soal Ini</p>
                    <div class="text-3xl font-mono font-bold font-tabular-nums" :class="{'text-red-300 animate-pulse': timeLeft <= 10}">
                        {{ formattedTime }}
                    </div>
                </div>
            </div>

            <div v-if="questions && questions.length > 0">
                <div class="p-8">
                    <h2 class="text-xl text-gray-800 font-semibold mb-6">
                        {{ currentQuestion.question_text }}
                    </h2>

                    <div v-if="currentQuestion.type === 'pilihan_ganda'" class="space-y-3">
                        <label v-for="(answer, ansIndex) in currentQuestion.answers" :key="answer.id"
                               class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-blue-50 transition"
                               :class="{'border-blue-500 bg-blue-50': form.responses[currentQuestion.id] === answer.id}">

                            <input type="radio" :name="'q_' + currentQuestion.id" :value="answer.id"
                                   v-model="form.responses[currentQuestion.id]" class="w-5 h-5 text-blue-600 mr-4">

                            <span class="font-bold mr-2 text-gray-500">{{ String.fromCharCode(65 + ansIndex) }}.</span>
                            <span>{{ answer.answer_text }}</span>
                        </label>
                    </div>

                    <div v-else-if="currentQuestion.type === 'isian'">
                        <input type="text" v-model="form.responses[currentQuestion.id]"
                               class="w-full border-2 border-gray-300 p-4 rounded-lg focus:border-blue-500 focus:ring-0 text-lg"
                               placeholder="Ketik jawaban singkat Anda di sini..." autocomplete="off">
                    </div>

                    <div v-else-if="currentQuestion.type === 'essay'">
                        <textarea v-model="form.responses[currentQuestion.id]" rows="6"
                                  class="w-full border-2 border-gray-300 p-4 rounded-lg focus:border-blue-500 focus:ring-0"
                                  placeholder="Uraikan jawaban Anda secara lengkap di sini..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 border-t flex justify-end">
                    <button @click="nextQuestion" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow hover:bg-blue-700 transition">
                        {{ currentIndex === questions.length - 1 ? 'Selesai & Kirim' : 'Simpan & Lanjut Soal Berikutnya &rarr;' }}
                    </button>
                </div>
            </div>

            <div v-else class="text-center p-12 bg-white">
                <h2 class="text-2xl text-red-600 font-bold mb-3">Ujian Belum Siap!</h2>
                <p class="text-gray-600 mb-6">Guru belum memasukkan soal apa pun ke dalam ujian ini. Silakan hubungi guru yang bersangkutan.</p>
                <Link href="/dashboard" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-6 py-3 rounded-lg transition">
                    &larr; Kembali ke Dashboard
                </Link>
            </div>

        </div>
    </div>
</template>
