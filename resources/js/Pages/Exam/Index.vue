<script setup>
defineProps({
    exams: Array
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Ujian CBT</h1>

            <div v-if="exams.length === 0" class="text-center text-gray-500 py-10">
                Belum ada data ujian yang dibuat.
            </div>

            <ul v-else class="space-y-4">
                <li v-for="exam in exams" :key="exam.id" class="border p-4 rounded-md shadow-sm">
                    <h2 class="text-lg font-semibold">{{ exam.title }}</h2>
                    <p class="text-sm text-gray-600">Durasi: {{ exam.duration_minutes }} Menit</p>
                    <p class="text-sm text-gray-500 mt-2">{{ exam.description }}</p>
                </li>
            </ul>

        </div>
    </div>
</template>
