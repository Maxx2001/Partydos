<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    url: {
        type: String,
        required: true
    },
    active: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <a
        class="block text-2xl font-semibold transition duration-300 p-2"
        :class="active ? 'bg-white text-blue-600 rounded-md' : 'text-white'"
        :href="url"
    >
        {{  label }}
    </a>
</template>
