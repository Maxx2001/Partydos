<script setup>
import {computed, defineProps} from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
    backgroundColor: {
        type: String,
        required: true, // Expect an exact color class like "bg-blue-500"
    },
    hoverColor: {
        type: String,
        required: true, // Expect an exact hover color class like "hover:bg-blue-700"
    },
})
</script>

<template>
    <a
        :href="props.url"
        target="_blank"
        :class="`border p-4 rounded ${backgroundColor} ${hoverColor} flex justify-center items-center text-white text-xl font-bold`"
    >
        {{ props.label }}
    </a>
</template>
