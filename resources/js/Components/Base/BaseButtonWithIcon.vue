<script setup>

defineProps({
    label: {
        type: String,
        default: 'Submit',
    },
    icon: {
        type: Object,
        required: false,
    },
})
</script>

<template>
    <button
        type="button"
        class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
    >
        <component :is="icon" class="-ml-0.5 h-5 w-5" aria-hidden="true" />
        {{ label }}
    </button>
</template>
