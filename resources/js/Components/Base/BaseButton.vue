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
        default: '',
    },
})

const buttonClasses = computed(() => {
    switch (props.variant) {
        case 'cancel':
            return 'bg-red-600 hover:bg-white hover:text-red-600 border-red-600';
        case 'submit':
            return 'bg-gradient-to-br from-blue-500 to-purple-600 hover:from-white hover:to-white hover:bg-white hover:text-indigo-600 hover:border-indigo-600';
        default:
            return 'bg-blue-600 hover:bg-white hover:text-blue-600 border-blue-600';
    }
});
</script>

<template>
    <button
        :type="type"
        :class="`inline-flex items-center justify-center gap-x-1.5 rounded-md px-6 py-3 font-semibold text-white border shadow-sm hover:border focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition duration-300 ${buttonClasses} ${extraClasses}`"
    >
        <span>
            {{ label }}
        </span>
    </button>
</template>
