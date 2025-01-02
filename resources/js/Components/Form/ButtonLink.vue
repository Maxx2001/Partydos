<script setup>
defineProps({
    route: {
        type: Function,
        required: true
    },
    label: {
        type: String,
        required: true
    }
});
</script>

<template>
    <a
        :href="route"
        class="inline-block w-full mt-4 py-3 bg-gradient-to-br from-purple-500 to-pink-500 text-white font-bold text-center rounded-md shadow-md focus:ring-4 focus:ring-pink-300 focus:outline-none transition-transform transform"
    >
        {{ label }}
    </a>
</template>
