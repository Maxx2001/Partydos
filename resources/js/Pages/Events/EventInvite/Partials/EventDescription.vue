<script setup>
defineProps({
    event: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div class="flex items-center flex-col justify-center bg-slate-100 px-4 md:px-8">
        <div class="w-full max-w-6xl p-6 md:p-8">
            <!-- Event Title -->
            <p class="text-2xl md:text-3xl text-indigo-700 font-semibold text-center mb-4">
                Event Description
            </p>
            <!-- Event Description -->
            <p class="text-gray-700 text-base md:text-lg leading-relaxed ">
                {{ event.description }}
            </p>
        </div>
    </div>
</template>
