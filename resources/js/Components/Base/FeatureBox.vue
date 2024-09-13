<script setup>
import { defineProps } from 'vue';

defineProps({
    icon: {
        type: Function,
        required: true,
    },
    title: {
        type: String,
        required: true,
        default: 'Feature Title',
    },
    description: {
        type: String,
        required: true,
        default: 'Feature description goes here.',
    },
    comingSoon: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>

<template>
    <div
        class="relative flex items-center p-8 border border-transparent rounded-lg shadow-lg bg-white hover:bg-gray-50 transition duration-300"
        :class="comingSoon ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
    >
        <div v-if="comingSoon" class="absolute top-2 right-2 bg-blue-100 text-blue-700 text-xs font-bold py-1 px-2 rounded">
            Coming Soon
        </div>

        <div class="flex-shrink-0 p-4 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 shadow-sm">
            <component :is="icon" class="h-10 w-10 text-blue-600" />
        </div>

        <div class="ml-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
            <p class="text-sm text-gray-600">{{ description }}</p>
        </div>
    </div>
</template>
