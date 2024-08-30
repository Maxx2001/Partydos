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
        class="border p-1 rounded bg-slate-100 hover:bg-slate-200 flex justify-center items-center"
    >
        {{ props.label }}
    </a>
</template>
