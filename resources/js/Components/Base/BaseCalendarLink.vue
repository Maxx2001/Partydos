<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <a
        :href="props.url"
        target="_blank"
        class="border p-1 rounded bg-indigo-500 hover:bg-indigo-700 flex justify-center items-center text-white"
    >
        {{ props.label }}
    </a>
</template>
