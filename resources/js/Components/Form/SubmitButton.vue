<script setup>
defineProps({
    type: {
        type: String,
        default: ' submit'
    },
    label: {
        type: String,
        default: 'Submit'
    }
});
</script>

<template>
    <button
        :type="type"
        class="w-full flex justify-center items-center py-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold rounded-md shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-transform transform"
    >
        {{ label}}
    </button>
</template>
