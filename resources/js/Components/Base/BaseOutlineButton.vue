<script setup>
import { computed } from 'vue';

const props = defineProps({
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
    variant: {
        type: String,
        default: 'submit',
    },
})

const buttonClasses = computed(() => {
    switch (props.variant) {
        case 'cancel':
            return 'bg-red-600 text-white hover:bg-white hover:text-red-600 border-red-600';
        case 'submit':
        default:
            return 'bg-white border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white';
    }
});
</script>

<template>
    <button
        :type="type"
        :class="`inline-flex items-center justify-center gap-x-1.5 rounded-md px-4 py-2 font-semibold transition duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ${buttonClasses} ${extraClasses}`"
    >
        <component v-if="icon" :is="icon" class="-ml-0.5 h-5 w-5" aria-hidden="true" />
        <span class="text-lg">
            {{ label }}
        </span>
    </button>
</template>
