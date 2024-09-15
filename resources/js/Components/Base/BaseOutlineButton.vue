<script setup>
defineProps({
    label: {
        type: String,
        default: 'Submit',
    },
    icon: {
        type: Function,
        required: false,
    },
    type: {
        type: String,
        default: 'button',
    },
    extraClasses: {
        type: String,
        default: '',
    },
})
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center gap-x-1.5 rounded-md bg-white border border-blue-600 px-3 py-2  font-semibold text-blue-600 hover:bg-blue-600 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition duration-300"
        :class="extraClasses"
    >
        <component v-if="icon" :is="icon" class="-ml-0.5 h-5 w-5" aria-hidden="true"/>
        <span>
            {{ label }}
        </span>
    </button>
</template>
