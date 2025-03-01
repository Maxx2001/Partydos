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
    },
    show: {
        type: Boolean,
        default: true
    }
})
</script>

<template>
    <a
        v-if="show"
        class="block py-1 text-xl font-semibold transition duration-300"
        :class="active ? 'text-blue-600 border-b-2 border-blue-600' : 'text-black'"
        :href="url"
    >
        {{  label }}
    </a>
</template>
